<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaasPlanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed initial pricing plans
        $plans = [
            [
                'slug' => 'bronze',
                'name' => 'Bronze Plan',
                'description' => 'Paket Dasar untuk RT RW Net pemula.',
                'price' => 50000,
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '1 Mikrotik',
                    '100 Pelanggan',
                    'Support Mode PPOE, STATIC, DHCP Hotspot',
                    'Free WhatsApp Notifikasi',
                    'Penjualan Voucher Online',
                    'Support Midtrans, Tripay & QRIN',
                    'Management Pelanggan',
                    'Inventory',
                    'Laporan Keuangan'
                ]),
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'silver',
                'name' => 'Silver Plan',
                'description' => 'Paket Menengah untuk perkembangan jaringan.',
                'price' => 100000,
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '2 Mikrotik',
                    '1 OLT Monitoring',
                    'Support ZTE C300, C320, HIOSO, HSGQ, GLOBAL, VSOL, C-DATA',
                    'GenieAcs',
                    '300 Pelanggan',
                    'Support Mode PPOE, STATIC, DHCP Hotspot',
                    'Free WhatsApp Notifikasi',
                    'Auto Login Mac Acak',
                    'Penjualan Voucher Online',
                    'Manajemen Mitra',
                    'Support Midtrans, Tripay & QRIN',
                    'Management Pelanggan',
                    'Inventory',
                    'Laporan Keuangan',
                    'Peta Jaringan/Side Map'
                ]),
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'gold',
                'name' => 'Gold Plan',
                'description' => 'Paket Profesional dengan fitur lengkap.',
                'price' => 200000,
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '4 Mikrotik',
                    '3 OLT Monitoring',
                    'Support ZTE C300, C320, HIOSO, HSGQ, GLOBAL, VSOL, C-DATA',
                    'GenieAcs',
                    '800 Pelanggan',
                    'Support Mode PPOE, STATIC, DHCP Hotspot',
                    'Free WhatsApp Notifikasi',
                    'Auto Login Mac Acak',
                    'Penjualan Voucher Online',
                    'Manajemen Mitra',
                    'Support Midtrans, Tripay & QRIN',
                    'Management Pelanggan',
                    'Inventory',
                    'Laporan Keuangan',
                    'Peta Jaringan/Side Map',
                    'Sistem Tiketing',
                    'Penugasan User',
                    'APK Mobile',
                    'Pengaturan SSID Mandiri Pelanggan',
                    'Fitur HRIS manajemen absensi deteksi wajah'
                ]),
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'platinum',
                'name' => 'Platinum Plan',
                'description' => 'Paket Enterprise tanpa kompromi.',
                'price' => 300000,
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '8 Mikrotik',
                    '6 OLT Monitoring',
                    'Support ZTE C300, C320, HIOSO, HSGQ, GLOBAL, VSOL, C-DATA',
                    'GenieAcs',
                    '1.500 Pelanggan',
                    'Support Mode PPOE, STATIC, DHCP Hotspot',
                    'Free WhatsApp Notifikasi',
                    'Auto Login Mac Acak',
                    'Penjualan Voucher Online',
                    'Manajemen Mitra',
                    'Support Midtrans, Tripay & QRIN',
                    'Management Pelanggan',
                    'Inventory',
                    'Laporan Keuangan',
                    'Peta Jaringan/Side Map',
                    'Sistem Tiketing',
                    'Penugasan User',
                    'APK Mobile',
                    'Pengaturan SSID Mandiri Pelanggan',
                    'Fitur HRIS manajemen absensi deteksi wajah'
                ]),
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('saas_plans')->updateOrInsert(['slug' => $plan['slug']], $plan);
        }

        // 2. Seed initial settings for Sumopod
        $settings = [
            [
                'key' => 'sumopod_api_key',
                'value' => '642a01968d53909d47205eacaacf3c78a63c96637d44ae42f1e6e265eb6095f1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sumopod_endpoint',
                'value' => 'https://api-pay-sandbox.sumopod.com/api/v1/payments',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sumopod_mode',
                'value' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('saas_settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
    }
}
