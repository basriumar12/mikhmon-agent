<?php
/*
 * Network Side Map / Peta Jaringan
 * Interactive map of OLT, ODP nodes, and customers
 */

include_once(__DIR__ . '/../include/db_config.php');
$conn = getDBConnection();

$message = '';
$error = '';

// Handle adding new node location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_node') {
    $name = $_POST['node_name'];
    $type = $_POST['node_type'];
    $lat = (float)$_POST['latitude'];
    $lng = (float)$_POST['longitude'];
    $capacity = $_POST['capacity'] ?? '1:8';
    
    try {
        $ownerId = $_SESSION['owner_id'] ?? 0;
        $stmt = $conn->prepare("INSERT INTO network_nodes (node_name, node_type, latitude, longitude, capacity, used_ports, owner_id) 
                               VALUES (:name, :type, :lat, :lng, :capacity, 0, :owner_id)");
        $stmt->execute([
            ':name' => $name,
            ':type' => $type,
            ':lat' => $lat,
            ':lng' => $lng,
            ':capacity' => $capacity,
            ':owner_id' => $ownerId
        ]);
        $message = "Node $name berhasil ditambahkan ke peta!";
    } catch (Exception $e) {
        $error = "Gagal menambah node: " . $e->getMessage();
    }
}

// Fetch OLT & ODP nodes from database
$nodes = [];
try {
    $ownerId = $_SESSION['owner_id'] ?? 0;
    $stmt = $conn->prepare("SELECT * FROM network_nodes WHERE owner_id = ?");
    $stmt->execute([$ownerId]);
    $nodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fail silently
}

// Fetch customers coordinates
$customers = [];
try {
    $ownerId = $_SESSION['owner_id'] ?? 0;
    $stmt = $conn->prepare("SELECT name, latitude, longitude FROM billing_customers WHERE owner_id = ? AND latitude IS NOT NULL AND longitude IS NOT NULL");
    $stmt->execute([$ownerId]);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fail silently
}

// Find OLT center coordinates to center map
$oltLat = -7.348400;
$oltLng = 112.723400;
foreach ($nodes as $n) {
    if ($n['node_type'] === 'olt') {
        $oltLat = (float)$n['latitude'];
        $oltLng = (float)$n['longitude'];
        break;
    }
}
?>

<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="row">
    <div class="col-md-9 col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-map-marker"></i> Peta Jaringan GPON / ODP</h3>
                <div>
                    <button class="btn btn-sm btn-primary" onclick="$('#addNodeModal').show();"><i class="fa fa-plus"></i> Tambah Node</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="resetMap();"><i class="fa fa-refresh"></i> Reset View</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 550px; width: 100%;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-info-circle"></i> Info Node Jaringan</h3>
            </div>
            <div class="card-body">
                
                <?php if ($message): ?>
                <div class="alert alert-success small"><?= $message; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-danger small"><?= $error; ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <span class="badge bg-red" style="padding: 6px 12px; font-size: 13px;"><i class="fa fa-server"></i> OLT Node</span>
                    <p class="text-muted small mb-1">Pusat distribusi jaringan internet utama.</p>
                </div>
                <div class="mb-3">
                    <span class="badge bg-yellow" style="padding: 6px 12px; font-size: 13px; color: #333 !important;"><i class="fa fa-cube"></i> ODP Box</span>
                    <p class="text-muted small mb-1">Optical Distribution Point (Kotak Distribusi).</p>
                </div>
                <div class="mb-3">
                    <span class="badge bg-green" style="padding: 6px 12px; font-size: 13px;"><i class="fa fa-home"></i> Pelanggan</span>
                    <p class="text-muted small mb-1">Lokasi rumah pelanggan aktif.</p>
                </div>
                
                <hr>
                
                <h4>Ringkasan Node Gudang</h4>
                <table class="table table-bordered">
                    <tr>
                        <td>Total OLT</td>
                        <td><strong><?= count(array_filter($nodes, function($n){return $n['node_type']=='olt';})); ?></strong></td>
                    </tr>
                    <tr>
                        <td>Total ODP</td>
                        <td><strong><?= count(array_filter($nodes, function($n){return $n['node_type']=='odp';})); ?></strong></td>
                    </tr>
                    <tr>
                        <td>Pelanggan Berkoordinat</td>
                        <td><strong><?= count($customers); ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Node Modal -->
<div id="addNodeModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:45%; border-radius:8px;">
        <h4><i class="fa fa-plus"></i> Tambah Node Baru Ke Peta</h4>
        <hr>
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_node">
            <div class="form-group">
                <label>Nama Node *</label>
                <input type="text" name="node_name" class="form-control" placeholder="Contoh: ODP-SI-05" required>
            </div>
            <div class="form-group">
                <label>Tipe Node *</label>
                <select name="node_type" class="form-control" required>
                    <option value="odp">ODP (Optical Distribution Point)</option>
                    <option value="olt">OLT (Optical Line Terminal)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Latitude *</label>
                <input type="text" name="latitude" id="node-lat" class="form-control" placeholder="Contoh: -7.348400" required>
            </div>
            <div class="form-group">
                <label>Longitude *</label>
                <input type="text" name="longitude" id="node-lng" class="form-control" placeholder="Contoh: 112.723400" required>
            </div>
            <div class="form-group">
                <label>Kapasitas Splitting</label>
                <input type="text" name="capacity" class="form-control" value="1:8">
            </div>
            <p class="text-muted small"><em>Tips: Anda dapat mengklik sembarang lokasi di peta untuk mengambil nilai koordinat Latitude & Longitude secara otomatis!</em></p>
            <hr>
            <div class="text-right">
                <button type="button" class="btn btn-default" onclick="$('#addNodeModal').hide();">Batal</button>
                <button type="submit" class="btn btn-primary">Tambahkan</button>
            </div>
        </form>
    </div>
</div>

<script>
var map;
var dbNodes = <?= json_encode($nodes); ?>;
var dbCustomers = <?= json_encode($customers); ?>;
var centerLat = <?= $oltLat; ?>;
var centerLng = <?= $oltLng; ?>;

$(document).ready(function() {
    // Initialize map centering around OLT location
    map = L.map('map').setView([centerLat, centerLng], 15);
    
    // OSM tile base layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    
    // Draw OLT and ODP nodes from DB
    dbNodes.forEach(function(node) {
        var lat = parseFloat(node.latitude);
        var lng = parseFloat(node.longitude);
        
        if (node.node_type === 'olt') {
            L.circleMarker([lat, lng], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.8,
                radius: 12
            }).addTo(map).bindPopup("<b>OLT: " + node.node_name + "</b><br>Kapasitas: " + node.capacity);
        } else {
            L.circleMarker([lat, lng], {
                color: 'orange',
                fillColor: '#ff9900',
                fillOpacity: 0.8,
                radius: 8
            }).addTo(map).bindPopup("<b>ODP: " + node.node_name + "</b><br>Kapasitas: " + node.capacity + "<br>Terpakai: " + node.used_ports + " Port");
            
            // Draw fiber trunk line from OLT to this ODP
            L.polyline([
                [centerLat, centerLng],
                [lat, lng]
            ], {
                color: 'blue',
                weight: 2,
                opacity: 0.6,
                dashArray: '5, 5'
            }).addTo(map);
        }
    });
    
    // Draw Customers from DB
    dbCustomers.forEach(function(cust) {
        var lat = parseFloat(cust.latitude);
        var lng = parseFloat(cust.longitude);
        
        L.circleMarker([lat, lng], {
            color: 'green',
            fillColor: '#28a745',
            fillOpacity: 0.9,
            radius: 5
        }).addTo(map).bindPopup("<b>Pelanggan: " + cust.name + "</b><br>Status: Koneksi Aktif");
    });
    
    // Map click event to fetch coordinates automatically for adding node
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(6);
        var lng = e.latlng.lng.toFixed(6);
        
        $('#node-lat').val(lat);
        $('#node-lng').val(lng);
        
        // Auto-show modal if they want to register it
        // (Don't auto show to prevent annoyance, just show notice or populate)
    });
});

function resetMap() {
    if (map) {
        map.setView([centerLat, centerLng], 15);
    }
}
</script>
