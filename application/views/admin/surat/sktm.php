<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Miskin</title>
</head>
<body>

<div style="text-align:center;">
    <img src="<?= base_url('assets/kop/kop.jpg'); ?>" width="50" style="margin-bottom: 2px; line-height: -0.90;">
    <h1 style="margin: 0; line-height: 0.60;">KEPALA DESA <?= $item->nama_desa; ?></h1>
    <h4 style="margin: 0; line-height: -0.70;">KECAMATAN <?= $item->nama_kecamatan; ?> KABUPATEN <?= $item->nama_kabupaten; ?></h4>
    <h4 style="margin: 0; text-decoration: underline; line-height: 1.2;">SURAT KETERANGAN MISKIN</h4>
    <h5 style="line-height: -0.5;">Nomor: 410 / ___________ / Kesos / 2024</h5>
</div>

<p>Yang bertandatangan di bawah ini:</p>
<table cellpadding="5">
    <tr><td width="150">a. Nama</td><td>: <b><?= $item->nama_kades; ?></b></td></tr>
    <tr><td>b. Jabatan</td><td>: Kepala Desa</td></tr>
</table>

<p>Dengan ini menerangkan bahwa :</p>
<table cellpadding="3">
    <tr>
        <td width="150">Nama</td>
        <td width="10">:</td>
        <td><b><?= strtoupper($item->nama_lengkap); ?></b></td>
    </tr>
    <tr>
        <td width="150">NIK</td>
        <td width="10">:</td>
        <td><?= $item->nik; ?></td>
    </tr>
    <tr>
        <td width="150">Tempat, Tanggal Lahir</td>
        <td width="10">:</td>
        <td><?= ucwords(strtolower($item->kota)) . ', ' . date('d-m-Y', strtotime($item->tgl_lahir)); ?></td>
    </tr>
    <tr>
        <td width="150">Pekerjaan</td>
        <td width="10">:</td>
        <td><?= $item->pekerjaan; ?></td>
    </tr>
    <tr>
        <td width="150">Alamat</td>
        <td width="10">:</td>
        <td>Kp. <?= ucwords(strtolower($item->alamat)); ?> RT <?= $item->rt; ?> RW <?= $item->rw; ?> Desa <?= ucwords(strtolower($item->desa)); ?> Kec. <?= ucwords(strtolower($item->kecamatan)); ?> Kab. <?= ucwords(strtolower($item->kota)); ?> <?= ucwords(strtolower($item->nama_propinsi)); ?></td>
    </tr>
</table>

<p>Nama tersebut di atas adalah warga desa <?= ucwords(strtolower($item->desa)); ?>. Sepanjang sepengetahuan kami, yang bersangkutan termasuk kategori Miskin (Pra Sejahtera).</p>
<p>Surat Keterangan ini dibuat untuk <b><?= $item->keterangan; ?></b>.</p>

<p>Demikian Surat Keterangan ini dibuat untuk dipergunakan seperlunya.</p>

<br><br>
<div style="text-align:center; line-height:1.5;">
<p><?= ucwords(strtolower($profil_desa->nama_desa)); ?>, <?= date('d', strtotime(date('Y-m-d'))) . ' ' . bulan_indo(date('m', strtotime(date('Y-m-d')))) . ' ' . date('Y'); ?></p>

    <!-- <p style="text-align:center; line-height:-1.8;">Kepala Desa Sukarame</p> -->

    <img src="<?= base_url('assets/ttd/ttd1.jpeg'); ?>" style="margin-top: 5px; line-height:6;" width="200">

    <!-- <p style="font-weight: bold; line-height:0.90;"><?= $item->nama_kades; ?></p> -->
</div>

</body>
</html>
