<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SaaS\LandingController;
use App\Http\Controllers\SaaS\RegisterController;
use App\Http\Controllers\SaaS\CheckoutController;
use App\Http\Controllers\Api\SumopodWebhookController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/owner/checkout', [CheckoutController::class, 'checkout'])->middleware('auth:owners');

Route::post('/api/webhook/sumopod', [SumopodWebhookController::class, 'handleWebhook']);
