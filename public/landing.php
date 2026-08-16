<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Hub - Sistem Billing Internet Terintegrasi RT RW Net & OLT</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --navbar-bg: rgba(11, 15, 25, 0.85);
            --card-bg: rgba(17, 24, 39, 0.6);
            --border-color: rgba(255, 255, 255, 0.08);
            --primary-color: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.35);
            --success-color: #10b981;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: var(--navbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            text-decoration: none;
        }

        .logo span {
            color: var(--primary-color);
        }

        .logo img {
            width: 32px;
            height: 32px;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        nav a:hover {
            color: #fff;
        }

        .btn-login-agent {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-login-agent:hover {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        /* Hero Section */
        .hero {
            padding-top: 150px;
            padding-bottom: 80px;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 60px;
        }

        @media (max-width: 768px) {
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--primary-color);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #fff;
        }

        .hero-title span {
            color: var(--primary-color);
        }

        .hero-desc {
            font-size: 18px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .hero-price {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 30px;
        }

        .hero-price span {
            font-size: 16px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .btn-cta {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            color: #fff;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transition: all 0.2s ease;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }

        .hero-img-container {
            display: flex;
            justify-content: center;
        }

        .hero-img-container img {
            width: 100%;
            max-width: 400px;
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.7));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Features Section */
        .features-section {
            padding: 60px 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        @media (max-width: 768px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
        }

        .feature-icon {
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 20px;
            filter: drop-shadow(0 0 8px var(--primary-glow));
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #fff;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* About Section */
        .about-section {
            padding: 80px 0;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            align-items: center;
            gap: 60px;
        }

        @media (max-width: 768px) {
            .about-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        .about-img img {
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
        }

        .about-content h2 {
            font-size: 36px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
            color: #fff;
        }

        .about-content p {
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        /* Pricing Section */
        .pricing-section {
            padding: 80px 0;
            text-align: center;
        }

        .section-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #fff;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 16px;
            margin-bottom: 50px;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            align-items: stretch;
        }

        @media (max-width: 1024px) {
            .pricing-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .pricing-grid {
                grid-template-columns: 1fr;
            }
        }

        .pricing-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.3s ease;
        }

        .pricing-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.15);
        }

        .pricing-card.popular {
            border-color: var(--primary-color);
            background: rgba(17, 24, 39, 0.8);
            box-shadow: 0 0 25px rgba(59, 130, 246, 0.2);
        }

        .badge-popular {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary-color);
            color: #fff;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .plan-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .pricing-card.popular .plan-name {
            color: var(--primary-color);
        }

        .plan-price {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 24px;
        }

        .plan-price span {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .plan-features-list {
            list-style: none;
            text-align: left;
            margin-bottom: 30px;
            flex-grow: 1;
        }

        .plan-features-list li {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .plan-features-list li i {
            color: var(--primary-color);
            font-size: 14px;
        }

        .btn-pricing {
            display: block;
            width: 100%;
            background: rgba(31, 41, 55, 0.8);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pricing-card.popular .btn-pricing, .btn-pricing:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--border-color);
            padding: 40px 0;
            background: rgba(11, 15, 25, 0.9);
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Floating WhatsApp Button */
        .wa-float {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .wa-float:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(37, 211, 102, 0.5);
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="container nav-container">
            <a href="#" class="logo">
                <img src="./img/favicon.png" alt="Logo">
                Billing<span>Hub</span>
            </a>
            <nav>
                <a href="#">HOME</a>
                <a href="#harga">HARGA</a>
                <a href="../agent/index.php">LOGIN PORTAL</a>
                <a href="./public/register.php" class="btn-login-agent">DAFTAR SEKARANG</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <div class="hero-badge">
                    <i class="fa fa-tag"></i> Aplikasi Billing Internet
                </div>
                <h1 class="hero-title">Sistem Billing Internet Terintegrasi untuk <span>RT RW Net</span></h1>
                <p class="hero-desc">
                    Mikhmon Agent memberikan solusi manajemen pelanggan ISP mandiri, otomasi penjualan voucher hotspot Mikrotik, cetak struk pembayaran, monitoring OLT GPON, dan penugasan teknisi dalam satu dashboard.
                </p>
                <div class="hero-price">
                    Rp 50.000 <span>/ bulan</span>
                </div>
                <a href="./public/register.php" class="btn-cta">Coba Gratis Sekarang</a>
            </div>
            <div class="hero-img-container">
                <img src="./img/favicon.png" alt="BillingHub Dashboard Preview">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-dashboard"></i></div>
                <h3>Integrasi Mudah</h3>
                <p>Integrasi cepat dengan Mikrotik dan support monitoring OLT ZTE C300, C320, HIOSO, HISFOCUS, HSGQ, GLOBAL, VSOL, C-DATA secara otomatis.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-line-chart"></i></div>
                <h3>Monitoring Real-time</h3>
                <p>Pantau status redaman ONU, status GPON port, and packet loss/LOS secara langsung dari dashboard agent Anda tanpa repot.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-phone"></i></div>
                <h3>Support 24/7</h3>
                <p>Tim support teknis kami siap mendampingi Anda via WhatsApp/Telegram untuk memastikan operasi RT RW Net tetap berjalan optimal.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container about-grid">
            <div class="about-img">
                <img src="./img/favicon.png" alt="Marketing Illustration" style="max-height: 350px; object-fit: contain;">
            </div>
            <div class="about-content">
                <h2>Solusi Aplikasi Billing Internet Terintegrasi untuk Bisnis Anda</h2>
                <p>
                    BillingHub adalah platform aplikasi billing internet yang dirancang khusus untuk penyedia layanan RT RW Net maupun ISP regional. Kami memahami kendala operasional yang Anda hadapi setiap hari.
                </p>
                <p>
                    Kami menghadirkan fitur terlengkap: isolasi pelanggan otomatis, notifikasi WhatsApp Gateway, integrasi Payment Gateway QRIS otomatis, monitoring kabel fiber optik, dan penugasan teknisi lapangan.
                </p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="pricing-section">
        <div class="container">
            <h2 class="section-title">Paket Aplikasi Billing Internet Terbaik</h2>
            <p class="section-subtitle">Pilih paket sesuai ukuran bisnis RT RW Net atau ISP Anda. Dapat ditingkatkan kapan saja.</p>
            
            <div class="pricing-grid">
                <!-- Bronze Plan -->
                <div class="pricing-card">
                    <div class="plan-name">Bronze</div>
                    <div class="plan-price">Rp 0 <span>/ masa uji</span></div>
                    <ul class="plan-features-list">
                        <li><i class="fa fa-check"></i> 1 Mikrotik</li>
                        <li><i class="fa fa-check"></i> 100 Pelanggan</li>
                        <li><i class="fa fa-check"></i> WhatsApp Notifikasi</li>
                        <li><i class="fa fa-check"></i> Voucher Hotspot Online</li>
                        <li><i class="fa fa-check"></i> Auto Login Mac</li>
                    </ul>
                    <a href="./public/register.php?plan=bronze" class="btn-pricing">Pilih & Daftar</a>
                </div>
                
                <!-- Silver Plan -->
                <div class="pricing-card">
                    <div class="plan-name">Silver</div>
                    <div class="plan-price">Rp 50.000 <span>/ bulan</span></div>
                    <ul class="plan-features-list">
                        <li><i class="fa fa-check"></i> 2 Mikrotik</li>
                        <li><i class="fa fa-check"></i> 1 OLT Monitoring</li>
                        <li><i class="fa fa-check"></i> 300 Pelanggan</li>
                        <li><i class="fa fa-check"></i> WhatsApp Notifikasi</li>
                        <li><i class="fa fa-check"></i> Penjualan Voucher Online</li>
                        <li><i class="fa fa-check"></i> Support Midtrans, QRIS</li>
                    </ul>
                    <a href="./public/register.php?plan=silver" class="btn-pricing">Pilih & Daftar</a>
                </div>
                
                <!-- Gold Plan -->
                <div class="pricing-card popular">
                    <div class="badge-popular">Populer</div>
                    <div class="plan-name">Gold</div>
                    <div class="plan-price">Rp 150.000 <span>/ bulan</span></div>
                    <ul class="plan-features-list">
                        <li><i class="fa fa-check"></i> 4 Mikrotik</li>
                        <li><i class="fa fa-check"></i> 3 OLT Monitoring</li>
                        <li><i class="fa fa-check"></i> 800 Pelanggan</li>
                        <li><i class="fa fa-check"></i> Peta Jaringan / Side Map</li>
                        <li><i class="fa fa-check"></i> Inventory Barang</li>
                        <li><i class="fa fa-check"></i> Sistem Tiketing</li>
                        <li><i class="fa fa-check"></i> Midtrans, Tripay, Sumopod</li>
                    </ul>
                    <a href="./public/register.php?plan=gold" class="btn-pricing">Pilih & Daftar</a>
                </div>
                
                <!-- Platinum Plan -->
                <div class="pricing-card">
                    <div class="plan-name">Platinum</div>
                    <div class="plan-price">Rp 300.000 <span>/ bulan</span></div>
                    <ul class="plan-features-list">
                        <li><i class="fa fa-check"></i> Unlimited Mikrotik</li>
                        <li><i class="fa fa-check"></i> 6 OLT Monitoring</li>
                        <li><i class="fa fa-check"></i> 1500 Pelanggan</li>
                        <li><i class="fa fa-check"></i> Peta Jaringan / Side Map</li>
                        <li><i class="fa fa-check"></i> HRIS Absensi Absen Wajah</li>
                        <li><i class="fa fa-check"></i> Prioritas Support</li>
                    </ul>
                    <a href="./public/register.php?plan=platinum" class="btn-pricing">Pilih & Daftar</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2026 BillingHub & Mikhmon Agent. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/628123456789" class="wa-float" target="_blank">
        <i class="fa fa-whatsapp"></i>
    </a>

</body>
</html>
