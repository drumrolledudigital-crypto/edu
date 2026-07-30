<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Exception;

class StripeService
{
    private $secretKey;
    private $publishableKey;

    public function __construct()
    {
        $this->publishableKey = SettingsService::getStripePublishableKey();
        $this->secretKey = SettingsService::getStripeSecretKey();
        
        if ($this->secretKey) {
            \Stripe\Stripe::setApiKey($this->secretKey);
        }
    }

    /**
     * Check if Stripe is properly configured.
     */
    public function isConfigured()
    {
        return !empty($this->secretKey) && !empty($this->publishableKey);
    }

    public function createCheckoutSession(Appointment $appointment, $student)
    {
        if (!$this->isConfigured()) {
            throw new Exception("Payments are currently unavailable. Please contact the administrator to configure Stripe API keys.");
        }

        // Session price from settings, fallback to 32.00
        $amount = (float) (\App\Models\Setting::get('session_price', '32.00'));
        $currency = strtolower(\App\Models\Setting::get('currency', 'usd')) ?: 'usd';

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => "Doubt Solving Session: " . $appointment->subject->name,
                        'description' => $appointment->doubt->title,
                    ],
                    'unit_amount' => $amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('student.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('student.payment.failed', ['appointment' => $appointment->id]),
            'customer_email' => $student->email,
            'metadata' => [
                'appointment_id' => $appointment->id,
                'student_id' => $student->id,
            ],
        ]);

        return $session;
    }

    public function getSession($sessionId)
    {
        if (!$this->isConfigured()) {
            throw new Exception("Stripe is not configured.");
        }
        return \Stripe\Checkout\Session::retrieve($sessionId);
    }
}
