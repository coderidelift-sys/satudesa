<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan BA Penguburan</title>
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

        <h3 style="margin: 0; text-decoration: underline;">BERITA ACARA PENGUBURAN</h3>
        <!-- <h5 style="line-height:-0.65;">Nomor: 474.4/___________/Pemdes/2025</h5> -->
    </div>


    <p>Pada hari ini
        <b><?= hari_indo($item->tgl_pengajuan) . ', ' . date('d', strtotime($item->tgl_pengajuan)) . ' ' . bulan_indo(date('m', strtotime($item->tgl_pengajuan))) . ' ' . date('Y', strtotime($item->tgl_pengajuan)); ?>
        </b>dilakukan
        pemakaman jenazah di <b>Tempat Pemakaman Umum (TPU)</b> <?= $item->lokasi_pemakaman; ?> Pukul <?= $item->jam; ?>
        WIB. Atas nama :</p>
    <?php
    // Pisahkan tanda tangan menjadi Jabatan dan Nama
    $tanda_tangan_parts = explode(" - ", $item->tanda_tangan);
    $jabatan = isset($tanda_tangan_parts[0]) ? $tanda_tangan_parts[0] : '';
    $nama_ttd = isset($tanda_tangan_parts[1]) ? $tanda_tangan_parts[1] : '';
    ?>

    <table cellpadding="3">
        <tr>
            <td width="150">Nama Lengkap</td>
            <td width="10">:</td>
            <td><b><?= strtoupper($item->nama); ?></b></td>
        </tr>
        <tr>
            <td width="150">NIK</td>
            <td width="10">:</td>
            <td><?= $item->nik; ?></td>
        </tr>
        <tr>
            <td width="150">Tempat, Tanggal Lahir</td>
            <td width="10">:</td>
            <td><?= ucwords(strtolower($item->kota)) . ', ' . date('d-m-Y', strtotime($item->tgl_lahir)); ?>
            </td>
        </tr>
        <tr>
            <td width="150">Alamat</td>
            <td width="10">:</td>
            <td width="350">Kp. <?= ucwords(strtolower($item->alamat)); ?> RT. <?= ucwords(strtolower($item->rt)); ?>
                RW.
                <?= ucwords(strtolower($item->rw)); ?> RW. <?= ucwords(strtolower($item->rw)); ?> Desa.
                <?= ucwords(strtolower($item->desa)); ?> Kec. <?= ucwords(strtolower($item->kecamatan)); ?> Kab.
                <?= ucwords(strtolower($item->kota)); ?> <?= ucwords(strtolower($item->propinsi)); ?></td>
        </tr>
    </table>

    <p><?= $item->keterangan; ?></p>

    <p>Demikian surat ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>

    <br><br>
    <div style="text-align:center;">
        <p><?= ucwords(strtolower($profil_desa->nama_desa)); ?>,
            <?= date('d', strtotime(date('Y-m-d'))) . ' ' . bulan_indo(date('m', strtotime(date('Y-m-d')))) . ' ' . date('Y'); ?>
        </p>

        <p style="line-height:-0.50;"><?= ucwords(strtolower($jabatan)); ?></p>

        <!-- <img src="<?= base_url('assets/ttd/ttd_kades.jpeg'); ?>" width="200"> -->

        <p style="margin-top: 1px; line-height:5;"><b><?= $nama_ttd; ?></b></p>
    </div>
    <div style="text-align:center;">
        <p style="line-height:-2;">Saksi-saksi</p>
        <?php
    // Pastikan daftar_saksi tidak kosong
    if (!empty($item->daftar_saksi)) {
        // Pisahkan daftar saksi berdasarkan baris baru atau koma
        $saksi_list = preg_split('/\r\n|\r|\n|,/', $item->daftar_saksi);
        
        // Loop untuk menampilkan saksi satu per satu
        foreach ($saksi_list as $saksi) {
            $saksi = trim($saksi); // Hapus spasi berlebih
            if (!empty($saksi)) {
                echo '<h5>' . strtoupper($saksi) . ' ( ................. )</h5>';
            }
        }
    } else {
        echo "<p>Tidak ada saksi terdaftar.</p>";
    }
    ?>
    </div>


</body>

</html>