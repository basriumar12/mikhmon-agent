<?php
/*
 * OLT Monitoring System
 * Support: ZTE C300, C320, HIOSO, HISFOCUS, VSOL, C-DATA
 */
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-desktop"></i> OLT Monitoring (ZTE & HIOSO)</h3>
                <div>
                    <select class="form-control" style="width: 250px; display: inline-block; vertical-align: middle;">
                        <option value="olt-1">ZTE C320 (Samudra Indah - OLT 01)</option>
                        <option value="olt-2">ZTE C300 (Samudra Indah - OLT 02)</option>
                        <option value="olt-3">HIOSO (Samudra Indah - OLT 03)</option>
                    </select>
                    <button class="btn btn-primary btn-sm" onclick="location.reload();">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-3 col-box-6">
                        <div class="box bg-blue bmh-75">
                            <h1>96</h1>
                            <div>Total ONU / ONT</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-green bmh-75">
                            <h1>88</h1>
                            <div>ONT Online</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-red bmh-75">
                            <h1>5</h1>
                            <div>ONT LOS (Loss of Signal)</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-yellow bmh-75">
                            <h1>3</h1>
                            <div>ONT Offline</div>
                        </div>
                    </div>
                </div>

                <!-- Signal Alert Info -->
                <div class="alert alert-info">
                    <strong>Informasi Redaman Signal:</strong> 
                    <span class="badge bg-green">-15 dBm s/d -24 dBm (Sangat Baik)</span>
                    <span class="badge bg-yellow">-25 dBm s/d -27 dBm (Kurang Baik)</span>
                    <span class="badge bg-red">> -28 dBm (Redaman Tinggi / Bermasalah)</span>
                </div>

                <!-- OLT Port Statistics -->
                <div class="card mb-4" style="border: 1px solid #ddd;">
                    <div class="card-header bg-light">
                        <strong><i class="fa fa-tasks"></i> Port Status & Usage</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>GPON Port</th>
                                    <th>Card Type</th>
                                    <th>Registered ONUs</th>
                                    <th>Traffic In</th>
                                    <th>Traffic Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>GPON 1/1/1</td>
                                    <td>GTGO (8 Port)</td>
                                    <td>32 ONUs</td>
                                    <td>125 Mbps</td>
                                    <td>380 Mbps</td>
                                    <td><span class="badge bg-green">Active</span></td>
                                </tr>
                                <tr>
                                    <td>GPON 1/1/2</td>
                                    <td>GTGO (8 Port)</td>
                                    <td>28 ONUs</td>
                                    <td>98 Mbps</td>
                                    <td>290 Mbps</td>
                                    <td><span class="badge bg-green">Active</span></td>
                                </tr>
                                <tr>
                                    <td>GPON 1/1/3</td>
                                    <td>GTGO (8 Port)</td>
                                    <td>36 ONUs</td>
                                    <td>150 Mbps</td>
                                    <td>420 Mbps</td>
                                    <td><span class="badge bg-green">Active</span></td>
                                </tr>
                                <tr>
                                    <td>GPON 1/1/4</td>
                                    <td>GTGO (8 Port)</td>
                                    <td>0 ONUs</td>
                                    <td>0 Mbps</td>
                                    <td>0 Mbps</td>
                                    <td><span class="badge bg-yellow">Empty</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed ONT List -->
                <div class="card" style="border: 1px solid #ddd;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong><i class="fa fa-list"></i> Daftar ONT Terdaftar (ZTE C320)</strong>
                        <input type="text" id="search-ont" class="form-control form-control-sm" placeholder="Cari SN / Nama / Pelanggan..." style="width: 250px;">
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0" id="ont-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>GPON Port</th>
                                    <th>Nama Pelanggan</th>
                                    <th>ONU ID / SN</th>
                                    <th>Redaman Signal</th>
                                    <th>Status</th>
                                    <th>Uptime / Downtime</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>1/1/1:1</td>
                                    <td>Ali Jaya (alijaya)</td>
                                    <td>ZTEG01A2B3C4</td>
                                    <td><strong style="color: green;">-18.4 dBm</strong></td>
                                    <td><span class="badge bg-green">Online</span></td>
                                    <td>12 Hari, 04:30:12</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Rebooting ONT ZTEG01A2B3C4...')"><i class="fa fa-refresh"></i> Reboot</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Menampilkan detail status ONT...')"><i class="fa fa-info-circle"></i> Info</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>1/1/1:2</td>
                                    <td>Budi Santoso (budis)</td>
                                    <td>ZTEG01A2B3C5</td>
                                    <td><strong style="color: green;">-21.2 dBm</strong></td>
                                    <td><span class="badge bg-green">Online</span></td>
                                    <td>05 Hari, 18:22:04</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Rebooting ONT ZTEG01A2B3C5...')"><i class="fa fa-refresh"></i> Reboot</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Menampilkan detail status ONT...')"><i class="fa fa-info-circle"></i> Info</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>1/1/2:1</td>
                                    <td>Cahaya Net (cahayanet)</td>
                                    <td>ZTEG01A2B3C6</td>
                                    <td><strong style="color: orange;">-26.8 dBm</strong></td>
                                    <td><span class="badge bg-green">Online</span></td>
                                    <td>02 Hari, 01:15:30</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Rebooting ONT ZTEG01A2B3C6...')"><i class="fa fa-refresh"></i> Reboot</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Menampilkan detail status ONT...')"><i class="fa fa-info-circle"></i> Info</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>1/1/2:2</td>
                                    <td>Deni Setiawan (denis)</td>
                                    <td>ZTEG01A2B3C7</td>
                                    <td><strong style="color: red;">-29.5 dBm</strong></td>
                                    <td><span class="badge bg-yellow">Online (Warning)</span></td>
                                    <td>00 Hari, 14:10:05</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Rebooting ONT ZTEG01A2B3C7...')"><i class="fa fa-refresh"></i> Reboot</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Menampilkan detail status ONT...')"><i class="fa fa-info-circle"></i> Info</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>1/1/3:1</td>
                                    <td>Eka Saputra (ekas)</td>
                                    <td>ZTEG01A2B3C8</td>
                                    <td><strong style="color: red;">N/A</strong></td>
                                    <td><span class="badge bg-red">LOS (Loss of Signal)</span></td>
                                    <td>Downtime: 03 Jam, 12:45</td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" disabled><i class="fa fa-refresh"></i> Reboot</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Menampilkan detail status ONT...')"><i class="fa fa-info-circle"></i> Info</button>
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
    $("#search-ont").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#ont-table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
