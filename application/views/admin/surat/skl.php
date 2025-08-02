<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Lahir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            font-size: 11px;
            margin: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h4 {
            margin: 0;
            text-decoration: underline;
            line-height: 1.2;
            font-size: 13px;
        }

        .header h5 {
            margin: 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table td {
            padding: 3px;
            vertical-align: top;
            font-size: 11px;
        }

        table td:first-child {
            width: 120px;
        }

        .signature {
            text-align: center;
            margin-top: 15px;
            line-height: 1.2;
        }

        .signature img {
            margin-top: 5px;
            width: 150px;
        }

        .signature p {
            margin: 3px 0;
            font-size: 11px;
        }

        p {
            margin: 0;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h4>SURAT KETERANGAN LAHIR</h4>
        <h5 style="margin: 0; line-height: -0.70;">Nomor: 174.3 / ___________ / KS / 2025</h5>
    </div>

    <p>Yang bertandatangan di bawah ini:</p>
    <table>
        <tr>
            <td width="150">Nama Kepala Desa</td>
            <td width="10">:</td>
            <td><b><?= $profil_desa->nama_kades; ?></b></td>
        </tr>
        <tr>
            <td width="150">Jabatan</td>
            <td width="10">:</td>
            <td>Kepala Desa</td>
        </tr>
    </table>

    <p>Dengan ini menerangkan bahwa :</p>
    <table>
        <tr>
            <td width="150">Hari</td>
            <td width="10">:</td>
            <td><?= hari_indo($item->tgl_lahir); ?></td>
        </tr>
        <tr>
            <td width="150">Tanggal Lahir</td>
            <td width="10">:</td>
            <td><?= date('d-m-Y', strtotime($item->tgl_lahir)); ?></td>
        </tr>
        <tr>
            <td width="150">Tempat Lahir</td>
            <td width="10">:</td>
            <td><?= strtoupper($item->lokasi_lahir); ?></td>
        </tr>
    </table>

    <p>Telah Lahir Anak Ke :</p>
    <table>
        <tr>
            <td width="150">Anak Ke</td>
            <td width="10">:</td>
            <td><?= $item->anak_ke; ?> (<?= terbilang($item->anak_ke); ?>)</td>
        </tr>
        <tr>
            <td width="150">Jenis Kelamin</td>
            <td width="10">:</td>
            <td><?= ($item->gender == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
        </tr>
        <tr>
            <td width="150">Nama Anak</td>
            <td width="10">:</td>
            <td><b><?= strtoupper($item->nama_anak); ?></b></td>
        </tr>
    </table>

    <p>Dari Ibu :</p>
    <table>
        <tr>
            <td width="150">Nama</td>
            <td width="10">:</td>
            <td><?= strtoupper($item->nama_ibu); ?></td>
        </tr>
        <tr>
            <td width="150">Umur</td>
            <td width="10">:</td>
            <td><?= $item->umur_ibu; ?> Tahun</td>
        </tr>
        <tr>
            <td width="150">Agama</td>
            <td width="10">:</td>
            <td><?= $item->agama_ibu; ?></td>
        </tr>
    </table>

    <p>Dari Seorang Ayah :</p>
    <table>
        <tr>
            <td width="150">Nama</td>
            <td width="10">:</td>
            <td><?= strtoupper($item->nama_ayah); ?></td>
        </tr>
        <tr>
            <td width="150">Umur</td>
            <td width="10">:</td>
            <td><?= $item->umur_ayah; ?> Tahun</td>
        </tr>
        <tr>
            <td width="150">Agama</td>
            <td width="10">:</td>
            <td><?= $item->agama_ayah; ?></td>
        </tr>
        <tr>
            <td width="150">Alamat</td>
            <td width="10">:</td>
            <td width="300">Kp.<?= ucwords(strtolower(format_alamat($item->alamat))); ?></td>
        </tr>
    </table>

    <p>Surat keterangan ini dibuat atas dasar keterangannya</p>

    <div class="signature">
    <p><?= ucwords(strtolower($profil_desa->nama_desa)); ?>, <?= date('d', strtotime(date('Y-m-d'))) . ' ' . bulan_indo(date('m', strtotime(date('Y-m-d')))) . ' ' . date('Y'); ?></p>

        <img src="<?= base_url('assets/ttd/ttd1.jpeg'); ?>" style="margin-top: 5px; line-height:6;" width="200">
    </div>

</body>

</html>