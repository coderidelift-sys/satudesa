<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengaturan Peta</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Peta</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Atur Batas Wilayah</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Peta -->
                        <div id="map" style="height: 500px; mb-2"></div>
                        <div class="card-body"></div>

                        <form action="<?= base_url('admin/map/save_wilayah') ?>" method="post">
                            <input type="hidden" id="koordinat" name="koordinat">

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama Wilayah</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_wilayah" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kategori Wilayah</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="kategori_wilayah" required>
                                        <option value="desa">Desa</option>
                                        <option value="kampung">Dusun/Kampung</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Wilayah</button>
                            </div>
                        </form>

                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Wilayah</th>
                                            <th>Kategori</th>
                                            <th>Koordinat</th>
                                            <th>Dibuat Pada</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($wilayah as $w) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $w->nama_wilayah ?></td>
                                            <td><?= ucfirst($w->kategori_wilayah) ?></td>
                                            <td><?= substr($w->koordinat, 0, 50) ?>...</td>
                                            <td><?= date('d-m-Y H:i', strtotime($w->created_at)) ?></td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1"
                                                    onclick="konfirmasiDelete('<?= base_url('admin/map/delete_wilayah/' . $w->id) ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
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
    </section>
</main>

<!-- Load Leaflet & Leaflet Draw -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

<script>
var map;
var wilayahTersimpan = <?php echo json_encode($wilayah); ?>;
var bounds = new L.LatLngBounds();

// Jika ada wilayah tersimpan, pusatkan ke titik pertama
if (wilayahTersimpan.length > 0) {
    var firstWilayah = JSON.parse(wilayahTersimpan[0].koordinat);
    var centerLatLng = firstWilayah[0][0];
    map = L.map('map').setView(centerLatLng, 13);
} else {
    map = L.map('map').setView([-6.200000, 106.816666], 13);
}

// Tambahkan pilihan layer
var openStreetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: 'Google Satellite'
});

var googleHybrid = L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: 'Google Hybrid'
});

// Tambahkan kontrol layer
var baseMaps = {
    "OpenStreetMap": openStreetMap,
    "Satelit": googleSat,
    "Hybrid": googleHybrid
};
L.control.layers(baseMaps).addTo(map);

// Tambahkan fitur gambar
var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
    draw: {
        polygon: true,
        marker: false,
        circle: false,
        rectangle: false,
        polyline: false
    },
    edit: {
        featureGroup: drawnItems
    }
});
map.addControl(drawControl);

map.on('draw:created', function(e) {
    var layer = e.layer;
    drawnItems.addLayer(layer);
    var coordinates = layer.getLatLngs();
    document.getElementById('koordinat').value = JSON.stringify(coordinates);
});

// Fungsi untuk menentukan warna berdasarkan kategori wilayah
function getColor(kategori) {
    return kategori === "desa" ? "blue" : "red"; // Desa: Biru, Kampung: Merah
}

// Menampilkan batas wilayah di peta
wilayahTersimpan.forEach(function(data) {
    var wilayah = JSON.parse(data.koordinat);
    var warna = getColor(data.kategori_wilayah);

    var polygon = L.polygon(wilayah, {
            color: warna
        }).addTo(map)
        .bindPopup("<b>" + data.nama_wilayah + "</b> (" + data.kategori_wilayah + ")");

    bounds.extend(polygon.getBounds());
});

if (wilayahTersimpan.length > 0) {
    map.fitBounds(bounds, {
        padding: [20, 20]
    });
}
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const submitButton = document.querySelector("button[type='submit']");

    submitButton.addEventListener("click", function(event) {
        event.preventDefault(); // Mencegah submit form langsung

        Swal.fire({
            title: "Simpan Wilayah?",
            text: "Pastikan data yang dimasukkan sudah benar!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(form);

                fetch("<?= base_url('admin/map/save_wilayah') ?>", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Wilayah berhasil disimpan.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: "Terjadi kesalahan, coba lagi.",
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Error!",
                            text: "Gagal menghubungi server.",
                            icon: "error"
                        });
                    });
            }
        });
    });
});
</script>