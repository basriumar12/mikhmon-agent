<?php
/*
 * Inventory Management System
 * Tracks stock of ONT modems, cables, ODPs, tools, etc.
 */

include_once(__DIR__ . '/../include/db_config.php');
$conn = getDBConnection();

$message = '';
$error = '';

// Handle stock adjustment (Mutasi) or add item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $code = $_POST['item_code'];
        $name = $_POST['item_name'];
        $category = $_POST['category'];
        $total = (int)$_POST['total_stock'];
        $unit = $_POST['unit'] ?? 'Unit';
        $warehouse = $_POST['warehouse'] ?? 'Gudang Utama';
        
        try {
            $ownerId = $_SESSION['owner_id'] ?? 0;
            $stmt = $conn->prepare("INSERT INTO inventory_items (item_code, item_name, category, total_stock, unit, warehouse, owner_id) VALUES (:code, :name, :category, :total, :unit, :warehouse, :owner_id)");
            $stmt->execute([
                ':code' => $code,
                ':name' => $name,
                ':category' => $category,
                ':total' => $total,
                ':unit' => $unit,
                ':warehouse' => $warehouse,
                ':owner_id' => $ownerId
            ]);
            $message = "Barang $name berhasil ditambahkan!";
        } catch (Exception $e) {
            $error = "Gagal menambah barang: " . $e->getMessage();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'adjust') {
        $id = (int)$_POST['item_id'];
        $adjustment = (int)$_POST['adjustment_value'];
        $type = $_POST['adjustment_type']; // 'in' or 'out'
        
        try {
            // Get current stock
            $stmt = $conn->prepare("SELECT total_stock, used_stock FROM inventory_items WHERE id = ?");
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            
            if ($item) {
                if ($type === 'in') {
                    $new_total = $item['total_stock'] + $adjustment;
                    $stmt = $conn->prepare("UPDATE inventory_items SET total_stock = ? WHERE id = ?");
                    $stmt->execute([$new_total, $id]);
                } else {
                    $new_used = $item['used_stock'] + $adjustment;
                    $stmt = $conn->prepare("UPDATE inventory_items SET used_stock = ? WHERE id = ?");
                    $stmt->execute([$new_used, $id]);
                }
                $message = "Stok berhasil disesuaikan!";
            } else {
                $error = "Barang tidak ditemukan.";
            }
        } catch (Exception $e) {
            $error = "Gagal menyesuaikan stok: " . $e->getMessage();
        }
    }
}

// Fetch all inventory items
$items = [];
try {
    $ownerId = $_SESSION['owner_id'] ?? 0;
    $stmt = $conn->prepare("SELECT *, (total_stock - used_stock) AS available_stock FROM inventory_items WHERE owner_id = ? ORDER BY id DESC");
    $stmt->execute([$ownerId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Gagal memuat inventory: " . $e->getMessage();
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-archive"></i> Inventory Barang & Alat</h3>
                <button class="btn btn-sm btn-primary" onclick="$('#addModal').show();"><i class="fa fa-plus"></i> Tambah Barang</button>
            </div>
            <div class="card-body">
                
                <?php if ($message): ?>
                <div class="alert alert-success"><i class="fa fa-check"></i> <?= $message; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?= $error; ?></div>
                <?php endif; ?>

                <!-- Stock Inventory Table -->
                <div class="card" style="border: 1px solid #ddd;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong><i class="fa fa-list"></i> Daftar Stok Gudang</strong>
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
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['item_code']); ?></td>
                                    <td><?= htmlspecialchars($item['item_name']); ?></td>
                                    <td><?= htmlspecialchars($item['category']); ?></td>
                                    <td><?= $item['total_stock']; ?></td>
                                    <td><?= $item['used_stock']; ?></td>
                                    <td><strong><?= $item['available_stock']; ?></strong></td>
                                    <td><?= htmlspecialchars($item['unit']); ?></td>
                                    <td>
                                        <?php if ($item['available_stock'] <= 0): ?>
                                            <span class="badge bg-red">Habis</span>
                                        <?php elseif ($item['available_stock'] <= 3): ?>
                                            <span class="badge bg-red">Menipis</span>
                                        <?php else: ?>
                                            <span class="badge bg-green">Aman</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-primary" onclick="openAdjustModal(<?= $item['id']; ?>, '<?= htmlspecialchars($item['item_name']); ?>')"><i class="fa fa-edit"></i> Mutasi</button>
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

<!-- Add Item Modal -->
<div id="addModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:50%; border-radius:8px;">
        <h4><i class="fa fa-plus"></i> Tambah Barang Baru</h4>
        <hr>
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Kode Barang (SKU) *</label>
                <input type="text" name="item_code" class="form-control" placeholder="Contoh: INV-ONT-ZTE-F660" required>
            </div>
            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="item_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <select name="category" class="form-control" required>
                    <option value="Modem / ONU">Modem / ONU</option>
                    <option value="Kabel / Pasif">Kabel / Pasif</option>
                    <option value="Box ODP / Pasif">Box ODP / Pasif</option>
                    <option value="Aksesoris / Konektor">Aksesoris / Konektor</option>
                    <option value="Peralatan Kerja">Peralatan Kerja</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah Stok Awal *</label>
                <input type="number" name="total_stock" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="unit" class="form-control" value="Unit">
            </div>
            <div class="form-group">
                <label>Lokasi Gudang</label>
                <input type="text" name="warehouse" class="form-control" value="Gudang Utama">
            </div>
            <hr>
            <div class="text-right">
                <button type="button" class="btn btn-default" onclick="$('#addModal').hide();">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div id="adjustModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:40%; border-radius:8px;">
        <h4 id="adjust-title"><i class="fa fa-refresh"></i> Mutasi Barang</h4>
        <hr>
        <form method="POST" action="">
            <input type="hidden" name="action" value="adjust">
            <input type="hidden" name="item_id" id="adjust-item-id">
            <div class="form-group">
                <label>Tipe Mutasi</label>
                <select name="adjustment_type" class="form-control">
                    <option value="in">Masuk (Tambah Stok Utama)</option>
                    <option value="out">Keluar (Gunakan / Pasang ke Pelanggan)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah Mutasi *</label>
                <input type="number" name="adjustment_value" class="form-control" min="1" required>
            </div>
            <hr>
            <div class="text-right">
                <button type="button" class="btn btn-default" onclick="$('#adjustModal').hide();">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Mutasi</button>
            </div>
        </form>
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

function openAdjustModal(id, name) {
    $('#adjust-item-id').val(id);
    $('#adjust-title').html('<i class="fa fa-refresh"></i> Mutasi Barang: ' + name);
    $('#adjustModal').show();
}
</script>
