<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $owner = auth('owners')->user();

        if ($owner) {
            // Bypass check for billing, checkout, and logout routes to allow payment renewal
            if ($request->is('owner/subscription*') || 
                $request->is('owner/checkout*') || 
                $request->is('owner/logout*') ||
                $request->is('api/webhook/*')) {
                return $next($request);
            }

            // Check if expired
            if ($owner->subscription_expires_at && $owner->subscription_expires_at->isPast()) {
                if ($request->ajax() || $request->hasHeader('X-Livewire')) {
                    \Filament\Notifications\Notification::make()
                        ->title('Masa Aktif Habis')
                        ->body('Masa aktif langganan Anda telah berakhir. Silakan lakukan pembayaran untuk melanjutkan.')
                        ->danger()
                        ->persistent()
                        ->send();
                }

                return redirect('/owner/subscription');
            }
        }

        return $next($request);
    }
}
