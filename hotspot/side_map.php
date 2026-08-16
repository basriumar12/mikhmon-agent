<?php
/*
 * Network Side Map / Peta Jaringan
 * Interactive map of OLT, ODP nodes, and customers
 */
?>
<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="row">
    <div class="col-md-9 col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fa fa-map-marker"></i> Peta Jaringan GPON / ODP</h3>
                <button class="btn btn-sm btn-outline-secondary" onclick="resetMap();"><i class="fa fa-refresh"></i> Reset View</button>
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
                
                <h4>Ringkasan Node</h4>
                <table class="table table-bordered">
                    <tr>
                        <td>Total OLT</td>
                        <td><strong>1</strong></td>
                    </tr>
                    <tr>
                        <td>Total ODP</td>
                        <td><strong>4</strong></td>
                    </tr>
                    <tr>
                        <td>Total Pelanggan</td>
                        <td><strong>18</strong></td>
                    </tr>
                </table>
                
                <div class="alert alert-warning small">
                    <strong>Peta Simulasi:</strong> Anda dapat menggeser map untuk melihat visualisasi jaringan fiber optik & ODP Samudra Indah.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var map;
$(document).ready(function() {
    // Initializing Leaflet Map centered around Surabaya/Sidoarjo area (example coordinates)
    map = L.map('map').setView([-7.3484, 112.7234], 15);
    
    // Add OpenStreetMap base tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    
    // Custom Markers Colors using standard Leaflet icons or simple circles
    // OLT Marker (Red)
    var oltMarker = L.circleMarker([-7.3484, 112.7234], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.8,
        radius: 12
    }).addTo(map).bindPopup("<b>OLT Samudra Indah Center</b><br>ZTE C320 Main Node<br>Surabaya - Sidoarjo Line");
    
    // ODP Markers (Yellow)
    var odpList = [
        {name: "ODP-SI-01", coords: [-7.3465, 112.7215], capacity: "1:8", used: 5},
        {name: "ODP-SI-02", coords: [-7.3495, 112.7255], capacity: "1:8", used: 6},
        {name: "ODP-SI-03", coords: [-7.3515, 112.7225], capacity: "1:16", used: 4},
        {name: "ODP-SI-04", coords: [-7.3455, 112.7265], capacity: "1:8", used: 3}
    ];
    
    odpList.forEach(function(odp) {
        L.circleMarker(odp.coords, {
            color: 'orange',
            fillColor: '#ff9900',
            fillOpacity: 0.8,
            radius: 8
        }).addTo(map).bindPopup("<b>" + odp.name + "</b><br>Kapasitas: " + odp.capacity + "<br>Terpakai: " + odp.used + " Port");
        
        // Draw fiber line from OLT to ODP
        L.polyline([
            [-7.3484, 112.7234],
            odp.coords
        ], {
            color: 'blue',
            weight: 2,
            opacity: 0.6,
            dashArray: '5, 5'
        }).addTo(map);
    });
    
    // Customer Locations (Green)
    var customers = [
        [-7.3468, 112.7210], [-7.3462, 112.7218], [-7.3469, 112.7219], // Connected to ODP 1
        [-7.3498, 112.7250], [-7.3492, 112.7259], [-7.3496, 112.7258], // Connected to ODP 2
        [-7.3518, 112.7220], [-7.3512, 112.7229], // Connected to ODP 3
        [-7.3458, 112.7260], [-7.3452, 112.7269]  // Connected to ODP 4
    ];
    
    customers.forEach(function(cust, index) {
        L.circleMarker(cust, {
            color: 'green',
            fillColor: '#28a745',
            fillOpacity: 0.9,
            radius: 5
        }).addTo(map).bindPopup("<b>Rumah Pelanggan #" + (index+1) + "</b><br>Status: Koneksi Aktif<br>Redaman: -19.5 dBm");
    });
});

function resetMap() {
    if (map) {
        map.setView([-7.3484, 112.7234], 15);
    }
}
</script>
