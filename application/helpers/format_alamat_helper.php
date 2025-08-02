<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('format_alamat')) {
    function format_alamat($alamat) {
        // Ubah format RT/RW
        $alamat = preg_replace('/\bRt\b/i', 'RT', $alamat);
        $alamat = preg_replace('/\brw\b/i', 'RW', $alamat);

        // Tambahkan "Desa" sebelum "Sukarame" jika belum ada
        $alamat = preg_replace('/\bSukarame\b/i', 'Desa Sukarame', $alamat, 1);

        // Tambahkan "Kec." sebelum "Cisolok" jika belum ada
        $alamat = preg_replace('/\bCisolok\b/i', 'Kec. Cisolok', $alamat, 1);

        // Tambahkan "Kab." sebelum "Sukabumi" jika belum ada
        $alamat = preg_replace('/\bSukabumi\b/i', 'Kab. Sukabumi', $alamat, 1);

        // Pindahkan kode pos ke akhir
        $alamat = preg_replace('/, (\d{5}),/', '', $alamat); // Hapus kode pos di tengah
        $alamat = trim($alamat) . ' 43366'; // Tambahkan kode pos di akhir

        return $alamat;
    }
}
?>
