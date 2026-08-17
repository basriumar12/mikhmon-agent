<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SumopodWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Sumopod Webhook Received', $request->all());

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event !== 'payment.completed') {
            return response()->json(['status' => 'ignored', 'message' => 'Event not handled']);
        }

        if (empty($data) || empty($data['order_id'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid webhook payload'], 400);
        }

        $orderId = $data['order_id'];
        $amount = (int) ($data['amount'] ?? 0);

        // Check if this is a SaaS subscription payment (starts with SAAS-)
        if (str_starts_with($orderId, 'SAAS-')) {
            // Format: SAAS-{owner_id}-{timestamp}
            $parts = explode('-', $orderId);
            if (count($parts) < 3) {
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID format'], 400);
            }

            $ownerId = (int) $parts[1];
            $owner = Owner::find($ownerId);

            if (!$owner) {
                return response()->json(['status' => 'error', 'message' => 'Owner tenant not found'], 404);
            }

            // Determine plan level based on payment amount
            $level = 'bronze'; // Default
            if ($amount >= 200000) {
                $level = 'platinum';
            } elseif ($amount >= 100000) {
                $level = 'gold';
            } elseif ($amount >= 50000) {
                $level = 'silver';
            }

            // Activate the owner account and set the correct plan level
            $owner->status = 'active';
            $owner->level = $level;
            $owner->save();

            Log::info("SaaS Tenant Activated via Sumopod Webhook", [
                'owner_id' => $owner->id,
                'username' => $owner->username,
                'plan' => $level,
                'amount' => $amount,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Tenant activated successfully']);
        }

        return response()->json(['status' => 'ignored', 'message' => 'Order type not handled']);
    }
}
