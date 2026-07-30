<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - INV-{{ $payment->created_at->format('Y') }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#ecf7fc', 100: '#d0edf8', 200: '#a6dcf1', 300: '#6cc5e7', 400: '#3aabdb', 500: '#2596be', 600: '#1f7aa0', 700: '#1c6283', 800: '#1b526c', 900: '#1b455b', 950: '#112c3d' }
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg border border-slate-200 rounded-2xl overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">{{ \App\Models\Setting::get('platform_name', 'Drumroll') }}</span>
                </div>
                <p class="text-sm text-slate-500">Academic Doubt Solving Platform</p>
                <p class="text-sm text-slate-500">{{ \App\Models\Setting::get('support_email', 'contact@drumroll.com') }}</p>
            </div>
            <div class="text-right">
                <h1 class="text-3xl font-bold text-slate-900 uppercase tracking-tighter">Invoice</h1>
                <p class="text-sm font-mono text-slate-500 mt-1">#INV-{{ $payment->created_at->format('Y') }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="p-8 grid grid-cols-2 gap-8">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Bill To</h3>
                <p class="font-bold text-slate-900">{{ $payment->student->name }}</p>
                <p class="text-sm text-slate-600">{{ $payment->student->email }}</p>
                <p class="text-sm text-slate-600">Year: {{ $payment->student->student_class }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Payment Info</h3>
                <p class="text-sm text-slate-600">Date: {{ $payment->created_at->format('d M, Y') }}</p>
                <p class="text-sm text-slate-600">Transaction: <span class="font-mono">{{ $payment->transaction_id ?? 'N/A' }}</span></p>
                <p class="text-sm text-slate-600">Status: <span class="font-bold text-emerald-600 uppercase">{{ $payment->payment_status }}</span></p>
            </div>
        </div>

        <div class="p-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b-2 border-slate-100">
                        <th class="py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Quantity</th>
                        <th class="py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Price</th>
                        <th class="py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr>
                        <td class="py-6">
                            <p class="font-bold text-slate-900">{{ $payment->appointment ? $payment->appointment->subject->name : 'Live Doubt Session' }}</p>
                            <p class="text-xs text-slate-500 mt-1">One-to-One {{ \App\Models\Setting::get('session_duration', 50) }}-Minute Live Session</p>
                        </td>
                        <td class="py-6 text-right text-slate-600">1</td>
                        <td class="py-6 text-right text-slate-600">${{ number_format($payment->amount, 2) }}</td>
                        <td class="py-6 text-right font-bold text-slate-900">${{ number_format($payment->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-slate-50 flex justify-end">
            <div class="w-64 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="font-bold text-slate-900">${{ number_format($payment->amount, 2) }}</span>
                </div>
                @php $taxRate = (float) \App\Models\Setting::get('tax_rate', 0); @endphp
                @php $taxAmount = $payment->amount * ($taxRate / 100); @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Tax ({{ $taxRate }}%)</span>
                    <span class="font-bold text-slate-900">${{ number_format($taxAmount, 2) }}</span>
                </div>
                <div class="h-px bg-slate-200 my-2"></div>
                <div class="flex justify-between text-lg">
                    <span class="font-bold text-slate-900">Total</span>
                    <span class="font-bold text-primary-600">${{ number_format($payment->amount + $taxAmount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="p-8 text-center text-xs text-slate-400 italic">
            Thank you for choosing {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} for your academic success!
        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-8 flex justify-center no-print">
        <button onclick="window.print()" class="px-6 py-2 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print Invoice
        </button>
    </div>
</body>
</html>

