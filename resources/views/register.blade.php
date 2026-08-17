<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun - Samudra Net</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b0f19;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-button {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
            transition: all 0.3s ease;
        }
        .glow-button:hover {
            box-shadow: 0 0 35px rgba(99, 102, 241, 0.7);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="text-gray-300 antialiased min-h-screen flex items-center justify-center p-6 relative overflow-x-hidden">

    <!-- Blur Blobs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-server text-white text-lg"></i>
                </div>
                <span class="text-2xl font-extrabold text-white tracking-wide">Samudra <span class="text-blue-400">Net</span></span>
            </a>
            <p class="text-sm text-gray-500 mt-2">Daftar akun Owner baru untuk memulai pengelolaan SaaS</p>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/15 border border-red-500/30 rounded-xl text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/register" method="POST" class="space-y-5">
                @csrf
                
                <!-- Plan Input (hidden but pre-selected) -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Paket Pilihan</label>
                    <select name="plan" id="plan" class="w-full px-4 py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-xl text-sm font-semibold text-white focus:outline-none focus:border-blue-500 transition">
                        <option value="bronze" class="bg-[#0b0f19]" {{ $selectedPlan === 'bronze' ? 'selected' : '' }}>Free Trial (7 Hari)</option>
                        <option value="silver" class="bg-[#0b0f19]" {{ $selectedPlan === 'silver' ? 'selected' : '' }}>Silver Plan (Rp 50.000)</option>
                        <option value="gold" class="bg-[#0b0f19]" {{ $selectedPlan === 'gold' ? 'selected' : '' }}>Gold Plan (Rp 100.000)</option>
                        <option value="platinum" class="bg-[#0b0f19]" {{ $selectedPlan === 'platinum' ? 'selected' : '' }}>Platinum Plan (Rp 200.000)</option>
                    </select>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-4 py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition" placeholder="Masukkan username">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition" placeholder="contoh@domain.com">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Nomor WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition" placeholder="Contoh: 08123456789">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition" placeholder="Buat kata sandi">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition" placeholder="Ulangi kata sandi">
                </div>

                <!-- Submit -->
                <button type="submit" class="glow-button w-full py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/20 transition-all mt-6">
                    Daftar & Lanjutkan Pembayaran
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-500">
                Sudah memiliki akun? <a href="/owner/login" class="text-blue-400 hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>

</body>
</html>
