<?php
$current_page = $this->uri->segment(1);
?>

<!-- Footer Navigation -->
<div class="footer">
    <a href="<?= base_url('') ?>" class="footer-item <?= ($current_page == '' || $current_page == '') ? 'active' : '' ?>">
        <i class="fas fa-home footer-icon"></i>
        <span class="footer-label">Beranda</span>
    </a>
    <a href="<?= base_url('faq') ?>" class="footer-item <?= ($current_page == 'faq') ? 'active' : '' ?>">
        <i class="fas fa-question-circle footer-icon"></i>
        <span class="footer-label">FAQ</span>
    </a>
    <a href="<?= base_url('kontak') ?>" class="footer-item <?= ($current_page == 'kontak') ? 'active' : '' ?>">
        <i class="fas fa-phone footer-icon"></i>
        <span class="footer-label">Kontak</span>
    </a>
    <a href="<?= base_url('notif') ?>" class="footer-item <?= ($current_page == 'notif') ? 'active' : '' ?>">
        <i class="fas fa-bell footer-icon"></i>
        <span class="footer-label">Notifikasi</span>
    </a>
<a href="javascript:void(0);" id="installButton" class="footer-item install-slide" style="display: none;">
  <div style="display: flex; flex-direction: column; align-items: center;">
    <i class="fas fa-download footer-icon"></i>
    <span class="footer-label">Install</span>
  </div>
</a>

</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('assets/assets_js/js/popper.min.js') ?>"></script>
<script src="<?= base_url('assets/assets_js/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/assets_js/js/chart.js') ?>"></script>
<script src="<?= base_url('assets/assets_js/js/leaflet.js') ?>"></script>
<script src="<?= base_url('assets/assets_js/js/leaflet.draw.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<!-- FontAwesome untuk ikon cuaca -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    let deferredPrompt = null;

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent default prompt
        e.preventDefault();
        deferredPrompt = e;

        // Show the install button
        const installBtn = document.getElementById('installButton');
        if (installBtn) {
            installBtn.style.display = 'flex';
        }
    });

    document.getElementById('installButton').addEventListener('click', async () => {
        if (!deferredPrompt) return;

        // SweetAlert confirmation
        const result = await Swal.fire({
            title: 'Install Aplikasi?',
            text: 'Aplikasi ini akan ditambahkan ke layar utama.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Install',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33'
        });

        if (result.isConfirmed) {
            // Only now show the native prompt
            deferredPrompt.prompt();

            const choiceResult = await deferredPrompt.userChoice;
            if (choiceResult.outcome === 'accepted') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Aplikasi berhasil di-install.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Proses install dibatalkan.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Hanya bisa dipakai sekali
            deferredPrompt = null;
            document.getElementById('installButton').style.display = 'none';
        }
    });
</script>


<script>
const apiKey = "79cc7a03c88b03f6be096f3244a726ea"; // Ganti dengan API Key Anda

// Pemetaan ikon dari OpenWeatherMap ke FontAwesome
const weatherIcons = {
    "01d": "fas fa-sun", // Cerah (siang)
    "01n": "fas fa-moon", // Cerah (malam)
    "02d": "fas fa-cloud-sun", // Berawan sebagian (siang)
    "02n": "fas fa-cloud-moon", // Berawan sebagian (malam)
    "03d": "fas fa-cloud", // Berawan
    "03n": "fas fa-cloud",
    "04d": "fas fa-cloud-meatball", // Mendung
    "04n": "fas fa-cloud-meatball",
    "09d": "fas fa-cloud-showers-heavy", // Hujan deras
    "09n": "fas fa-cloud-showers-heavy",
    "10d": "fas fa-cloud-rain", // Hujan ringan (siang)
    "10n": "fas fa-cloud-rain", // Hujan ringan (malam)
    "11d": "fas fa-bolt", // Badai petir
    "11n": "fas fa-bolt",
    "13d": "fas fa-snowflake", // Salju
    "13n": "fas fa-snowflake",
    "50d": "fas fa-smog", // Kabut
    "50n": "fas fa-smog"
};

async function fetchWeather(city) {
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}&lang=id`;
    const response = await fetch(url);
    const data = await response.json();

    // Update tampilan cuaca
    document.getElementById("temperature").textContent = data.main.temp;
    document.getElementById("humidity").textContent = data.main.humidity;
    document.getElementById("wind-speed").textContent = data.wind.speed;
    document.getElementById("weather-condition").textContent = data.weather[0].description;

    // Ganti ikon berdasarkan kode cuaca
    const iconCode = data.weather[0].icon;
    const iconClass = weatherIcons[iconCode] || "fas fa-question-circle"; // Default jika tidak ditemukan
    document.getElementById("weather-icon").className = iconClass + " fa-4x";
}

document.getElementById("weather-city").addEventListener("change", function() {
    fetchWeather(this.value);
});

// Load cuaca default untuk Palabuhanratu
fetchWeather("Sukabumi");
</script>

<script>
async function fetchPrayerTimes(city) {
    const url = `https://api.aladhan.com/v1/timingsByCity?city=${city}&country=Indonesia&method=2`;
    const response = await fetch(url);
    const data = await response.json();
    const timings = data.data.timings;

    document.querySelector("#prayer-times").innerHTML = `
            <td>${timings.Fajr}</td>
            <td>${timings.Dhuhr}</td>
            <td>${timings.Asr}</td>
            <td>${timings.Maghrib}</td>
            <td>${timings.Isha}</td>
        `;
}

document.getElementById("city").addEventListener("change", function() {
    fetchPrayerTimes(this.value);
});

// Load default city (Palabuhanratu)
fetchPrayerTimes("Sukabumi");
</script>

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
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('wargaChart').getContext('2d');

    const data = [
        <?= $total_laki ?? 0 ?>,
        <?= $total_perempuan ?? 0 ?>,
        <?= $total_warga ?? 0 ?>,
        <?= $total_kk ?? 0 ?>
    ];

    const wargaChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pria', 'Wanita', 'Warga', 'Keluarga'],
            datasets: [{
                label: 'Jumlah Warga',
                data: data,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)', // Pria
                    'rgba(255, 99, 132, 0.8)', // Wanita
                    'rgba(255, 206, 86, 0.8)', // Warga
                    'rgba(75, 192, 192, 0.8)'  // Keluarga
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 0 // Hilangkan garis pemisah
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Warga'
                },
                datalabels: {
                    color: '#fff',
                    formatter: function(value, context) {
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + '%';
                        return percentage;
                    },
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
<!-- JavaScript untuk Pencarian -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxProfesi = document.getElementById('profesiChart').getContext('2d');

    // Data dari PHP
    const dataPekerjaan = <?= json_encode($pekerjaan) ?>;

    // Ambil label & data
    const labels = dataPekerjaan.map(item => item.pekerjaan);
    const dataCounts = dataPekerjaan.map(item => item.total);

    const profesiChart = new Chart(ctxProfesi, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Warga',
                data: dataCounts,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 205, 86, 0.8)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 205, 86, 1)'
                ],
                borderWidth: 0 // Hilangkan garis antar segmen
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Profesi Warga'
                },
                datalabels: {
                    color: '#fff',
                    formatter: function(value, context) {
                        const data = context.chart.data.datasets[0].data;
                        const total = data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + "%";
                        return percentage;
                    },
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>

 <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var ctx = document.getElementById('grafikDataWargaChart').getContext('2d');
                        var dataGrafik = <?= json_encode($grafik_data_warga); ?>;

                        var labels = dataGrafik.map(item => item.alamat);
                        var totalKK = dataGrafik.map(item => parseInt(item.total_kk));
                        var totalWarga = dataGrafik.map(item => parseInt(item.total_warga));
                        var totalLaki = dataGrafik.map(item => parseInt(item.total_laki));
                        var totalPerempuan = dataGrafik.map(item => parseInt(item.total_perempuan));

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Total KK',
                                        data: totalKK,
                                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                    },
                                    {
                                        label: 'Total Warga',
                                        data: totalWarga,
                                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                                    },
                                    {
                                        label: 'Laki-laki',
                                        data: totalLaki,
                                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                                    },
                                    {
                                        label: 'Perempuan',
                                        data: totalPerempuan,
                                        backgroundColor: 'rgba(255, 159, 64, 0.6)'
                                    }
                                ]
                            }
                        });
                    });
                    </script>
                    
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById("grafikSuratMetode").getContext("2d");

    // Data chart
    var data = {
        labels: ["Offline", "Online"],
        datasets: [{
            data: [
                <?php 
                $offline = 0; $online = 0;
                foreach ($grafik_metode as $g) {
                    if ($g['metode'] == 'Offline') { $offline = $g['total']; }
                    if ($g['metode'] == 'Online') { $online = $g['total']; }
                }
                echo "$offline, $online"; 
                ?>
            ],
            backgroundColor: ["#f39c12", "#3498db"],
            borderColor: ["#f39c12", "#3498db"],
            borderWidth: 0
        }]
    };

    // Opsi chart
    var options = {
        responsive: true,
        plugins: {
            legend: {
                display: true
            },
            datalabels: {
                color: '#fff',
                formatter: function(value, context) {
                    const data = context.chart.data.datasets[0].data;
                    const total = data.reduce((a, b) => a + b, 0);
                    const percentage = ((value / total) * 100).toFixed(1) + "%";
                    return percentage;
                },
                font: {
                    weight: 'bold',
                    size: 14
                }
            }
        }
    };

    // Inisialisasi chart
    new Chart(ctx, {
        type: "doughnut",
        data: data,
        options: options,
        plugins: [ChartDataLabels] // Aktifkan plugin di sini
    });
});
</script>

                    
                    
<script>
document.getElementById('search-input').addEventListener('input', function() {
    const searchQuery = this.value.toLowerCase();
    const layananItems = document.querySelectorAll('.layanan-item');

    layananItems.forEach(function(item) {
        const textContent = item.textContent.toLowerCase();

        if (textContent.includes(searchQuery)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

  
  <script>
document.getElementById('btnSubmitAduan').addEventListener('click', function () {
    const form = document.getElementById('formAduan');
    const nik = document.getElementById('nik').value;

    // Validasi NIK 16 digit
    if (nik.length !== 16) {
        Swal.fire({
            icon: 'warning',
            title: 'NIK tidak valid',
            text: 'NIK harus terdiri dari 16 digit angka.',
        });
        return;
    }

    // Tampilkan konfirmasi sebelum submit
    Swal.fire({
        title: 'Konfirmasi Pengiriman',
        text: "Dengan ini Anda siap bertanggung jawab atas informasi yang dikirimkan.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kirim',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // Submit form jika disetujui
        }
    });
});
</script>




  <!-- Inisialisasi Select2 -->
  <script>
    $(document).ready(function () {
      $('.select2').select2({
        placeholder: "-- Pilih Alamat --",
        allowClear: true
      });
    });
  </script>


<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('<?= base_url("/service-worker.js") ?>')
            .then(registration => {
                console.log('ServiceWorker registered with scope: ', registration.scope);

                // Cek update service worker
                registration.onupdatefound = () => {
                    const newWorker = registration.installing;
                    newWorker.onstatechange = () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            alert('Aplikasi telah diperbarui! Silakan refresh halaman.');
                        }
                    };
                };
            })
            .catch(err => {
                console.log('ServiceWorker registration failed:', err);
            });
    });

    // Paksa reload halaman saat ada service worker baru
    navigator.serviceWorker.addEventListener('controllerchange', () => {
        console.log('New service worker activated. Reloading page...');
        location.reload();
    });
}
</script>

<script>
// JavaScript untuk menangani instalasi PWA
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (event) => {
    // Mencegah prompt instalasi otomatis
    event.preventDefault();
    // Simpan event untuk digunakan nanti
    deferredPrompt = event;
    // Tampilkan tombol instal
    const installButton = document.getElementById('installButton');
    installButton.style.display = 'inline-block';
});

const installButton = document.getElementById('installButton');
installButton.addEventListener('click', async () => {
    if (deferredPrompt) {
        // Tampilkan prompt instalasi
        deferredPrompt.prompt();
        // Tunggu pengguna merespons prompt
        const {
            outcome
        } = await deferredPrompt.userChoice;
        // Log hasil instalasi
        console.log(`User response: ${outcome}`);
        // Sembunyikan tombol instal setelah prompt ditampilkan
        installButton.style.display = 'none';
        // Hapus event setelah digunakan
        deferredPrompt = null;
    }
});

window.addEventListener('appinstalled', () => {
    console.log('PWA installed successfully!');
    // Sembunyikan tombol instal jika aplikasi sudah diinstal
    installButton.style.display = 'none';
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const spinner = document.getElementById('loading-spinner')
    spinner.style.display = 'none' // Sembunyikan spinner saat halaman selesai dimuat
})

window.addEventListener('pageshow', function(event) {
    // Jika halaman dimuat dari cache (saat tombol "Back" ditekan)
    if (event.persisted) {
        const spinner = document.getElementById('loading-spinner')
        spinner.style.display = 'none' // Sembunyikan spinner
    }
})

window.addEventListener('beforeunload', function() {
    const spinner = document.getElementById('loading-spinner')
    spinner.style.display = 'flex' // Tampilkan spinner saat meninggalkan halaman
})
</script>



</body>

</html>
