<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Services\SumopodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected SumopodService $sumopodService;

    public function __construct(SumopodService $sumopodService)
    {
        $this->sumopodService = $sumopodService;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:silver,gold,platinum',
        ]);

        $owner = Auth::guard('owners')->user();
        if (!$owner) {
            return redirect('/owner/login')->withErrors(['session' => 'Silakan login terlebih dahulu.']);
        }

        $plan = $request->input('plan');
        $prices = [
            'silver' => 50000,
            'gold' => 100000,
            'platinum' => 200000,
        ];
        $amount = $prices[$plan];

        // Store target plan in session so we know what they paid for when they return,
        // but the webhook will also be the main source of truth.
        $orderId = 'SAAS-' . $owner->id . '-' . time();
        
        $successUrl = url('/owner');
        $cancelUrl = url('/owner');

        $paymentResult = $this->sumopodService->createPayment(
            $orderId,
            $amount,
            $successUrl,
            $cancelUrl,
            'QRIS'
        );

        if ($paymentResult['success']) {
            $paymentLink = $paymentResult['data']['payment_link_url'];
            return redirect()->away($paymentLink);
        }

        return redirect('/owner')->with('error', $paymentResult['message'] ?? 'Gagal membuat pembayaran ke gateway.');
    }
}
