<?php

namespace App\Filament\Owner\Pages;

use Filament\Pages\Page;
use Livewire\Attributes\Url;

class MikhmonFrame extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.owner.pages.mikhmon-frame';

    #[Url]
    public ?string $hotspot = null;

    public function mount(): void
    {
        $this->hotspot = request()->query('hotspot');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $owner = auth('owners')->user();
        if ($owner) {
            $_SESSION["mikhmon"] = $owner->username;
            $_SESSION["owner_id"] = $owner->id;
            $_SESSION["owner_level"] = $owner->level;
            $_SESSION["timezone"] = $owner->timezone ?? 'Asia/Jakarta';
        }

        session_write_close();
    }

    public function getTitle(): string
    {
        $titles = [
            'agent-list' => 'Daftar Agent',
            'agent-add' => 'Tambah Agent',
            'agent-prices' => 'Harga Agent',
            'agent-topup' => 'Topup Saldo',
            'agent-transactions' => 'Transaksi Agent',
            'voucher-settings' => 'Format Voucher',
            'whatsapp-agent-settings' => 'WhatsApp Agent',
            'pricing' => 'Harga Jual Voucher',
            'payment-gateway-config' => 'Payment Gateway',
            'digiflazz-settings' => 'Digiflazz Settings',
            'payment-methods' => 'Payment Methods',
            'public-sales' => 'Transaksi Public',
            'billing-dashboard' => 'Billing Dashboard',
            'billing-profiles' => 'Profil Paket Billing',
            'billing-customers' => 'Pelanggan Billing',
            'billing-invoices' => 'Tagihan & Pembayaran',
            'billing-settings' => 'Pengaturan Billing',
            'olt-monitoring' => 'OLT Monitoring',
            'side-map' => 'Peta Jaringan',
            'inventory' => 'Inventory Barang',
            'ticketing' => 'Tiketing & Penugasan',
            'genieacs' => 'GenieACS',
        ];

        return $titles[$this->hotspot] ?? 'Mikhmon Panel';
    }
}
