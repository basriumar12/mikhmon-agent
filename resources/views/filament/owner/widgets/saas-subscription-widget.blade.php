@php
    $owner = auth('owners')->user();
    $currentLevel = $owner->level ?? 'bronze';
    $currentStatus = $owner->status ?? 'inactive';
@endphp

<div class="fi-wi-stats-overview-stat-keyboard-actions">
    <!-- Header Summary -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-lg mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-sm opacity-90 uppercase tracking-wider font-semibold">Status Langganan Anda</p>
                <h2 class="text-3xl font-extrabold mt-1">
                    Samudra Net - 
                    @if($currentLevel == 'bronze') Free Trial @elseif($currentLevel == 'silver') Silver Plan @elseif($currentLevel == 'gold') Gold Plan @elseif($currentLevel == 'platinum') Platinum Plan @endif
                </h2>
                <div class="flex items-center gap-3 mt-3">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold uppercase tracking-wider">
                        Level: {{ strtoupper($currentLevel) }}
                    </span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold uppercase tracking-wider">
                        Status: {{ strtoupper($currentStatus) }}
                    </span>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                <p class="text-xs opacity-90">Koneksi Mikhmon Anda:</p>
                <a href="/mikhmon/admin.php" target="_blank" class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-bold text-sm transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Buka Panel Mikhmon
                </a>
            </div>
        </div>
    </div>

    <!-- Pricing Upgrade Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Silver Plan Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border {{ $currentLevel == 'silver' ? 'border-indigo-500 ring-2 ring-indigo-500/50' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden shadow-sm flex flex-col justify-between">
            @if($currentLevel == 'silver')
                <div class="bg-indigo-500 text-white text-center py-1 text-xs font-bold uppercase tracking-wider">Plan Aktif Anda</div>
            @endif
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Silver Plan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cocok untuk jaringan RT RW Net pemula.</p>
                <div class="mt-4 flex items-baseline">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">Rp 50.000</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">/bulan</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        2 Router MikroTik
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        2 OLT Monitoring
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Support ZTE, HIOSO, dll.
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        400 Pelanggan
                    </li>
                </ul>
            </div>
            <div class="p-6 pt-0">
                <form action="/owner/checkout" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="silver">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-center font-bold text-sm {{ $currentLevel == 'silver' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 cursor-default' : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow transition' }}" {{ $currentLevel == 'silver' ? 'disabled' : '' }}>
                        {{ $currentLevel == 'silver' ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Gold Plan Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border {{ $currentLevel == 'gold' ? 'border-yellow-500 ring-2 ring-yellow-500/50' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden shadow-sm flex flex-col justify-between relative">
            <div class="absolute top-0 right-0 bg-yellow-500 text-white px-3 py-1 rounded-bl-xl text-[10px] font-extrabold uppercase tracking-widest">Terpopuler</div>
            @if($currentLevel == 'gold')
                <div class="bg-yellow-500 text-white text-center py-1 text-xs font-bold uppercase tracking-wider">Plan Aktif Anda</div>
            @endif
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gold Plan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fitur lengkap untuk manajemen prima.</p>
                <div class="mt-4 flex items-baseline">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">Rp 100.000</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">/bulan</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        4 Router MikroTik
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        3 OLT Monitoring
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        GenieACS & 800 Pelanggan
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        WhatsApp Notifikasi Free
                    </li>
                </ul>
            </div>
            <div class="p-6 pt-0">
                <form action="/owner/checkout" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="gold">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-center font-bold text-sm {{ $currentLevel == 'gold' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 cursor-default' : 'bg-yellow-600 hover:bg-yellow-700 text-white shadow transition' }}" {{ $currentLevel == 'gold' ? 'disabled' : '' }}>
                        {{ $currentLevel == 'gold' ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Platinum Plan Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border {{ $currentLevel == 'platinum' ? 'border-emerald-500 ring-2 ring-emerald-500/50' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden shadow-sm flex flex-col justify-between">
            @if($currentLevel == 'platinum')
                <div class="bg-emerald-500 text-white text-center py-1 text-xs font-bold uppercase tracking-wider">Plan Aktif Anda</div>
            @endif
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Platinum Plan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tanpa batas untuk ISP berkembang.</p>
                <div class="mt-4 flex items-baseline">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">Rp 200.000</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">/bulan</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited Router MikroTik
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited OLT Monitoring
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unlimited Pelanggan
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Sistem HRIS Absensi Wajah
                    </li>
                </ul>
            </div>
            <div class="p-6 pt-0">
                <form action="/owner/checkout" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="platinum">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-center font-bold text-sm {{ $currentLevel == 'platinum' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 cursor-default' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow transition' }}" {{ $currentLevel == 'platinum' ? 'disabled' : '' }}>
                        {{ $currentLevel == 'platinum' ? 'Aktif' : 'Pilih Plan / Perpanjang' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
