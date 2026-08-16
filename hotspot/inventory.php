<?php
/*
 * Inventory Management System
 * Tracks stock of ONT modems, cables, ODPs, tools, etc.
 */
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-archive"></i> Inventory Barang & Alat</h3>
                <button class="btn btn-sm btn-primary" onclick="alert('Membuka form tambah barang baru...')"><i class="fa fa-plus"></i> Tambah Barang</button>
            </div>
            <div class="card-body">
                
                <!-- Inventory Stats -->
                <div class="row mb-4">
                    <div class="col-4 col-box-6">
                        <div class="box bg-blue bmh-75">
                            <h1>12</h1>
                            <div>Kategori Barang</div>
                        </div>
                    </div>
                    <div class="col-4 col-box-6">
                        <div class="box bg-green bmh-75">
                            <h1>420</h1>
                            <div>Total Unit Tersedia</div>
                        </div>
                    </div>
                    <div class="col-4 col-box-6">
                        <div class="box bg-red bmh-75">
                            <h1>2</h1>
                            <div>Item Menipis / Habis</div>
                        </div>
                    </div>
                </div>

                <!-- Stock Inventory Table -->
                <div class="card" style="border: 1px solid #ddd;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong><i class="fa fa-list"></i> Daftar Stok Gudang Utama</strong>
                        <input type="text" id="search-inventory" class="form-control form-control-sm" placeholder="Cari nama barang atau kode..." style="width: 250px;">
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0" id="inventory-table">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok Total</th>
                                    <th>Terpakai / Keluar</th>
                                    <th>Tersedia</th>
                                    <th>Satuan</th>
                                    <th>Status Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>INV-ONT-ZTE-F660</td>
                                    <td>ZTE F660 ONT Wi-Fi Router</td>
                                    <td>Modem / ONU</td>
                                    <td>50</td>
                                    <td>32</td>
                                    <td><strong>18</strong></td>
                                    <td>Unit</td>
                                    <td><span class="badge bg-green">Aman</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Sesuaikan stok ZTE F660')"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Log mutasi keluar masuk barang...')"><i class="fa fa-history"></i> Mutasi</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-ONT-HG8245H</td>
                                    <td>Huawei HG8245H GPON ONU</td>
                                    <td>Modem / ONU</td>
                                    <td>30</td>
                                    <td>28</td>
                                    <td><strong>2</strong></td>
                                    <td>Unit</td>
                                    <td><span class="badge bg-red">Menipis</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Sesuaikan stok Huawei HG8245H')"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Log mutasi keluar masuk barang...')"><i class="fa fa-history"></i> Mutasi</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-CAB-FO-1CORE</td>
                                    <td>Kabel Drop Core Fiber Optik 3 Seling 1 Core</td>
                                    <td>Kabel / Pasif</td>
                                    <td>10</td>
                                    <td>8</td>
                                    <td><strong>2</strong></td>
                                    <td>Roll (1km)</td>
                                    <td><span class="badge bg-red">Menipis</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Sesuaikan stok Kabel Drop Core')"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Log mutasi keluar masuk barang...')"><i class="fa fa-history"></i> Mutasi</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-ODP-BOX-8</td>
                                    <td>Box ODP Plastik 8 Port Splitter</td>
                                    <td>Box ODP / Pasif</td>
                                    <td>15</td>
                                    <td>5</td>
                                    <td><strong>10</strong></td>
                                    <td>Unit</td>
                                    <td><span class="badge bg-green">Aman</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Sesuaikan stok Box ODP')"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Log mutasi keluar masuk barang...')"><i class="fa fa-history"></i> Mutasi</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-ACC-FASTCONN</td>
                                    <td>Fast Connector SC/UPC Biru</td>
                                    <td>Aksesoris / Konektor</td>
                                    <td>500</td>
                                    <td>120</td>
                                    <td><strong>380</strong></td>
                                    <td>Pcs</td>
                                    <td><span class="badge bg-green">Aman</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Sesuaikan stok Fast Connector')"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Log mutasi keluar masuk barang...')"><i class="fa fa-history"></i> Mutasi</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>INV-TOOL-SPLICER</td>
                                    <td>Fusion Splicer Fiber Optik (Alat Penyambung)</td>
                                    <td>Peralatan Kerja</td>
                                    <td>4</td>
                                    <td>4</td>
                                    <td><strong>0</strong></td>
                                    <td>Unit</td>
                                    <td><span class="badge bg-yellow">Semua Dipinjam</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Sesuaikan stok Splicer')"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="alert('Log mutasi keluar masuk barang...')"><i class="fa fa-history"></i> Mutasi</button>
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
    $("#search-inventory").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#inventory-table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
