<?php
/*
 * OLT Monitoring System
 * Support: ZTE C300, C320, HIOSO, HISFOCUS, VSOL, C-DATA
 */

require_once(__DIR__ . '/../lib/OltClient.class.php');
$oltClient = new OltClient();
$selectedOlt = $_GET['olt'] ?? 'olt-1';
$onuList = $oltClient->getOnuList($selectedOlt);

// Calculate stats dynamically
$totalOnu = count($onuList);
$onlineOnu = 0;
$losOnu = 0;
$warningOnu = 0;

foreach ($onuList as $onu) {
    if ($onu['status'] === 'Online') {
        $onlineOnu++;
    } elseif ($onu['status'] === 'LOS') {
        $losOnu++;
    } else {
        $warningOnu++;
    }
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-desktop"></i> OLT Monitoring (ZTE & HIOSO)</h3>
                <div>
                    <select class="form-control" id="olt-selector" style="width: 250px; display: inline-block; vertical-align: middle;" onchange="changeOlt(this.value)">
                        <option value="olt-1" <?= $selectedOlt == 'olt-1' ? 'selected' : ''; ?>>ZTE C320 (Samudra Indah - OLT 01)</option>
                        <option value="olt-2" <?= $selectedOlt == 'olt-2' ? 'selected' : ''; ?>>ZTE C300 (Samudra Indah - OLT 02)</option>
                        <option value="olt-3" <?= $selectedOlt == 'olt-3' ? 'selected' : ''; ?>>HIOSO (Samudra Indah - OLT 03)</option>
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
                            <h1><?= $totalOnu; ?></h1>
                            <div>Total ONU / ONT</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-green bmh-75">
                            <h1><?= $onlineOnu; ?></h1>
                            <div>ONT Online</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-red bmh-75">
                            <h1><?= $losOnu; ?></h1>
                            <div>ONT LOS (Loss of Signal)</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-yellow bmh-75">
                            <h1><?= $warningOnu; ?></h1>
                            <div>ONT Warning / Offline</div>
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
                                <?php if ($selectedOlt === 'olt-3'): ?>
                                <tr>
                                    <td>PON 1</td>
                                    <td>EPON 4 Port</td>
                                    <td>2 ONUs</td>
                                    <td>15 Mbps</td>
                                    <td>45 Mbps</td>
                                    <td><span class="badge bg-green">Active</span></td>
                                </tr>
                                <?php else: ?>
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
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed ONT List -->
                <div class="card" style="border: 1px solid #ddd;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong><i class="fa fa-list"></i> Daftar ONT Terdaftar</strong>
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
                                <?php foreach ($onuList as $onu): ?>
                                <tr>
                                    <td><?= $onu['no']; ?></td>
                                    <td><?= $onu['port']; ?></td>
                                    <td><?= htmlspecialchars($onu['name']); ?></td>
                                    <td><?= htmlspecialchars($onu['sn']); ?></td>
                                    <td>
                                        <?php if ($onu['signal'] === null): ?>
                                            <strong style="color: red;">N/A</strong>
                                        <?php else: ?>
                                            <strong style="color: <?= $onu['signal'] < -27 ? 'red' : ($onu['signal'] < -25 ? 'orange' : 'green'); ?>;">
                                                <?= $onu['signal']; ?> dBm
                                            </strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($onu['status'] === 'Online'): ?>
                                            <span class="badge bg-green">Online</span>
                                        <?php elseif ($onu['status'] === 'LOS'): ?>
                                            <span class="badge bg-red">LOS</span>
                                        <?php else: ?>
                                            <span class="badge bg-yellow"><?= $onu['status']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $onu['uptime']; ?></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="alert('Rebooting ONT <?= htmlspecialchars($onu['sn']); ?>...')"><i class="fa fa-refresh"></i> Reboot</button>
                                        <button class="btn btn-xs btn-outline-info" onclick="alert('Menampilkan detail status ONT...')"><i class="fa fa-info-circle"></i> Info</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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

function changeOlt(val) {
    window.location.href = './?hotspot=olt-monitoring&session=<?= $_GET['session'] ?? ''; ?>&olt=' + val;
}
</script>
