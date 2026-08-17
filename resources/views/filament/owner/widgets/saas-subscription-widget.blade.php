@php
    $owner = auth('owners')->user();
    $currentLevel = $owner->level ?? 'bronze';
    $currentStatus = $owner->status ?? 'inactive';
@endphp

<style>
    .saas-widget-container {
        font-family: inherit;
    }
    .saas-header-card {
        background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    .saas-header-card h2 {
        color: white !important;
        font-size: 28px;
        font-weight: 800;
        margin: 4px 0 12px 0;
        line-height: 1.2;
    }
    .saas-badge {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-right: 8px;
        color: white !important;
    }
    .saas-mikhmon-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #10b981;
        color: white !important;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: background 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .saas-mikhmon-btn:hover {
        background: #059669;
    }
    .saas-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    @media (min-width: 768px) {
        .saas-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .saas-plan-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        color: #1f2937 !important; /* Dark text for light theme */
        position: relative;
    }
    .dark .saas-plan-card {
        background: #1f2937;
        border-color: #374151;
        color: #f3f4f6 !important; /* Light text for dark theme */
    }
    .saas-plan-card.active {
        border: 2px solid #4f46e5;
    }
    .saas-plan-badge {
        background: #4f46e5;
        color: white !important;
        text-align: center;
        padding: 4px 0;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: -24px -24px 20px -24px;
    }
    .saas-plan-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }
    .saas-plan-desc {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }
    .dark .saas-plan-desc {
        color: #9ca3af;
    }
    .saas-plan-price {
        font-size: 32px;
        font-weight: 800;
        margin-top: 16px;
        display: flex;
        align-items: baseline;
    }
    .saas-plan-price span {
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        margin-left: 4px;
    }
    .dark .saas-plan-price span {
        color: #9ca3af;
    }
    .saas-plan-features {
        margin: 24px 0;
        padding: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .saas-plan-features li {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .saas-plan-features svg {
        width: 16px;
        height: 16px;
        color: #10b981;
        flex-shrink: 0;
    }
    .saas-button {
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
    .saas-button-primary {
        background: #4f46e5;
        color: white !important;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
    }
    .saas-button-primary:hover {
        background: #4338ca;
    }
    .saas-button-disabled {
        background: #f3f4f6;
        color: #9ca3af !important;
        cursor: default;
    }
    .dark .saas-button-disabled {
        background: #374151;
        color: #6b7280 !important;
    }
</style>

<div class="saas-widget-container">
    <!-- Header Summary Card -->
    <div class="saas-header-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9; margin: 0;">Status Langganan Anda</p>
                <h2>
                    Samudra Net - 
                    @if($currentLevel == 'bronze') Free Trial @elseif($currentLevel == 'silver') Silver Plan @elseif($currentLevel == 'gold') Gold Plan @elseif($currentLevel == 'platinum') Platinum Plan @endif
                </h2>
                <div>
                    <span class="saas-badge">Level: {{ strtoupper($currentLevel) }}</span>
                    <span class="saas-badge">Status: {{ strtoupper($currentStatus) }}</span>
                </div>
            </div>
            
            <div style="background: rgba(255, 255, 255, 0.1); padding: 16px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.15);">
                <p style="font-size: 12px; margin: 0 0 8px 0; opacity: 0.9;">Koneksi Mikhmon Anda:</p>
                <a href="/mikhmon/admin.php" target="_blank" class="saas-mikhmon-btn">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Buka Panel Mikhmon
                </a>
            </div>
        </div>
    </div>

    <!-- Pricing Upgrade Grid -->
    <div class="saas-grid">
        <!-- Silver Plan Card -->
        <div class="saas-plan-card {{ $currentLevel == 'silver' ? 'active' : '' }}">
            @if($currentLevel == 'silver')
                <div class="saas-plan-badge">Plan Aktif Anda</div>
            @endif
            <div>
                <h3 class="saas-plan-title">Silver Plan</h3>
                <p class="saas-plan-desc">Cocok untuk jaringan RT RW Net pemula.</p>
                <div class="saas-plan-price">
                    Rp 50.000<span>/bulan</span>
                </div>
                <ul class="saas-plan-features">
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        2 Router MikroTik
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        2 OLT Monitoring
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Support ZTE, HIOSO, dll.
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        400 Pelanggan
                    </li>
                </ul>
            </div>
            <div>
                <form action="/owner/checkout" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="silver">
                    <button type="submit" class="saas-button {{ $currentLevel == 'silver' ? 'saas-button-disabled' : 'saas-button-primary' }}" {{ $currentLevel == 'silver' ? 'disabled' : '' }}>
                        {{ $currentLevel == 'silver' ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Gold Plan Card -->
        <div class="saas-plan-card {{ $currentLevel == 'gold' ? 'active' : '' }}">
            @if($currentLevel == 'gold')
                <div class="saas-plan-badge">Plan Aktif Anda</div>
            @endif
            <div>
                <h3 class="saas-plan-title">Gold Plan</h3>
                <p class="saas-plan-desc">Fitur lengkap untuk manajemen prima.</p>
                <div class="saas-plan-price">
                    Rp 100.000<span>/bulan</span>
                </div>
                <ul class="saas-plan-features">
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        4 Router MikroTik
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        3 OLT Monitoring
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        GenieACS & 800 Pelanggan
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        WhatsApp Notifikasi Free
                    </li>
                </ul>
            </div>
            <div>
                <form action="/owner/checkout" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="gold">
                    <button type="submit" class="saas-button {{ $currentLevel == 'gold' ? 'saas-button-disabled' : 'saas-button-primary' }}" {{ $currentLevel == 'gold' ? 'disabled' : '' }}>
                        {{ $currentLevel == 'gold' ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Platinum Plan Card -->
        <div class="saas-plan-card {{ $currentLevel == 'platinum' ? 'active' : '' }}">
            @if($currentLevel == 'platinum')
                <div class="saas-plan-badge">Plan Aktif Anda</div>
            @endif
            <div>
                <h3 class="saas-plan-title">Platinum Plan</h3>
                <p class="saas-plan-desc">Tanpa batas untuk ISP berkembang.</p>
                <div class="saas-plan-price">
                    Rp 200.000<span>/bulan</span>
                </div>
                <ul class="saas-plan-features">
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited Router MikroTik
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited OLT Monitoring
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited Pelanggan
                    </li>
                    <li>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Sistem HRIS Absensi Wajah
                    </li>
                </ul>
            </div>
            <div>
                <form action="/owner/checkout" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="platinum">
                    <button type="submit" class="saas-button {{ $currentLevel == 'platinum' ? 'saas-button-disabled' : 'saas-button-primary' }}" {{ $currentLevel == 'platinum' ? 'disabled' : '' }}>
                        {{ $currentLevel == 'platinum' ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
