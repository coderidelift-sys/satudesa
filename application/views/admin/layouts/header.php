<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= isset($aplikasi->nama_aplikasi) ? $aplikasi->nama_aplikasi : 'Aplikasi Warga'; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="icon" href="<?= base_url('assets_admin/img/favicon.png') ?>">
  <link rel="apple-touch-icon" href="<?= base_url('assets_admin/img/apple-touch-icon.png') ?>">

  <!-- Preconnect CDN -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/bootstrap-icons/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/boxicons/css/boxicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/quill/quill.snow.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/quill/quill.bubble.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/remixicon/remixicon.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets_admin/vendor/simple-datatables/style.css') ?>">

  <!-- Main CSS -->
  <link rel="stylesheet" href="<?= base_url('assets_admin/css/style.css') ?>">

  <!-- DataTables Bootstrap4 -->
  <link rel="stylesheet" href="<?= base_url('assets/luar/dataTables.bootstrap4.min.css') ?>">

  <!-- Select2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
</head>


    <style>
    table th,
    table td {
        /* text-transform: uppercase; */
        font-size: 12px;
        /* Sesuaikan ukuran font */
        font-family: Arial, sans-serif;
        /* Ganti dengan font yang diinginkan */
    }
    </style>

    <!-- LOADING OVERLAY -->
    <style>
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    </style>
    


</head>
<div id="loading-overlay">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="#" class="logo d-flex align-items-center">
                <img src="<?php echo base_url('assets/aplikasi/' . $aplikasi->logo_aplikasi); ?>" alt="">
                <span class="d-none d-lg-block"><?php echo $aplikasi->nama_aplikasi; ?></span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <!-- Sapaan dan Waktu -->
        <div class="ms-auto me-3 text-center d-none d-md-block" id="sapaan-waktu">
            <strong><span id="sapaan">Selamat Datang</span></strong>
            <p class="mb-0" id="waktu-sekarang" style="font-size: 12px;"></p>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

<li class="nav-item dropdown">
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <?php if (!empty($pending_notifications)): ?>
            <span class="badge bg-primary badge-number"><?= count($pending_notifications); ?></span>
        <?php endif; ?>
    </a>

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
            Ada <?= count($pending_notifications); ?> notifikasi
            <br><b>Notifikasi hanya untuk surat yang masih Pending</b>
            <br><b>Segera tindak lanjut pengajuan!</b>
        </li>
        <li><hr class="dropdown-divider"></li>

        <li>
            <div class="notification-scrollable px-3" style="max-height: 400px; overflow-y: auto;">
                <?php foreach ($pending_notifications as $notif): ?>
                    <div class="notification-item py-2">
                        <?php
                            // Tentukan URL tujuan
                            $url = 'admin/surat'; // default
                            switch($notif->kategori) {
                                case 'Surat Domisili': $url = 'admin/domisili'; break;
                                case 'Surat Lahir': $url = 'admin/surlah'; break;
                                case 'Surat Status': $url = 'admin/suratstatus'; break;
                                case 'Surat Sukem': $url = 'admin/sukem'; break;
                                case 'BA Penguburan': $url = 'admin/bape'; break;
                                case 'Pengajuan Surat':
                                    if (strpos(strtolower($notif->jenis_surat), 'usaha') !== false || 
                                        strpos(strtolower($notif->jenis_surat), 'sktm') !== false) {
                                        $url = 'admin/surat';
                                    }
                                    break;
                                case 'Aduan':
                                    $url = 'admin/aduan';
                                    break;
                            }
                        ?>
                        <a href="<?= base_url($url) ?>" class="d-flex">
                            <i class="bi <?= ($notif->kategori == 'Aduan') ? 'bi-exclamation-circle text-warning' : 'bi-file-earmark-text text-danger'; ?> me-2"></i>
                            <div>
                                <h4><?= ($notif->kategori == 'Aduan') ? 'Aduan Baru' : $notif->kategori . ' Pending' ?></h4>
                                <?php if (!empty($notif->jenis_surat)): ?>
                                    <p>Jenis Surat: <?= $notif->jenis_surat ?></p>
                                <?php endif; ?>
                                <?php if (!empty($notif->no_tracking)): ?>
                                    <p>Nomor Aduan: <?= $notif->no_tracking ?></p>
                                <?php endif; ?>
                                <?php if (!empty($notif->no_pengajuan)): ?>
                                    <p>No ID: <?= $notif->no_pengajuan ?></p>
                                <?php endif; ?>
                                <p>Status: <?= $notif->status ?></p>
                                <p><?= !empty($notif->created_at) ? $notif->created_at : $notif->tgl_pengajuan ?></p>
                            </div>
                        </a>
                    </div>
                    <hr class="dropdown-divider">
                <?php endforeach; ?>
            </div>
        </li>
    </ul>
</li>

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span class="d-none d-md-block dropdown-toggle ps-2">
                            <?php echo $this->session->userdata('nama'); ?>
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $this->session->userdata('nama'); ?></h6>
                            <span><?php echo ucfirst($this->session->userdata('level')); ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="<?php echo base_url('admin/login/logout'); ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->