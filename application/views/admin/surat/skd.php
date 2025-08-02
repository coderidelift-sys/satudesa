<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Domisili</title>
</head>

<body>

    <div style="text-align:center;">
        <!-- <img src="<?= base_url('assets/kop/kop.jpg'); ?>" width="50" style="margin-bottom: 2px;"> -->
        <h5 style="margin: 0;">PEMERINTAH KABUPATEN <?= ucwords(strtoupper($profil_desa->nama_kabupaten)); ?></h5>
        <h5 style="margin: 0; line-height:-0.80;">KECAMATAN <?= ucwords(strtoupper($profil_desa->nama_kecamatan)); ?></h5>
        <h3 style="margin: 0; line-height:0.3;">SEKRETARIAT DESA <?= ucwords(strtoupper($profil_desa->nama_desa)); ?></h3>
        <h5 style="margin: 0; line-height:0-20">Alamat: <?= $profil_desa->alamat_desa ?> <?= $profil_desa->kode_pos ?></h5>
        <h5 style="margin: 0; line-height:1">Email : <?= $profil_desa->email; ?></h5>
        <div style="border-bottom: 3px solid black; width: 100%; margin-top: 2px; margin-bottom: 5px;"></div>
        <h5 style="margin: 0; line-height:-3">Website : <?= $profil_desa->situs_desa; ?></h5>

    <h3 style="margin: 0; text-decoration: underline;">SURAT KETERANGAN DOMISILI</h3>
    <h5 style="line-height:-0.65;">Nomor: 474.4/___________/Pemdes/2025</h5>
</div>


    <p>Yang bertandatangan di bawah ini:</p>
    <?php
    // Pisahkan tanda tangan menjadi Jabatan dan Nama
    $tanda_tangan_parts = explode(" - ", $item->tanda_tangan);
    $jabatan = isset($tanda_tangan_parts[0]) ? $tanda_tangan_parts[0] : '';
    $nama_ttd = isset($tanda_tangan_parts[1]) ? $tanda_tangan_parts[1] : '';
    ?>

    <table cellpadding="5">
        <tr>
            <td width="150">Nama</td>
            <td>: <b><?= $nama_ttd; ?></b></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: <?= ucwords(strtolower($jabatan)); ?></td>
        </tr>
    </table>


    <p>Dengan ini menerangkan bahwa:</p>
    <table cellpadding="3">
        <tr>
            <td width="150">Nama</td>
            <td>: <b><?= strtoupper($item->nama); ?></b></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>: <?= $item->nik; ?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= ucwords(strtolower($item->tempat_lahir)) . ', ' . date('d-m-Y', strtotime($item->tgl_lahir)); ?>
            </td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= $item->alamat_lengkap; ?></td>
        </tr>
    </table>

    <p>Nama diatas Adalah benar warga Desa <?= ucwords(strtolower($profil_desa->nama_desa)); ?>, sampai saat ini yang
        bersangkutan masih berdomisili sebagaimana alamat diatas.</p>

    <p>Demikian surat ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>

    <br><br>
    <div style="text-align:center;">
    <p><?= ucwords(strtolower($profil_desa->nama_desa)); ?>, <?= date('d', strtotime(date('Y-m-d'))) . ' ' . bulan_indo(date('m', strtotime(date('Y-m-d')))) . ' ' . date('Y'); ?></p>

        <p style="line-height:-0.50;"><?= ucwords(strtolower($jabatan)); ?></p>

        <!-- <img src="<?= base_url('assets/ttd/ttd_kades.jpeg'); ?>" width="200"> -->

        <p style="margin-top: 1px; line-height:5;"><b><?= $nama_ttd; ?></b></p>
    </div>

</body>

</html>