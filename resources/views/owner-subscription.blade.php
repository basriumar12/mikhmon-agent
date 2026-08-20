<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langganan & Upgrade - Samudra Net</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Samudra Net">
    <link rel="apple-touch-icon" href="/pwa/icon-192x192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px 20px;
            color: #1f2937;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        .header p {
            color: #6b7280;
            font-size: 16px;
            margin: 0;
        }
        .status-card {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        .status-info h2 {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 8px 0;
            color: white;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-right: 8px;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: #4f46e5;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        .back-btn:hover {
            transform: translateY(-2px);
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media (min-width: 768px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .card.active {
            border: 2px solid #4f46e5;
        }
        .active-badge {
            background: #4f46e5;
            color: white;
            text-align: center;
            padding: 4px 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: -24px -24px 20px -24px;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }
        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }
        .card-desc {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .card-price {
            font-size: 32px;
            font-weight: 800;
            margin-top: 16px;
            display: flex;
            align-items: baseline;
        }
        .card-price span {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            margin-left: 4px;
        }
        .features-list {
            margin: 24px 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .features-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .features-list svg {
            width: 16px;
            height: 16px;
            color: #10b981;
            flex-shrink: 0;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary {
            background: #4f46e5;
            color: white;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
        }
        .btn-primary:hover {
            background: #4338ca;
        }
        .btn-disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Upgrade & Perpanjang Langganan</h1>
            <p>Kelola lisensi platform SaaS MikroTik Anda secara mandiri.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Status Card -->
        <div class="status-card">
            <div class="status-info">
                <h2>Paket Saat Ini: {{ strtoupper($owner->level) }}</h2>
                <div>
                    <span class="badge">Status: {{ strtoupper($owner->status) }}</span>
                    @if($owner->subscription_expires_at)
                        <span class="badge" style="background: {{ $owner->subscription_expires_at->isPast() ? '#ef4444' : 'rgba(255, 255, 255, 0.2)' }};">
                            Masa Aktif: {{ $owner->subscription_expires_at->format('d M Y H:i') }}
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <a href="/mikhmon/admin.php" class="back-btn">
                    Kembali ke Panel Mikhmon
                </a>
            </div>
        </div>

        <!-- Pricing Upgrade Grid -->
        <div class="grid">
            @foreach($plans as $plan)
                <div class="card {{ $owner->level == $plan->slug ? 'active' : '' }}">
                    @if($owner->level == $plan->slug)
                        <div class="active-badge">Plan Aktif Anda</div>
                    @endif
                    <div>
                        <h3 class="card-title">{{ $plan->name }}</h3>
                        <p class="card-desc">{{ $plan->description }}</p>
                        <div class="card-price">
                            Rp {{ number_format($plan->price, 0, ',', '.') }}<span>/{{ $plan->billing_period == 'yearly' ? 'tahun' : ($plan->billing_period == '7_days' ? '7 hari' : 'bulan') }}</span>
                        </div>
                        @if($plan->features)
                            <ul class="features-list">
                                @foreach($plan->features as $feature)
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div>
                        @if($plan->price > 0)
                            <form action="/owner/checkout" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plan->slug }}">
                                <button type="submit" class="btn {{ $owner->level == $plan->slug ? 'btn-disabled' : 'btn-primary' }}" {{ $owner->level == $plan->slug ? 'disabled' : '' }}>
                                    {{ $owner->level == $plan->slug ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                                </button>
                            </form>
                        @else
                            <button class="btn btn-disabled" disabled>
                                Free Trial (Default)
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
