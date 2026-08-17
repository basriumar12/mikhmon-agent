<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samudra Net - SaaS Billing Engine & OLT Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b0f19;
        }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glow-button {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
            transition: all 0.3s ease;
        }
        .glow-button:hover {
            box-shadow: 0 0 35px rgba(99, 102, 241, 0.7);
            transform: translateY(-2px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="text-gray-300 antialiased overflow-x-hidden">

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-card">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-server text-white text-lg"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-wide">Samudra <span class="text-blue-400">Net</span></span>
            </a>
            
            <nav class="hidden md:flex items-center gap-8 font-medium">
                <a href="#features" class="hover:text-white transition">Fitur</a>
                <a href="#pricing" class="hover:text-white transition">Paket Harga</a>
                <a href="#about" class="hover:text-white transition">Tentang Kami</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/owner/login" class="px-5 py-2 rounded-xl text-sm font-semibold hover:text-white transition">Login</a>
                <a href="/register" class="glow-button px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-500/20">Daftar Sekarang</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-24 md:pt-40 md:pb-36 overflow-hidden">
        <!-- Background Blur Blobs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-1/3 right-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <span class="px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-bold uppercase tracking-wider">Premium SaaS Billing & Monitoring Engine</span>
            
            <h1 class="text-4xl md:text-7xl font-extrabold text-white tracking-tight mt-6 max-w-4xl mx-auto leading-tight">
                Kelola Jaringan RT RW Net Anda dengan <span class="gradient-text">Mudah & Otomatis</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-400 mt-6 max-w-2xl mx-auto leading-relaxed">
                Platform SaaS all-in-one terpercaya untuk monitoring OLT, billing hotspot mikrotik, WhatsApp notifikasi gateway, peta jaringan interaktif, dan absensi HRIS.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="/register?plan=bronze" class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-white rounded-2xl font-bold transition">
                    Coba Free Trial
                </a>
                <a href="#pricing" class="glow-button px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-500/30">
                    Lihat Paket Harga
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 glass-card border-y border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white">Segudang Fitur Unggulan Jaringan</h2>
                <p class="text-gray-400 mt-4 leading-relaxed">Dibuat khusus untuk efisiensi bisnis RT/RW Net Anda dari pengelolaan alat hingga otomatisasi pembayaran.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 hover:border-blue-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 mb-6">
                        <i class="fa-solid fa-desktop text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">OLT Monitoring Terintegrasi</h3>
                    <p class="text-gray-400 mt-3 text-sm leading-relaxed">Dukung SNMP & Telnet CLI untuk ZTE C300, C320, HIOSO, VSOL, dan lainnya. Monitor redaman dan status port ONU secara real-time.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 mb-6">
                        <i class="fa-solid fa-ticket text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Penjualan Voucher Online</h3>
                    <p class="text-gray-400 mt-3 text-sm leading-relaxed">Penjualan voucher hotspot otomatis dengan auto-approve pembayaran QRIS Sumopod. Dilengkapi integrasi template cetak voucher.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 hover:border-pink-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/20 flex items-center justify-center text-pink-400 mb-6">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">WhatsApp Gateway Gratis</h3>
                    <p class="text-gray-400 mt-3 text-sm leading-relaxed">Kirim detail akun voucher, pengingat tagihan bulanan PPPoE, dan notifikasi keluhan langsung ke WhatsApp pelanggan otomatis.</p>
                </div>

                <!-- Feature 4 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 hover:border-emerald-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 mb-6">
                        <i class="fa-solid fa-map-location-dot text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Peta Jaringan (Network Map)</h3>
                    <p class="text-gray-400 mt-3 text-sm leading-relaxed">Visualisasikan letak tiang, ODP, OTB, dan pelanggan Anda di peta interaktif menggunakan Google Maps API atau LeafletJS.</p>
                </div>

                <!-- Feature 5 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 mb-6">
                        <i class="fa-solid fa-clipboard-list text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Inventory & Ticketing</h3>
                    <p class="text-gray-400 mt-3 text-sm leading-relaxed">Pantau ketersediaan stok kabel, ONT, SFP, serta sistem penugasan teknisi lapangan untuk penyelesaian keluhan pelanggan.</p>
                </div>

                <!-- Feature 6 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 hover:border-yellow-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center text-yellow-400 mb-6">
                        <i class="fa-solid fa-face-smile text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Sistem Absensi HRIS Face</h3>
                    <p class="text-gray-400 mt-3 text-sm leading-relaxed">Manajemen absensi karyawan, teknisi, dan admin menggunakan deteksi wajah kamera smartphone untuk keakuratan tinggi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white">Paket Langganan Sesuai Kebutuhan</h2>
                <p class="text-gray-400 mt-4">Semua pembayaran diproses otomatis, instan, and aman melalui QRIS Sumopod.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-16">
                <!-- Free Trial -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 flex flex-col justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <h3 class="text-lg font-bold text-white">Free Trial</h3>
                        <p class="text-xs text-gray-500 mt-1">Coba gratis 7 hari</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-2xl font-extrabold text-white">Rp 0</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-xs text-gray-400">
                            <li>1 MikroTik Router</li>
                            <li>1 OLT Monitoring</li>
                            <li>Fitur Standar</li>
                        </ul>
                    </div>
                    <a href="/register?plan=bronze" class="mt-8 block w-full py-3 bg-white/10 hover:bg-white/20 text-white font-bold text-center rounded-xl text-sm transition">Mulai Coba</a>
                </div>

                <!-- Silver Plan -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 flex flex-col justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <h3 class="text-lg font-bold text-white">Silver Plan</h3>
                        <p class="text-xs text-gray-500 mt-1">Jaringan Pemula</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-3xl font-extrabold text-white">Rp 50K</span>
                            <span class="text-gray-500 ml-1 text-xs">/bln</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-xs text-gray-400">
                            <li>2 MikroTik Router</li>
                            <li>2 OLT Monitoring</li>
                            <li>400 Pelanggan</li>
                            <li>WhatsApp Gateway</li>
                        </ul>
                    </div>
                    <a href="/register?plan=silver" class="mt-8 block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-center rounded-xl text-sm transition shadow-md shadow-blue-500/20">Pilih Paket</a>
                </div>

                <!-- Gold Plan (Recommended) -->
                <div class="p-8 rounded-2xl bg-white/5 border-2 border-indigo-500 relative flex flex-col justify-between hover:-translate-y-1 transition duration-300 shadow-xl shadow-indigo-500/5">
                    <span class="absolute top-0 right-6 -translate-y-1/2 px-3 py-1 bg-indigo-500 text-white text-[10px] font-bold uppercase rounded-full tracking-wider">Terpopuler</span>
                    <div>
                        <h3 class="text-lg font-bold text-white">Gold Plan</h3>
                        <p class="text-xs text-indigo-400 mt-1 font-semibold">RT/RW Net Ultimate</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-3xl font-extrabold text-white">Rp 100K</span>
                            <span class="text-gray-500 ml-1 text-xs">/bln</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-xs text-gray-400">
                            <li class="text-gray-300 font-medium">4 MikroTik Router</li>
                            <li class="text-gray-300 font-medium">3 OLT Monitoring</li>
                            <li>800 Pelanggan</li>
                            <li>WhatsApp Gateway + ACS</li>
                            <li>Penjualan Voucher Online</li>
                        </ul>
                    </div>
                    <a href="/register?plan=gold" class="mt-8 block w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-center rounded-xl text-sm transition shadow-md shadow-indigo-500/20">Pilih Paket</a>
                </div>

                <!-- Platinum Plan -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/5 flex flex-col justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <h3 class="text-lg font-bold text-white">Platinum Plan</h3>
                        <p class="text-xs text-gray-500 mt-1">Enterprise ISP</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-3xl font-extrabold text-white">Rp 200K</span>
                            <span class="text-gray-500 ml-1 text-xs">/bln</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-xs text-gray-400">
                            <li>Unlimited MikroTik Router</li>
                            <li>Unlimited OLT Monitoring</li>
                            <li>Unlimited Pelanggan</li>
                            <li>WhatsApp & HRIS Absensi</li>
                        </ul>
                    </div>
                    <a href="/register?plan=platinum" class="mt-8 block w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-center rounded-xl text-sm transition shadow-md shadow-purple-500/20">Pilih Paket</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5 bg-[#070b12]">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-server text-white text-sm"></i>
                </div>
                <span class="text-lg font-bold text-white">Samudra <span class="text-blue-400">Net</span></span>
            </div>
            <p class="text-sm text-gray-500">&copy; 2026 Samudra Net. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/628123456789?text=Halo%20Samudra%20Net,%20saya%20tertarik%20dengan%20layanan%20SaaS%20Anda" target="_blank" class="fixed bottom-6 right-6 w-14 h-14 bg-[#25d366] text-white rounded-full flex items-center justify-center text-3xl shadow-lg z-50 hover:scale-110 transition duration-300">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

</body>
</html>
