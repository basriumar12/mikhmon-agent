<?php
/*
 * Ticketing & Penugasan Teknisi System
 * Handles customer support complaints and work assignments
 */

include_once(__DIR__ . '/../include/db_config.php');
$conn = getDBConnection();

$message = '';
$error = '';

// Handle ticket creation or assignment updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $customer = $_POST['customer_name'];
        $complaint = $_POST['complaint'];
        $priority = $_POST['priority'];
        $technician = !empty($_POST['assigned_technician']) ? $_POST['assigned_technician'] : null;
        
        $ticket_number = 'TCK-' . date('Y') . '-' . rand(1000, 9999);
        
        try {
            $ownerId = $_SESSION['owner_id'] ?? 0;
            $stmt = $conn->prepare("INSERT INTO support_tickets (ticket_number, customer_name, complaint, priority, assigned_technician, status, owner_id) 
                                   VALUES (:ticket_number, :customer, :complaint, :priority, :technician, 'open', :owner_id)");
            $stmt->execute([
                ':ticket_number' => $ticket_number,
                ':customer' => $customer,
                ':complaint' => $complaint,
                ':priority' => $priority,
                ':technician' => $technician,
                ':owner_id' => $ownerId
            ]);
            $message = "Tiket baru $ticket_number berhasil dibuat!";
        } catch (Exception $e) {
            $error = "Gagal membuat tiket: " . $e->getMessage();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'assign') {
        $id = (int)$_POST['ticket_id'];
        $technician = $_POST['assigned_technician'];
        
        try {
            $stmt = $conn->prepare("UPDATE support_tickets SET assigned_technician = ?, status = 'process' WHERE id = ?");
            $stmt->execute([$technician, $id]);
            $message = "Teknisi berhasil ditugaskan!";
        } catch (Exception $e) {
            $error = "Gagal menugaskan teknisi: " . $e->getMessage();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $id = (int)$_POST['ticket_id'];
        $status = $_POST['status'];
        
        try {
            $stmt = $conn->prepare("UPDATE support_tickets SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $message = "Status tiket berhasil diperbarui!";
        } catch (Exception $e) {
            $error = "Gagal memperbarui status: " . $e->getMessage();
        }
    }
}

// Fetch support tickets
$tickets = [];
try {
    $ownerId = $_SESSION['owner_id'] ?? 0;
    $stmt = $conn->prepare("SELECT * FROM support_tickets WHERE owner_id = ? ORDER BY id DESC");
    $stmt->execute([$ownerId]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Gagal memuat tiket: " . $e->getMessage();
}

// Get dynamic stats
$totalTickets = count($tickets);
$openTickets = 0;
$processTickets = 0;
$resolvedTickets = 0;

foreach ($tickets as $t) {
    if ($t['status'] === 'open') {
        $openTickets++;
    } elseif ($t['status'] === 'process') {
        $processTickets++;
    } elseif ($t['status'] === 'resolved' || $t['status'] === 'closed') {
        $resolvedTickets++;
    }
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-ticket"></i> Sistem Tiketing & Penugasan</h3>
                <button class="btn btn-sm btn-primary" onclick="$('#createModal').show();"><i class="fa fa-plus"></i> Tiket Baru</button>
            </div>
            <div class="card-body">
                
                <?php if ($message): ?>
                <div class="alert alert-success"><i class="fa fa-check"></i> <?= $message; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?= $error; ?></div>
                <?php endif; ?>

                <!-- Ticket Stats -->
                <div class="row mb-4">
                    <div class="col-3 col-box-6">
                        <div class="box bg-blue bmh-75">
                            <h1><?= $totalTickets; ?></h1>
                            <div>Total Tiket</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-red bmh-75">
                            <h1><?= $openTickets; ?></h1>
                            <div>Tiket Open</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-yellow bmh-75">
                            <h1><?= $processTickets; ?></h1>
                            <div>Tiket Proses</div>
                        </div>
                    </div>
                    <div class="col-3 col-box-6">
                        <div class="box bg-green bmh-75">
                            <h1><?= $resolvedTickets; ?></h1>
                            <div>Tiket Selesai</div>
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
                                <?php foreach ($tickets as $t): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($t['ticket_number']); ?></strong></td>
                                    <td><?= htmlspecialchars($t['customer_name']); ?></td>
                                    <td><?= htmlspecialchars($t['complaint']); ?></td>
                                    <td>
                                        <?php if ($t['priority'] === 'critical'): ?>
                                            <span class="badge bg-red" style="font-weight: bold;">Critical</span>
                                        <?php elseif ($t['priority'] === 'high'): ?>
                                            <span class="badge bg-red">High</span>
                                        <?php elseif ($t['priority'] === 'medium'): ?>
                                            <span class="badge bg-yellow" style="color: #333 !important;">Medium</span>
                                        <?php else: ?>
                                            <span class="badge bg-blue">Low</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $t['assigned_technician'] ? '<strong>'.htmlspecialchars($t['assigned_technician']).'</strong>' : '<span class="text-muted">Belum Ditugaskan</span>'; ?>
                                    </td>
                                    <td>
                                        <?php if ($t['status'] === 'open'): ?>
                                            <span class="badge bg-red">Open</span>
                                        <?php elseif ($t['status'] === 'process'): ?>
                                            <span class="badge bg-yellow" style="color: #333 !important;">Proses</span>
                                        <?php elseif ($t['status'] === 'resolved'): ?>
                                            <span class="badge bg-green">Selesai (Resolved)</span>
                                        <?php else: ?>
                                            <span class="badge bg-default">Closed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $t['created_at']; ?></td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="openAssignModal(<?= $t['id']; ?>)"><i class="fa fa-user"></i> Tugas</button>
                                        <button class="btn btn-xs btn-outline-success" onclick="openStatusModal(<?= $t['id']; ?>, '<?= $t['status']; ?>')"><i class="fa fa-check"></i> Status</button>
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

<!-- Create Ticket Modal -->
<div id="createModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:50%; border-radius:8px;">
        <h4><i class="fa fa-plus"></i> Buat Tiket Keluhan Baru</h4>
        <hr>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label>Nama Pelanggan / ID *</label>
                <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Ali Jaya (alijaya)" required>
            </div>
            <div class="form-group">
                <label>Keluhan / Deskripsi Masalah *</label>
                <textarea name="complaint" class="form-control" rows="4" placeholder="Detail keluhan pelanggan..." required></textarea>
            </div>
            <div class="form-group">
                <label>Tingkat Prioritas *</label>
                <select name="priority" class="form-control" required>
                    <option value="low">Rendah (Low)</option>
                    <option value="medium" selected>Sedang (Medium)</option>
                    <option value="high">Tinggi (High)</option>
                    <option value="critical">Kritis (Critical)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tugaskan Teknisi (Opsional)</label>
                <select name="assigned_technician" class="form-control">
                    <option value="">-- Pilih Teknisi (Nanti) --</option>
                    <option value="Ahmad (Teknisi 1)">Ahmad (Teknisi 1)</option>
                    <option value="Budi (Teknisi 2)">Budi (Teknisi 2)</option>
                    <option value="Dedi (Teknisi 3)">Dedi (Teknisi 3)</option>
                </select>
            </div>
            <hr>
            <div class="text-right">
                <button type="button" class="btn btn-default" onclick="$('#createModal').hide();">Batal</button>
                <button type="submit" class="btn btn-primary">Buat Tiket</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Technician Modal -->
<div id="assignModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:40%; border-radius:8px;">
        <h4><i class="fa fa-user"></i> Tugaskan Teknisi</h4>
        <hr>
        <form method="POST" action="">
            <input type="hidden" name="action" value="assign">
            <input type="hidden" name="ticket_id" id="assign-ticket-id">
            <div class="form-group">
                <label>Pilih Teknisi *</label>
                <select name="assigned_technician" class="form-control" required>
                    <option value="Ahmad (Teknisi 1)">Ahmad (Teknisi 1)</option>
                    <option value="Budi (Teknisi 2)">Budi (Teknisi 2)</option>
                    <option value="Dedi (Teknisi 3)">Dedi (Teknisi 3)</option>
                </select>
            </div>
            <hr>
            <div class="text-right">
                <button type="button" class="btn btn-default" onclick="$('#assignModal').hide();">Batal</button>
                <button type="submit" class="btn btn-primary">Tugaskan</button>
            </div>
        </form>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:40%; border-radius:8px;">
        <h4><i class="fa fa-check"></i> Perbarui Status Tiket</h4>
        <hr>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="ticket_id" id="status-ticket-id">
            <div class="form-group">
                <label>Status Tiket *</label>
                <select name="status" id="status-select" class="form-control" required>
                    <option value="open">Open (Belum Dikerjakan)</option>
                    <option value="process">Process (Sedang Pengerjaan)</option>
                    <option value="resolved">Resolved (Selesai)</option>
                    <option value="closed">Closed (Ditutup)</option>
                </select>
            </div>
            <hr>
            <div class="text-right">
                <button type="button" class="btn btn-default" onclick="$('#statusModal').hide();">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Status</button>
            </div>
        </form>
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

function openAssignModal(id) {
    $('#assign-ticket-id').val(id);
    $('#assignModal').show();
}

function openStatusModal(id, currentStatus) {
    $('#status-ticket-id').val(id);
    $('#status-select').val(currentStatus);
    $('#statusModal').show();
}
</script>
