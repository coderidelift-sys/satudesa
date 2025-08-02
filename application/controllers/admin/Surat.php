<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '../vendor/autoload.php'; // Sesuaikan path jika vendor berada di root

class Surat extends CI_Controller {
    private $token = '$2y$10$byA1Efq.uQ0V03tQB5m03.R06Ro0Eln8LazYUXGkIdw2zsG0rua5e'; // Token API

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
        $this->load->model('Model_warga'); // Load the model
        $this->load->library('session');
        $this->load->helper('indo_helper');
        $this->load->helper('terbilang_helper');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library("pdf/TCPDF");
    }

    // Display all bansos data in a table
    public function index() {
        $data['aplikasi'] = $this->M_all->get_aplikasi();
    // Ambil status aplikasi dari database
    $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
    $data['pending_aduan'] = $this->M_all->get_aduan_pending();
    $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in != TRUE || empty($logged_in)) {
        redirect('admin/login'); 
    } else {

    // Cek jika status aplikasi = 0
    if ($status_aplikasi == 0) {
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('errors/error_404'); 
         $this->load->view('admin/layouts/footer');
        return; 
    }
        $data['surat'] = $this->M_all->get_all_surat(); // Fetch all bansos data
        $data['profil_desa'] = $this->Model_warga->get_nama_desa();
        $data['anggota_keluarga'] = $this->db->get('anggota_keluarga')->result();
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/surat', $data); 
        $this->load->view('admin/layouts/footer');
    }
    }

    public function add() {
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        // $this->form_validation->set_rules('no_surat', 'Nomor Surat', 'required', [
        //     'required' => 'Nomor Surat wajib diisi.'
        // ]);
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/surat');
        } else {
            $nik = $this->input->post('nik');
            $no_surat = $this->input->post('no_surat');
    
            // Cek apakah NIK tersedia di tabel item_keluarga
            $cek_nik = $this->db->get_where('anggota_keluarga', ['nik' => $nik])->row();
    
            if (!$cek_nik) {
                $this->session->set_flashdata('error', 'NIK anda tidak terdaftar!');
                redirect('admin/surat');
            }

    
            // **Generate Nomor Pengajuan**
            $date = date('dmy'); // Format tanggal ddmmyy
            $random_number = mt_rand(1000000, 9999999); // Angka random 7 digit
            $no_pengajuan = 'PS' . $date . $random_number; // Format: PSddmmyyXXXXXXX
    
            // **Siapkan Data untuk Disimpan**
            $data = [
                'no_pengajuan' => $no_pengajuan,
                'no_surat' => $no_surat, // Input Manual
                'metode' => "Offline",
                'nik' => $nik,
                'jenis_surat' => $this->input->post('jenis_surat'),
                'keterangan' => $this->input->post('keterangan'),
                'no_wa' => $this->input->post('no_wa'),
                'tgl_pengajuan' => date('Y-m-d H:i:s'),
                'status' => 'Pending',
                'tgl_update' => null,
            ];
    
            // **Insert ke Database**
            if ($this->M_all->insert_surat($data)) {
                $this->session->set_flashdata('success', 'Surat berhasil diajukan. No Pengajuan: ' . $no_pengajuan . '. Admin akan mengirimkan surat ke WhatsApp Anda.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengajuan surat.');
            }
    
            redirect('admin/surat');
        }
    }
    
    

    public function add_surat_dari_user() {
        // Validasi form input
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman sebelumnya
            $this->session->set_flashdata('error', validation_errors());
            redirect('surat');
        } else {
            $nik = $this->input->post('nik');
        
            // Cek apakah NIK tersedia di tabel item_keluarga
            $cek_nik = $this->db->get_where('anggota_keluarga', ['nik' => $nik])->row();
        
            if (!$cek_nik) {
                // Jika NIK tidak ditemukan
                $this->session->set_flashdata('error', 'NIK anda tidak terdaftar!');
                redirect('surat');
            }

             // **Cari No Surat Terakhir**
             $last_no_surat = $this->db->select_max('no_surat', 'last_no')->get('tb_pengajuan_surat')->row();
             $no_surat_baru = ($last_no_surat->last_no) ? $last_no_surat->last_no + 1 : 123;
        
            // Menghasilkan no_pengajuan otomatis dengan format SDDMMYYXXXXXXX
            $date = date('dmy'); // Format tanggal ddmmyy
            $random_number = mt_rand(1000000, 9999999); // Angka random 7 digit
            $no_pengajuan = 'PS' . $date . $random_number; // Format: SddmmyyXXXXXXX
        
            // Siapkan data untuk disimpan
            $data = [
                'no_pengajuan' => $no_pengajuan, // Gunakan no_pengajuan yang di-generate
                'no_surat' => $no_surat_baru,
                'metode' => "Online",
                'nik' => $nik,
                'jenis_surat' => $this->input->post('jenis_surat'),
                'keterangan' => $this->input->post('keterangan'),
                'no_wa' => $this->input->post('no_wa'),
                'tgl_pengajuan' => date('Y-m-d H:i:s'),
                'status' => 'Pending', // Default status saat pertama kali ditambahkan
                'tgl_update' => null,
            ];
        
            // Insert ke database
            if ($this->M_all->insert_surat($data)) {
                // Set flashdata dengan nomor pengajuan
                $this->session->set_flashdata('success', 'Surat berhasil diajukan. No Pengajuan Anda: ' . $no_pengajuan. ' Admin akan mengirimkan surat ke no Whatsapp anda.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengajuan surat.');
            }
        
            // Redirect ke halaman admin surat
            redirect('surat');
        }
    }

    public function update_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $no_surat = $this->input->post('no_surat');
        $keterangan = $this->input->post('keterangan');
        $no_wa = $this->input->post('no_wa');
        $tgl_update = date('Y-m-d H:i:s'); // Ambil tanggal saat ini
    
        // Validasi
        if (!$id || !$status || !$keterangan || !$no_surat) {
            $this->session->set_flashdata('error', 'Data tidak valid!');
            redirect('admin/surat');
        }
    
        // Ambil data lama berdasarkan ID
        $data_lama = $this->db->get_where('tb_pengajuan_surat', ['id' => $id])->row();
    
        if (!$data_lama) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('admin/surat');
        }
    
        // Cek jika nomor surat diubah, periksa duplikat
        // if ($no_surat != $data_lama->no_surat) {
        //     $cek_no_surat = $this->db->get_where('tb_pengajuan_surat', ['no_surat' => $no_surat])->row();
        //     if ($cek_no_surat) {
        //         $this->session->set_flashdata('error', 'Nomor Surat tersebut sudah digunakan!');
        //         redirect('admin/surat');
        //     }
        // }
    
        // Panggil model untuk update status surat
        $update = $this->M_all->update_status_surat($id, $no_surat, $status, $keterangan, $no_wa, $tgl_update);
    
        if ($update) {
            $this->session->set_flashdata('success', 'Status dan keterangan berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }
    
        redirect('admin/surat');
    }
    
    // Delete bansos
    public function delete($id) {
        // Get the bansos data
        $bansos = $this->M_all->get_surat_by_id($id);

        // Delete the lampiran if exists
        if ($surat->lampiran && file_exists('./assets/lampiran/' . $surat->lampiran)) {
            unlink('./assets/lampiran/' . $surat->lampiran);
        }

        // Delete the data
        if ($this->M_all->delete_surat($id)) {
            $this->session->set_flashdata('success', 'Surat berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus Surat.');
        }

        redirect('admin/surat');
    }

    public function search_nik() {
        $term = $this->input->get('term', true);
    
        if (empty($term)) {
            echo json_encode([]); // Mengirimkan array kosong jika term kosong
            return;
        }
    
        $result = $this->M_all->search_nik_or_nama($term);
    
        if (empty($result)) {
            echo json_encode([]); // Mengirimkan array kosong jika tidak ada hasil
            return;
        }
    
        echo json_encode($result); // Mengirimkan hasil pencarian dalam format JSON
    }

    public function downloadsktm($encoded_alias = null) {
        if ($encoded_alias === null) {
            show_404();
        }
    
        $id_alias = trim(base64_decode(urldecode($encoded_alias)));
    
        if (empty($id_alias)) {
            show_404();
        }
    
        $data['item'] = $this->Model_warga->tampil_data_by_alias($id_alias)->row();
    
        if (!$data['item']) {
            show_404();
        }
    
        
 // Ambil data profil desa dari tb_profil_desa
 $data['profil_desa'] = $this->Model_warga->get_nama_desa();
    
        // Periksa expired_at HANYA jika expired_at TIDAK KOSONG
        if (!empty($data['item']->expired_at) && strtotime($data['item']->expired_at) < time()) {
            $data['pesan'] = "Link sudah expired. Silakan ajukan kembali surat atau hubungi admin desa.";
            $this->load->view('admin/surat/link_expired', $data);
            return;
        }
    
        $this->load->library('tcpdf');
    
        $pdf = new TCPDF('P', 'mm', array(210, 330), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
    
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Desa Sukarame');
        $pdf->SetTitle('Surat Keterangan Miskin');
        $pdf->SetSubject('Surat Keterangan Miskin');
    
        $html = $this->load->view('admin/surat/sktm', $data, true);
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $pdf->Output('surat_keterangan_miskin.pdf', 'I');
    }
    
    public function downloadsku($encoded_alias = null) {
        if ($encoded_alias === null) {
            show_404();
        }
    
        $id_alias = trim(base64_decode(urldecode($encoded_alias)));
    
        if (empty($id_alias)) {
            show_404();
        }
    
        $data['item'] = $this->Model_warga->tampil_data_by_alias($id_alias)->row();
    
        if (!$data['item']) {
            show_404();
        }

         // Ambil data profil desa dari tb_profil_desa
    $data['profil_desa'] = $this->Model_warga->get_nama_desa();
    
        // Periksa expired_at HANYA jika expired_at TIDAK KOSONG
        if (!empty($data['item']->expired_at) && strtotime($data['item']->expired_at) < time()) {
            $data['pesan'] = "Link sudah expired. Silakan ajukan kembali surat atau hubungi admin desa.";
            $this->load->view('admin/surat/link_expired', $data);
            return;
        }
    
        $this->load->library('tcpdf');
    
        $pdf = new TCPDF('P', 'mm', array(210, 330), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
    
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Desa Sukarame');
        $pdf->SetTitle('Surat Keterangan Usaha');
        $pdf->SetSubject('Surat Keterangan Usaha');
    
        $html = $this->load->view('admin/surat/sku', $data, true);
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $pdf->Output('surat_keterangan_usaha.pdf', 'I');
    }

    public function downloadskl($encoded_alias = null) {
        if ($encoded_alias === null) {
            show_404();
        }
    
        $id_alias = trim(base64_decode(urldecode($encoded_alias)));
    
        if (empty($id_alias)) {
            show_404();
        }
    
        $data['item'] = $this->Model_warga->tampil_data_by_alias($id_alias)->row();
    
        if (!$data['item']) {
            show_404();
        }
    
        // Periksa expired_at HANYA jika expired_at TIDAK KOSONG
        if (!empty($data['item']->expired_at) && strtotime($data['item']->expired_at) < time()) {
            $data['pesan'] = "Link sudah expired. Silakan ajukan kembali surat atau hubungi admin desa.";
            $this->load->view('admin/surat/link_expired', $data);
            return;
        }
    
        $this->load->library('tcpdf');
    
        $pdf = new TCPDF('P', 'mm', array(210, 330), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
    
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Desa Sukarame');
        $pdf->SetTitle('Surat Keterangan Lahir');
        $pdf->SetSubject('Surat Keterangan Lahir');
    
        $html = $this->load->view('admin/surat/skl', $data, true);
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $pdf->Output('surat_keterangan_usaha.pdf', 'I');
    }

    private function kirim_whatsapp($target, $pesan) {
        $url = 'https://notificationwa.com/api/post';
    
        $data = array(
            'isi_pesan' => $pesan,
            'nomor_recieved' => $target
        );
    
        $headers = array(
            "Authorization: $this->token"
        );
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data), // Pastikan data dikirim dalam format yang benar
            CURLOPT_HTTPHEADER => $headers,
        ));
    
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
    
        // Debugging: Log response API WhatsApp
        log_message('debug', "WhatsApp API Response: HTTP Code - $http_code | Response - $response | cURL Error - $error");
    
        // Jika terjadi error saat cURL
        if ($error) {
            log_message('error', "WhatsApp API Error: $error");
            return false;
        }
    
        // Decode JSON response
        $result = json_decode($response, true);
    
        // Pastikan API mengembalikan sukses
        if ($http_code == 200 && isset($result['status']) && strtolower($result['status']) == 'success') {
            return true;
        }
    
        // Jika API tidak sukses, log pesan error
        log_message('error', "WhatsApp API Error: " . json_encode($result));
        return false;
    }
    
    
public function kirim_surat($encoded_alias) {
    // Dekode ID Alias dari URL
    $id_alias = base64_decode(urldecode($encoded_alias));

    // Pastikan ID Alias tidak kosong
    if (empty($id_alias)) {
        $this->session->set_flashdata('error', 'ID tidak valid.');
        redirect('admin/surat');
    }

    // Ambil data berdasarkan id_alias
    $pengajuan = $this->Model_warga->tampil_data_by_alias($id_alias)->row();

    if (!$pengajuan) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('admin/surat');
    }

    // Set expired_at ke 24 jam ke depan dan update tgl_update ke waktu sekarang
    $this->db->set('expired_at', date('Y-m-d H:i:s', strtotime('+24 hours')));
    $this->db->set('tgl_update', date('Y-m-d H:i:s')); // ← update tgl_update
    $this->db->where('id_alias', $id_alias);
    $this->db->update('tb_pengajuan_surat');

    // Encode ulang alias_id untuk link PDF
    $encoded_alias = urlencode(base64_encode($pengajuan->id_alias));

    // Tentukan link berdasarkan jenis surat
    if ($pengajuan->jenis_surat === 'Surat Keterangan Tidak Mampu') {
        $link_pdf = base_url('admin/surat/downloadsktm/' . $encoded_alias);
    } elseif ($pengajuan->jenis_surat === 'Surat Keterangan Usaha') {
        $link_pdf = base_url('admin/surat/downloadsku/' . $encoded_alias);
    } else {
        $this->session->set_flashdata('error', 'Jenis surat tidak dikenal.');
        redirect('admin/surat');
    }

    // Format pesan WhatsApp
    $pesan = "Hallo, *{$pengajuan->nama_lengkap}*\n\n" .
             "No Pengajuan : *{$pengajuan->no_pengajuan}*\n" .
             "Pengajuan *{$pengajuan->jenis_surat}* siap diunduh:\n" .
             "$link_pdf\n\n" .
             "Link berlaku selama 24 jam.\n" .
              "Link akan kadaluarsa pada : *" . date('d-M-Y H:i:s', strtotime('+24 hours')) . "*\n".
             "Pastikan anda sudah mendownload file surat PDF.\n\n" .
             "Terima kasih,\nStaff Pelayanan - " .
             "Desa *{$pengajuan->nama_desa}*";

    // Kirim pesan ke WhatsApp
    $response = $this->kirim_whatsapp($pengajuan->no_wa, $pesan);

    // Beri notifikasi berdasarkan hasil pengiriman
    if ($response) {
        $this->session->set_flashdata('error', 'Gagal mengirimkan pesan');
    } else {
        $this->session->set_flashdata('success', 'Pesan berhasil dikirim!');
    }

    redirect('admin/surat');
}

    
    
    
    
}