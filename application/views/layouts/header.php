<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $aplikasi->nama_aplikasi; ?></title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#4CAF50">

    <!-- CSS Library -->
    <link href="<?php echo base_url('assets/font/font-awesome/css/all.min.css'); ?>" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url('')?>assets_admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="<?php echo base_url('')?>assets/assets_js/js/leaflet.draw.css" />
    <style>
    /* ========== BASE STYLES ========== */
    :root {
        --primary-color: #4CAF50;
        --secondary-color: #2196F3;
        --accent-color: #FFC107;
        --text-color: #333;
        --light-gray: #f5f5f5;
        --medium-gray: #e0e0e0;
        --dark-gray: #757575;
    }
    
   body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    color: var(--text-color);
}

    
    a {
        text-decoration: none;
        color: inherit;
    }
    
    .container {
        padding-top: 120px;
        padding-bottom: 80px;
    }
    
    /* ========== HEADER & SEARCH ========== */
.header {
    background-color: #4CAF50;
    padding: 8px 15px;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.header .title-wrapper h3 {
    font-size: 1.3rem;
    margin: 0;
    font-weight: 700;
    color: white;
    line-height: 1.2;
}

.header .title-wrapper .subtitle {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 400;
    margin-top: 0px;
}

.header img {
    width: 36px;
    height: 36px;
    object-fit: contain;
}

/* Main container spacing */
.container {
    padding-top: 70px; /* Reduced to bring content closer to header */
    padding-bottom: 80px;
}

/* Carousel adjustments */
.carousel-container {
    padding: 0 15px;
    margin-bottom: 15px;
}

.carousel {
    margin-top: -30px; /* Removed negative margin */
}

.carousel-banner {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

/* For desktop view */
@media (min-width: 768px) {
    .header {
        padding: 10px 20px;
    }
    
    .header .title-wrapper h3 {
        font-size: 1.4rem;
    }
    
    .header img {
        width: 40px;
        height: 40px;
    }
    
    .carousel-banner {
        height: 180px;
        border-radius: 10px;
    }
    
    .container {
        padding-top: 100px;
    }
}
    
    /* ========== MOBILE MENU ========== */
    .mobile-menu {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        padding: 15px;
        background: white;
        border-radius: 15px;
        margin: 15px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 5px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    
    .menu-item:hover {
        background-color: var(--light-gray);
        transform: translateY(-3px);
    }
    
    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
        color: white;
        font-size: 18px;
    }
    
    .menu-label {
        font-size: 0.7rem;
        text-align: center;
        font-weight: 500;
        color: var(--text-color);
    }
    
    /* ========== CARDS ========== */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid var(--medium-gray);
        font-weight: 600;
        padding: 15px;
    }
    
    /* ========== STATS GRID ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        padding: 10px;
    }
    
    .stat-item {
        text-align: center;
        padding: 10px;
    }
    
    .stat-icon {
        width: 30px;
        height: 30px;
        margin: 0 auto 5px;
    }
    
    .stat-value {
        font-weight: 700;
        font-size: 1rem;
    }
    
    .stat-label {
        font-size: 0.7rem;
        color: var(--dark-gray);
    }
    
   /* ========== FOOTER NAV ========== */
.footer {
    background-color: white;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    height: 80px; /* Tinggi sedikit ditambah */
    position: fixed;
    bottom: 0;
    width: 100%;
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
    padding-bottom: 12px;
    z-index: 1000;
}

/* Wave design */
.footer::before {
    content: '';
    position: absolute;
    top: -15px;
    left: 0;
    right: 0;
    height: 15px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 100'%3E%3Cpath fill='%23ffffff' d='M0,70L48,65C96,60,192,50,288,45C384,40,480,40,576,45C672,50,768,60,864,65C960,70,1056,70,1152,65C1248,60,1344,50,1392,45L1440,40L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z'%3E%3C/path%3E%3C/svg%3E");
    background-size: cover;
    background-repeat: no-repeat;
}

.footer-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 8px 12px;
    border-radius: 50px;
    transition: all 0.3s ease;
    flex: 1;
    max-width: 25%;
}

.footer-icon {
    font-size: 22px;
    color: var(--dark-gray);
    transition: all 0.3s ease;
    margin-bottom: 6px; /* Jarak bawah icon diperbesar */
}

.footer-label {
    font-size: 0.7rem;
    color: var(--dark-gray);
    transition: all 0.3s ease;
    font-weight: 500;
    margin-top: 2px; /* Tambahan jarak atas label */
}

/* Active state styles */
.footer-item.active {
    transform: translateY(-15px);
    padding-bottom: 12px; /* Tambahan padding saat aktif */
}

.footer-item.active .footer-icon {
    color: var(--primary-color);
    font-size: 24px;
    margin-bottom: 8px; /* Jarak lebih besar saat aktif */
}

.footer-item.active .footer-label {
    color: var(--primary-color);
    font-weight: 600;
    margin-top: 4px; /* Jarak lebih besar saat aktif */
}

/* Active item background circle */
.footer-item.active::after {
    content: '';
    position: absolute;
    top: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    z-index: -1;
    box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .footer {
        height: 75px;
        padding-bottom: 10px;
    }
    
    .footer::before {
        top: -12px;
        height: 12px;
    }
    
    .footer-item.active {
        transform: translateY(-12px);
        padding-bottom: 10px;
    }
    
    .footer-icon {
        font-size: 20px;
        margin-bottom: 5px;
    }
    
    .footer-item.active .footer-icon {
        font-size: 22px;
        margin-bottom: 6px;
    }
    
    .footer-label {
        margin-top: 1px;
    }
    
    .footer-item.active .footer-label {
        margin-top: 3px;
    }
    
    .footer-item.active::after {
        width: 45px;
        height: 45px;
    }
}

/* ========== HILANGKAN GARIS BAWAH DEFAULT PADA LINK ========== */
a, a:hover, a:focus, a:active, a:visited {
    text-decoration: none !important;
}

/* ========== HEADER MENU LINK ========== */
/* Jika ada menu di header */
.nav-menu a, 
.nav-menu a:hover, 
.nav-menu a:focus {
    text-decoration: none !important;
}


/* Responsive adjustments */
@media (max-width: 576px) {
    .footer {
        height: 70px;
    }
    
    .footer::before {
        top: -12px;
        height: 12px;
    }
    
    .footer-item.active {
        transform: translateY(-12px);
    }
    
    .footer-icon {
        font-size: 20px;
    }
    
    .footer-item.active .footer-icon {
        font-size: 22px;
    }
    
    .footer-item.active::after {
        width: 45px;
        height: 45px;
    }
}
    
    /* ========== LOADING SPINNER ========== */
    .spinner-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .spinner {
        border: 4px solid rgba(0,0,0,0.1);
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* ========== COLOR CLASSES ========== */
    .bg-blue { background-color: #2196F3; }
    .bg-yellow { background-color: #FFC107; }
    .bg-green { background-color: #4CAF50; }
    .bg-red { background-color: #F44336; }
    .bg-purple { background-color: #9C27B0; }
    .bg-orange { background-color: #FF9800; }
    .bg-teal { background-color: #009688; }
    .bg-indigo { background-color: #3F51B5; }
    .bg-cyan       { background-color: #00BCD4; }
    .bg-pink       { background-color: #E91E63; }
    .bg-lime       { background-color: #CDDC39; }
    .bg-brown      { background-color: #795548; }
    .bg-grey       { background-color: #9E9E9E; }
    .bg-deep-orange { background-color: #FF5722; }
    .bg-light-blue { background-color: #03A9F4; }
    .bg-light-green { background-color: #8BC34A; }
    .bg-amber      { background-color: #FFC107; }
    .bg-deep-purple { background-color: #673AB7; }
    .bg-blue-grey   { background-color: #607D8B; }
    .bg-black       { background-color: #000000; }
    .bg-silver      { background-color: #BDC3C7; }
    .bg-dark        { background-color: #343a40; }

    
    
    /* ========== RESPONSIVE ADJUSTMENTS ========== */
   /* For mobile responsiveness */
@media (max-width: 576px) {
    .container {
        padding-top: 90px;
    }
    
    .search-bar {
        margin-top: 60px;
        margin-bottom: 50px;
    }
    
    .card {
        margin-bottom: 10px;
    }
    
    .mobile-menu {
        margin: 8px 0;
        padding: 10px;
    }
    
    .chart-container {
        height: 180px;
    }
}
    
    /* ========== CAROUSEL ========== */
    .carousel-banner {
        height: 180px;
        object-fit: cover;
        border-radius: 15px;
    }
    
    .carousel-indicators li {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    /* ========== TABLES ========== */
    .table {
        font-size: 0.8rem;
    }
    
    /* ========== UTILITY CLASSES ========== */
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .text-small {
        font-size: 0.8rem;
    }
    </style>
    <style>
/* ========== UNIFIED CARD STYLES ========== */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 10px;
    overflow: hidden;
    background: white;
}

.card-header {
    background-color: #4CAF50;
    color: white;
    padding: 12px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    font-weight: 600;
    font-size: 1rem;
}

.card-body {
    padding: 16px;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 16px;
    color: #333;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    padding: 12px;
}

.stat-item {
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.stat-icon {
    width: 30px;
    height: 30px;
    margin: 0 auto 8px;
}

.stat-value {
    font-weight: 700;
    font-size: 1rem;
    color: #333;
}

.stat-label {
    font-size: 0.75rem;
    color: #666;
}

/* Tables */
.table {
    width: 100%;
    font-size: 0.875rem;
    margin-bottom: 0;
}

.table thead th {
    background-color: #4CAF50;
    color: white;
    border-color: #4CAF50;
}

.table-bordered th, 
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Chart Containers */
.chart-container {
    position: relative;
    height: 250px;
    width: 100%;
}

.chart-scroll-wrapper {
    width: 100%;
    overflow-x: auto;
}

/* Special Cards */
.card.bg-primary {
    background-color: #4CAF50 !important;
    border-color: #4CAF50 !important;
}

.card.bg-primary .stat-value,
.card.bg-primary .stat-label {
    color: white;
}

/* Form Elements in Cards */
.card select.form-select,
.card input.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.875rem;
}

/* Weather Info */
#weather-info {
    padding: 12px;
}

/* Map Container */
#map {
    height: 250px;
    width: 100%;
    border-radius: 8px;
}

/* Tambahkan ini ke bagian CSS Anda */
.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 250px; /* Sesuaikan tinggi sesuai kebutuhan */
}

.chart-container canvas {
    max-width: 100%;
    height: auto !important; /* Gunakan !important untuk mengatasi style inline dari Chart.js */
}


</style>
    
</head>
<body>
    
    <!-- Spinner Loading -->
    <div id="loading-spinner" class="spinner-container">
        <div class="spinner"></div>
    </div>
    
<!-- Header -->
<div class="header" style="background-color: #4CAF50; padding: 6px 12px; position: fixed; top: 0; width: 100%; z-index: 1000; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
    <div class="title-wrapper" style="display: flex; flex-direction: column; line-height: 1.1;">
        <h3 style="font-size: 1.2rem; margin: 0; font-weight: 700; color: white;"><?php echo $aplikasi->nama_aplikasi; ?></h3>
        <span class="subtitle" style="font-size: 0.65rem; color: rgba(255,255,255,0.9); font-weight: 400; margin-top: 1px;"><?php echo $aplikasi->nama_kepanjangan; ?></span>
    </div>
    <img alt="Logo Aplikasi" src="<?php echo base_url('assets/aplikasi/' . $aplikasi->logo_aplikasi); ?>" style="width: 36px; height: 36px; object-fit: contain;">
</div>