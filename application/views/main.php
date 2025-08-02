
<!-- Main Content -->
<div class="container">
    <!-- Carousel Banner -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <?php if (!empty($banners)) : ?>
        <div class="carousel-inner rounded">
            <?php foreach ($banners as $index => $banner) : ?>
            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                <img src="<?= base_url('assets/banner/' . $banner->gambar_banner) ?>" 
                    class="d-block w-100 carousel-banner" 
                    alt="Banner <?= $index + 1 ?>">
            </div>
            <?php endforeach; ?>
        </div>
        <ol class="carousel-indicators">
            <?php foreach ($banners as $index => $banner) : ?>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $index ?>"
                class="<?= $index == 0 ? 'active' : '' ?>"></li>
            <?php endforeach; ?>
        </ol>
        <?php else : ?>
        <div class="carousel-item active">
            <div class="carousel-banner bg-light d-flex align-items-center justify-content-center">
                <p class="text-muted">Tidak ada banner tersedia</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!-- Mobile Menu Grid -->
    <div class="mobile-menu">
        <a href="<?= base_url('desa') ?>" class="menu-item">
            <div class="menu-icon bg-blue">
                <i class="fas fa-home"></i>
            </div>
            <span class="menu-label">Desa</span>
        </a>
        <a href="<?= base_url('maps') ?>" class="menu-item">
            <div class="menu-icon bg-green">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <span class="menu-label">Maps</span>
        </a>
        <a href="<?= base_url('struktur') ?>" class="menu-item">
            <div class="menu-icon bg-purple">
                <i class="fas fa-sitemap"></i>
            </div>
            <span class="menu-label">Struktur</span>
        </a>
        <a href="<?= base_url('wisata') ?>" class="menu-item">
            <div class="menu-icon bg-orange">
                <i class="fas fa-umbrella-beach"></i>
            </div>
            <span class="menu-label">Wisata</span>
        </a>
        <a href="<?= base_url('usaha') ?>" class="menu-item">
            <div class="menu-icon bg-indigo">
                <i class="fas fa-store"></i>
            </div>
            <span class="menu-label">UMKM</span>
        </a>
        <a href="<?= base_url('surat') ?>" class="menu-item">
            <div class="menu-icon bg-teal">
                <i class="fas fa-envelope"></i>
            </div>
            <span class="menu-label">Surat</span>
        </a>
        <a href="<?= base_url('kegiatan') ?>" class="menu-item">
            <div class="menu-icon bg-black">
                <i class="fas fa-calendar"></i>
            </div>
            <span class="menu-label">Kegiatan</span>
        </a>
        <a href="<?= base_url('pengumuman') ?>" class="menu-item">
            <div class="menu-icon bg-yellow">
                <i class="fas fa-bullhorn"></i>
            </div>
            <span class="menu-label">Pengumuman</span>
        </a>
        <a href="<?= base_url('hukum') ?>" class="menu-item">
            <div class="menu-icon bg-grey">
                <i class="fas fa-book"></i>
            </div>
            <span class="menu-label">Produk Hukum</span>
        </a>
         <a href="<?= base_url('#') ?>" class="menu-item position-relative">
    <div class="menu-icon bg-light-green">
        <i class="fas fa-dollar"></i>
    </div>
    <span class="menu-label">Anggaran</span>

    <!-- Badge SEGERA -->
    <span class="badge badge-danger" style="
        position: absolute;
        top: 5px;
        right: 10px;
        font-size: 10px;
        padding: 3px 6px;
        border-radius: 10px;
        background-color: #dc3545;
        color: white;
    ">
        Segera
    </span>
</a>

        <a href="<?= base_url('aduan') ?>" class="menu-item">
            <div class="menu-icon bg-red">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <span class="menu-label">Pengaduan</span>
        </a>
        
        <a href="<?= base_url('#') ?>" class="menu-item position-relative">
    <div class="menu-icon bg-light-blue">
        <i class="fas fa-comment-dots"></i>
    </div>
    <span class="menu-label">Anggaran</span>

    <!-- Badge SEGERA -->
    <span class="badge badge-danger" style="
        position: absolute;
        top: 5px;
        right: 10px;
        font-size: 10px;
        padding: 3px 6px;
        border-radius: 10px;
        background-color: #dc3545;
        color: white;
    ">
        Segera
    </span>
</a>


    </div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Statistik Warga</h6>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <div class="stat-item">
                <img src="<?= base_url('assets/icon_aplikasi/male.png') ?>" class="stat-icon">
                <div class="stat-value"><?= $total_laki ?></div>
                <div class="stat-label">Pria</div>
            </div>
            <div class="stat-item">
                <img src="<?= base_url('assets/icon_aplikasi/female.png') ?>" class="stat-icon">
                <div class="stat-value"><?= $total_perempuan ?></div>
                <div class="stat-label">Wanita</div>
            </div>
            <div class="stat-item">
                <img src="<?= base_url('assets/icon_aplikasi/warga.png') ?>" class="stat-icon">
                <div class="stat-value"><?= $total_warga ?></div>
                <div class="stat-label">Warga</div>
            </div>
            <div class="stat-item">
                <img src="<?= base_url('assets/icon_aplikasi/keluarga.png') ?>" class="stat-icon">
                <div class="stat-value"><?= $total_kk ?></div>
                <div class="stat-label">Keluarga</div>
            </div>
        </div>
    </div>
</div>

<!-- Warga Dusun - Updated -->
<div class="card chart-card">  <!-- Tambahkan class khusus -->
    <div class="card-header py-2">  <!-- Padding vertikal diperkecil -->
        <h6 class="mb-0">Warga Dusun</h6>
    </div>
    <div class="card-body p-1">  <!-- Padding diperkecil -->
        <div class="chart-responsive-wrapper">  <!-- Wrapper responsif -->
            <canvas id="grafikDataWargaChart"></canvas>
        </div>
    </div>
</div>

<!-- Map - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Peta Desa</h6>
    </div>
    <div class="card-body">
        <div id="map"></div>
    </div>
</div>

<!-- Persentase Warga - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Persentase Warga</h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="wargaChart"></canvas>
        </div>
    </div>
</div>

<!-- Data Kategori Usia - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Data Kategori Usia</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kelompok Usia</th>
                        <th>Jumlah Warga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Balita (1-3 tahun)</td>
                        <td><?= $grafik_usia_warga['balita']; ?></td>
                    </tr>
                    <tr>
                        <td>Anak (4-12 tahun)</td>
                        <td><?= $grafik_usia_warga['anak']; ?></td>
                    </tr>
                    <tr>
                        <td>Remaja (13-17 tahun)</td>
                        <td><?= $grafik_usia_warga['remaja']; ?></td>
                    </tr>
                    <tr>
                        <td>Pemuda (18-35 tahun)</td>
                        <td><?= $grafik_usia_warga['pemuda']; ?></td>
                    </tr>
                    <tr>
                        <td>Dewasa (36-50 tahun)</td>
                        <td><?= $grafik_usia_warga['dewasa']; ?></td>
                    </tr>
                    <tr>
                        <td>Lansia (51-100 tahun)</td>
                        <td><?= $grafik_usia_warga['lansia']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Statistik Profesi Warga - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Statistik Profesi Warga</h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="profesiChart"></canvas>
        </div>
    </div>
</div>

<!-- Statistik Pengajuan Surat - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Metode Layanan Surat</h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="grafikSuratMetode"></canvas>
        </div>
    </div>
</div>

<!-- Jadwal Sholat - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Jadwal Sholat</h6>
    </div>
    <div class="card-body">
        <select id="city" class="form-select mb-3">
            <option value="Sukabumi" selected>Palabuhanratu (Sukabumi)</option>
            <option value="Jakarta">Jakarta</option>
            <option value="Surabaya">Surabaya</option>
            <option value="Bandung">Bandung</option>
        </select>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Subuh</th>
                        <th>Dzuhur</th>
                        <th>Ashar</th>
                        <th>Maghrib</th>
                        <th>Isya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="prayer-times">
                        <td>--:--</td>
                        <td>--:--</td>
                        <td>--:--</td>
                        <td>--:--</td>
                        <td>--:--</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Informasi Cuaca - Updated -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Informasi Cuaca</h6>
    </div>
    <div class="card-body text-center">
        <select id="weather-city" class="form-select mb-3">
            <option value="Sukabumi" selected>Palabuhanratu (Sukabumi)</option>
            <option value="Jakarta">Jakarta</option>
            <option value="Surabaya">Surabaya</option>
            <option value="Bandung">Bandung</option>
        </select>
        <div id="weather-info">
            <h3 class="mb-3"><span id="temperature">--</span>°C</h3>
            <i id="weather-icon" class="fas fa-question-circle fa-3x mb-3"></i>
            <p class="mb-1"><strong><span id="weather-condition">--</span></strong></p>
            <p class="mb-1 text-small"><i class="fas fa-tint me-2"></i>Kelembaban: <span id="humidity">--</span>%</p>
            <p class="mb-0 text-small"><i class="fas fa-wind me-2"></i>Angin: <span id="wind-speed">--</span> m/s</p>
        </div>
    </div>
</div>


<!-- Statistik Pengunjung - Updated -->
<div class="card bg-primary text-white" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: none; overflow: hidden; margin-bottom: 20px;">
    <div class="card-header" style="background-color: rgba(0,0,0,0.1); border-bottom: 1px solid rgba(255,255,255,0.2); padding: 12px 16px;">
        <h6 class="mb-0" style="font-size: 1.1rem; font-weight: 600; color: white; letter-spacing: 0.5px; text-transform: uppercase; margin: 0;">Statistik Pengunjung</h6>
    </div>
    <div class="card-body text-center" style="padding: 20px 15px;">
        <div class="d-flex justify-content-around" style="gap: 15px;">
            <div>
                <div class="stat-value" style="font-size: 1.8rem; font-weight: 700; color: white; line-height: 1.2; margin-bottom: 5px; font-family: 'Roboto', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <?= $total_visitors ?>
                </div>
                <div class="stat-label" style="font-size: 0.85rem; font-weight: 500; color: rgba(255,255,255,0.9); letter-spacing: 0.5px; text-transform: uppercase;">
                    Total
                </div>
            </div>
            <div>
                <div class="stat-value" style="font-size: 1.8rem; font-weight: 700; color: white; line-height: 1.2; margin-bottom: 5px; font-family: 'Roboto', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <?= $today_visitors ?>
                </div>
                <div class="stat-label" style="font-size: 0.85rem; font-weight: 500; color: rgba(255,255,255,0.9); letter-spacing: 0.5px; text-transform: uppercase;">
                    Hari Ini
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>

