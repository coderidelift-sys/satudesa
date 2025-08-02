<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('hari_indo')) {
    function hari_indo($tanggal) {
        $hariInggris = date('l', strtotime($tanggal));
        $hariIndonesia = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];
        return $hariIndonesia[$hariInggris] ?? 'Tidak diketahui';
    }
}

if (!function_exists('format_tanggal_indo')) {
    function format_tanggal_indo($tanggal) {
        $hari = hari_indo($tanggal);
        $tgl = date('d', strtotime($tanggal));
        $bulan = bulan_indo(date('m', strtotime($tanggal)));
        $tahun = date('Y', strtotime($tanggal));

        return "$hari, $tgl $bulan $tahun";
    }
}

if (!function_exists('bulan_indo')) {
    function bulan_indo($bulan) {
        $daftar_bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        return $daftar_bulan[$bulan] ?? 'Tidak diketahui';
    }
}


?>