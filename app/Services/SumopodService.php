<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SumopodService
{
    protected string $apiKey = '642a01968d53909d47205eacaacf3c78a63c96637d44ae42f1e6e265eb6095f1';
    protected string $apiUrl = 'https://api-pay-sandbox.sumopod.com/api/v1/payments';

    /**
     * Create a payment checkout link on Sumopod
     */
    public function createPayment(string $orderId, int $amount, string $successUrl, string $cancelUrl, string $paymentMethod = 'QRIS')
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Api-Key' => $this->apiKey,
            ])->post($this->apiUrl, [
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => 'IDR',
                'expires_in_hours' => 24,
                'success_return_url' => $successUrl,
                'cancel_return_url' => $cancelUrl,
                'payment_method_type_code' => $paymentMethod,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Sumopod Payment Creation Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal membuat pembayaran ke gateway. Silakan coba beberapa saat lagi.',
            ];
        } catch (\Exception $e) {
            Log::error('Sumopod Exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi ke payment gateway: ' . $e->getMessage(),
            ];
        }
    }
}
