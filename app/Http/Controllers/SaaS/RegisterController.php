<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Services\SumopodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected SumopodService $sumopodService;

    public function __construct(SumopodService $sumopodService)
    {
        $this->sumopodService = $sumopodService;
    }

    public function showRegistrationForm(Request $request)
    {
        $selectedPlan = $request->query('plan', 'bronze');
        return view('register', compact('selectedPlan'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:owners,username',
            'email' => 'required|string|email|max:100|unique:owners,email',
            'phone' => 'required|string|max:20|unique:owners,phone',
            'password' => 'required|string|min:6|confirmed',
            'plan' => 'required|in:bronze,silver,gold,platinum',
        ]);

        $plan = $request->input('plan');
        $status = ($plan === 'bronze') ? 'active' : 'inactive';

        // Create the SaaS Owner
        $owner = Owner::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'status' => $status,
            'level' => $plan,
        ]);

        if ($plan === 'bronze') {
            // Log in free trial immediately
            Auth::guard('owners')->login($owner);
            return redirect('/owner')->with('success', 'Pendaftaran berhasil! Selamat menikmati free trial.');
        }

        // Paid Plan checkout amounts
        $prices = [
            'silver' => 50000,
            'gold' => 100000,
            'platinum' => 200000,
        ];
        $amount = $prices[$plan];

        $orderId = 'SAAS-' . $owner->id . '-' . time();
        $successUrl = url('/owner');
        $cancelUrl = url('/register?plan=' . $plan);

        // Initiate payment checkout
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

        // If payment fails, delete the owner and return back
        $owner->delete();
        return back()->withErrors(['payment' => $paymentResult['message'] ?? 'Gagal memproses pembayaran.'])->withInput();
    }
}
