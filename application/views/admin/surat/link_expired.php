<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! Link Expired</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        margin-top: 10%;
        color: #333;
    }

    .oops-img {
        width: 150px;
        height: auto;
    }

    .pesan-expired {
        color: red;
        font-size: 18px;
    }

    .back-link {
        margin-top: 20px;
        display: inline-block;
        text-decoration: none;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .back-link:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <img src="https://cdn-icons-png.flaticon.com/512/580/580185.png" alt="Oops" class="oops-img">
    <h1>Oops! Link Sudah Expired</h1>
    <p class="pesan-expired"><?php echo $pesan; ?></p>

    <?php 
    // Ambil URL sebelumnya, jika tidak ada arahkan ke halaman utama surat
    $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('surat'); 
    ?>

    <a href="<?php echo $back_url; ?>" class="back-link">Kembali ke Halaman Sebelumnya</a>
</body>

</html>