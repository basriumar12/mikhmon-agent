<?php
/*
 * Ticketing & Penugasan Teknisi System
 * Handles customer support complaints and work assignments
 */
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-ticket"></i> Sistem Tiketing & Penugasan</h3>
                <button class="btn btn-sm btn-primary" onclick="alert('Membuka form tiket baru...')"><i class="fa fa-plus"></i> Tiket Baru</button>
            </div>
            <div class="card-body">
                
                <!-- Ticket Stats -->
                <div class="row mb-4">
                    <div class="col-3 col-box-6">
                        <div class="box bg-blue bmh-75">
                            <h1>15</h1>
                            <div>Total Tiket (Bulan Ini)</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-red bmh-75">
                            <h1>3</h1>
                            <div>Tiket Open (Belum Ditangani)</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-yellow bmh-75">
                            <h1>4</h1>
                            <div>Tiket Proses (Pengerjaan)</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-green bmh-75">
                            <h1>8</h1>
                            <div>Tiket Selesai (Resolved)</div>
                        </div>
                    </div>
                </div>

                <!-- Active Tickets Table -->
                <div class="card" style="border: 1px solid #ddd;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong><i class="fa fa-list"></i> Daftar Pengaduan & Tugas Teknisi</strong>
                        <input type="text" id="search-tickets" class="form-control form-control-sm" placeholder="Cari tiket, pelanggan, teknisi..." style="width: 250px;">
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0" id="tickets-table">
                            <thead>
                                <tr>
                                    <th>No Tiket</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Keluhan / Gangguan</th>
                                    <th>Prioritas</th>
                                    <th>Teknisi Ditugaskan</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TCK-2026-0801</td>
                                    <td>Ali Jaya (alijaya)</td>
                                    <td>Internet lambat dan sering terputus sejak kemarin malam</td>
                                    <td><span class="badge bg-yellow" style="color:#333 !important;">Medium</span></td>
                                    <td><strong>Dedi (Teknisi 1)</strong></td>
                                    <td><span class="badge bg-yellow" style="color:#333 !important;">Proses</span></td>
                                    <td>2026-08-16 14:30</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Ubah penugasan teknisi...')"><i class="fa fa-user"></i> Tugaskan</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Update status tiket...')"><i class="fa fa-check"></i> Status</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TCK-2026-0802</td>
                                    <td>Budi Santoso (budis)</td>
                                    <td>Lampu LOS merah berkedip pada router (Kabel putus?)</td>
                                    <td><span class="badge bg-red">High</span></td>
                                    <td><strong>Ahmad (Teknisi 2)</strong></td>
                                    <td><span class="badge bg-red">Open</span></td>
                                    <td>2026-08-17 08:15</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Ubah penugasan teknisi...')"><i class="fa fa-user"></i> Tugaskan</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Update status tiket...')"><i class="fa fa-check"></i> Status</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TCK-2026-0803</td>
                                    <td>Cahaya Net (cahayanet)</td>
                                    <td>Request migrasi paket dari 20Mbps ke 50Mbps</td>
                                    <td><span class="badge bg-blue">Low</span></td>
                                    <td><strong>Belum Ditugaskan</strong></td>
                                    <td><span class="badge bg-red">Open</span></td>
                                    <td>2026-08-17 09:00</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Ubah penugasan teknisi...')"><i class="fa fa-user"></i> Tugaskan</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Update status tiket...')"><i class="fa fa-check"></i> Status</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TCK-2026-0798</td>
                                    <td>Deni Setiawan (denis)</td>
                                    <td>Modem sering restart sendiri secara acak</td>
                                    <td><span class="badge bg-yellow" style="color:#333 !important;">Medium</span></td>
                                    <td><strong>Dedi (Teknisi 1)</strong></td>
                                    <td><span class="badge bg-green">Selesai</span></td>
                                    <td>2026-08-15 10:20</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" disabled><i class="fa fa-user"></i> Tugaskan</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Membuka log riwayat keluhan...')"><i class="fa fa-history"></i> Log</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TCK-2026-0795</td>
                                    <td>Eka Saputra (ekas)</td>
                                    <td>Wi-Fi tidak bisa terhubung (Wrong password terus)</td>
                                    <td><span class="badge bg-blue">Low</span></td>
                                    <td><strong>Ahmad (Teknisi 2)</strong></td>
                                    <td><span class="badge bg-green">Selesai</span></td>
                                    <td>2026-08-14 16:45</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" disabled><i class="fa fa-user"></i> Tugaskan</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Membuka log riwayat keluhan...')"><i class="fa fa-history"></i> Log</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#search-tickets").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tickets-table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
