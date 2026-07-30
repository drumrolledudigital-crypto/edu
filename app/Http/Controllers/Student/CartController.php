<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookPurchase;
use App\Services\AuditLoggerService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private $stripeSecret;

    public function __construct()
    {
        $this->stripeSecret = SettingsService::getStripeSecretKey();
    }

    private function isStripeConfigured(): bool
    {
        return !empty($this->stripeSecret) && !empty(SettingsService::getStripePublishableKey());
    }

    private function getCart()
    {
        return session()->get('book_cart', []);
    }

    private function setCart(array $cart)
    {
        session()->put('book_cart', $cart);
    }

    public function index()
    {
        $cart = $this->getCart();
        $total = array_sum(array_column($cart, 'price'));
        return view('student.cart.index', compact('cart', 'total'));
    }

    public function add(Book $book)
    {
        if (!$book->price || $book->status !== 'active') {
            return back()->with('error', 'This book is not available for purchase.');
        }

        $cart = $this->getCart();

        if (isset($cart[$book->id])) {
            return back()->with('info', 'This book is already in your cart.');
        }

        $cart[$book->id] = [
            'book_id' => $book->id,
            'title' => $book->title,
            'slug' => $book->slug,
            'price' => (float) $book->price,
            'cover_image' => $book->cover_image,
            'subject_name' => $book->subject->name ?? '',
        ];

        $this->setCart($cart);

        return redirect()->route('student.cart.index')->with('success', 'Book added to cart successfully.');
    }

    public function remove(Book $book)
    {
        $cart = $this->getCart();

        if (isset($cart[$book->id])) {
            unset($cart[$book->id]);
            $this->setCart($cart);
        }

        return redirect()->route('student.cart.index')->with('success', 'Book removed from cart.');
    }

    public function checkout()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('books.index')->with('error', 'Your cart is empty.');
        }

        if (!$this->isStripeConfigured()) {
            return back()->with('error', 'Payments are currently unavailable. Please contact the administrator.');
        }

        \Stripe\Stripe::setApiKey($this->stripeSecret);

        $lineItems = [];
        $totalAmount = 0;

        foreach ($cart as $item) {
            $amount = (int) round($item['price'] * 100);
            $totalAmount += $amount;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['title'],
                        'description' => $item['subject_name'] ?? 'Book',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('student.cart.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('student.cart.cancel'),
            'customer_email' => Auth::user()->email,
            'metadata' => [
                'user_id' => Auth::id(),
                'type' => 'book_purchase',
            ],
        ]);

        session()->put('book_checkout_session_id', $session->id);

        return redirect($session->url);
    }

    public function success(Request $request, AuditLoggerService $logger)
    {
        $sessionId = $request->query('session_id');
        $checkoutSessionId = session()->pull('book_checkout_session_id');

        if (!$sessionId || $sessionId !== $checkoutSessionId) {
            return redirect()->route('books.index')->with('error', 'Invalid session.');
        }

        \Stripe\Stripe::setApiKey($this->stripeSecret);

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', 'Could not verify payment.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('student.cart.cancel');
        }

        $cart = session()->pull('book_cart', []);

        foreach ($cart as $item) {
            BookPurchase::create([
                'user_id' => Auth::id(),
                'book_id' => $item['book_id'],
                'amount' => $item['price'],
                'stripe_session_id' => $session->id,
                'stripe_payment_intent' => $session->payment_intent,
                'status' => 'completed',
                'purchased_at' => now(),
            ]);
        }

        $logger->log('Book Purchase', 'Purchase', Auth::user()->name . ' purchased ' . count($cart) . ' book(s).', null, [
            'user_id' => Auth::id(),
            'books' => array_values($cart),
            'stripe_session_id' => $session->id,
        ]);

        return view('student.cart.success', ['count' => count($cart)]);
    }

    public function cancel()
    {
        session()->forget('book_checkout_session_id');
        return redirect()->route('student.cart.index')->with('info', 'Payment was cancelled.');
    }

    public function purchases()
    {
        $purchases = BookPurchase::with('book.subject')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest('purchased_at')
            ->get();

        return view('student.books.index', compact('purchases'));
    }
}
