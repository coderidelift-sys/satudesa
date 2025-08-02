<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '../vendor/autoload.php'; // Sesuaikan path jika vendor berada di root

class Suratstatus extends CI_Controller {
    private $token = '$2y$10$byA1Efq.uQ0V03tQB5m03.R06Ro0Eln8LazYUXGkIdw2zsG0rua5e'; // Token API

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
        $this->load->model('Model_warga'); // Load the model
        $this->load->helper('indo_helper');
        $this->load->helper('terbilang_helper');
        $this->load->helper('format_alamat_helper');
        $this->load->library('session');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library("pdf/TCPDF");
    }

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
            if ($status_aplikasi == 0) {
                $this->load->view('admin/layouts/header',$data);
                $this->load->view('errors/error_404'); 
                $this->load->view('admin/layouts/footer');
                return; 
            }
    
            $data['status'] = $this->M_all->get_all_suratstatus(); 
            $data['profil_desa'] = $this->Model_warga->get_nama_desa();
    
            // 🔹 Ambil daftar perangkat desa dari tb_perangkat_desa
            $data['perangkat_desa'] = $this->db->get('tb_perangkat_desa')->result();
    
            $this->load->view('admin/layouts/header',$data);
            $this->load->view('admin/layouts/sidebar');
            $this->load->view('admin/suratstatus', $data); 
            $this->load->view('admin/layouts/footer');
        }
    }
    

    public function add() {
        // Validasi form input
        $this->form_validation->set_rules('no_surat', 'Nomor Surat', 'required', [
            'required' => 'Nomor Surat wajib diisi.'
        ]);
        $this->form_validation->set_rules('nik', 'NIK', 'required', [
            'required' => 'NIK wajib diisi.'
        ]);
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', [
            'required' => 'Nama Lengkap wajib diisi.'
        ]);
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required', [
            'required' => 'Tempat Lahir wajib diisi.'
        ]);
        $this->form_validation->set_rules('pekerjaan', 'Pekerjaan', 'required', [
            'required' => 'Pekerjaan wajib diisi.'
        ]);
        $this->form_validation->set_rules('status_kawin', 'Status Perkawinan', 'required', [
            'required' => 'Status Perkawinan wajib diisi.'
        ]);
        $this->form_validation->set_rules('status_sekarang', 'Status sekarang', 'required', [
            'required' => 'Status sekarang wajib diisi.'
        ]);
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required', [
            'required' => 'Tanggal Lahir wajib diisi.'
        ]);
        $this->form_validation->set_rules('alamat_lengkap', 'Alamat Lengkap', 'required', [
            'required' => 'Alamat wajib diisi.'
        ]);
        $this->form_validation->set_rules('no_wa', 'No WA', 'required', [
            'required' => 'Nomor WhatsApp wajib diisi.'
        ]);
        $this->form_validation->set_rules('status', 'Status', 'required', [
            'required' => 'Status wajib diisi.'
        ]);
        $this->form_validation->set_rules('tanda_tangan', 'Tanda Tangan', 'required', [
            'required' => 'Tanda Tangan wajib diisi.'
        ]);
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/suratstatus');
        } else {
            // **Generate Nomor Pengajuan**
            $date = date('dmy'); // Format tanggal ddmmyy
            $random_number = mt_rand(1000000, 9999999); // Angka random 7 digit
            $no_pengajuan = 'PS' . $date . $random_number; // Format: PDddmmyyXXXXXXX
    
            // **Generate ID Alias (20 karakter acak)**
            $id_alias = bin2hex(random_bytes(10));
    
            // **Siapkan Data untuk Disimpan**
            $data = [
                'id_alias' => $id_alias,
                'no_pengajuan' => $no_pengajuan,
                'no_surat' => $this->input->post('no_surat'),
                'nik' => $this->input->post('nik'),
                'nama' => $this->input->post('nama'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'pekerjaan' => $this->input->post('pekerjaan'),
                'status_kawin' => $this->input->post('status_kawin'),
                'status_sekarang' => $this->input->post('status_sekarang'),
                'tgl_lahir' => $this->input->post('tgl_lahir'),
                'alamat_lengkap' => $this->input->post('alamat_lengkap'),
                'no_wa' => $this->input->post('no_wa'),
                'tgl_pengajuan' => date('Y-m-d H:i:s'),
                'status' => $this->input->post('status'),
                'tanda_tangan' => $this->input->post('tanda_tangan'),
                'metode' => 'Offline',
                'tgl_update' => null,
            ];
    
            // **Insert ke Database**
            if ($this->M_all->insert_suratstatus($data)) {
                $this->session->set_flashdata('success', 'Pengajuan Surat status berhasil ditambahkan. No Pengajuan: ' . $no_pengajuan);
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'status', 'Menambahkan data pengajuan surat status');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengajuan Surat status.');
            }
    
            redirect('admin/suratstatus');
        }
    }
    
       

    public function add_surat_dari_user() {
        // Validasi form input
            // Validasi input
    $this->form_validation->set_rules('no_wa', 'No Whatsapp', 'required|min_length[11]|max_length[13]', [
        'required' => 'No Whatsapp wajib diisi.',
        'min_length' => 'No Whatsapp harus 11 - 13 karakter.',
        'max_length' => 'No Whatsapp harus 11 - 13 karakter.'
    ]);
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

    public function update() {
        $id = $this->input->post('id');
        $no_surat = $this->input->post('no_surat');
        $nik = $this->input->post('nik');
        $nama = $this->input->post('nama');
        $tempat_lahir = $this->input->post('tempat_lahir');
        $status_sekarang = $this->input->post('status_sekarang');
        $pekerjaan = $this->input->post('pekerjaan');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $alamat_lengkap = $this->input->post('alamat_lengkap');
        $no_wa = $this->input->post('no_wa');
        $status_kawin = $this->input->post('status_kawin');
        $status = $this->input->post('status');
        $tanda_tangan = $this->input->post('tanda_tangan');
        $tgl_update = date('Y-m-d H:i:s'); // Tanggal update otomatis
    
        // Validasi data wajib
        if (!$id || !$no_surat || !$nik || !$nama || !$status) {
            $this->session->set_flashdata('error', 'Data tidak valid! Pastikan semua data terisi.');
            redirect('admin/suratstatus');
        }
    
        // Cek apakah data ada di database
        $data_lama = $this->db->get_where('tb_surat_status', ['id' => $id])->row();
        if (!$data_lama) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('admin/suratstatus');
        }
    
        // Data yang akan diupdate
        $data = [
            'no_surat' => $no_surat,
            'nik' => $nik,
            'nama' => $nama,
            'tempat_lahir' => $tempat_lahir,
            'status_sekarang' => $status_sekarang,
            'pekerjaan' => $pekerjaan,
            'tgl_lahir' => $tgl_lahir,
            'alamat_lengkap' => $alamat_lengkap,
            'no_wa' => $no_wa,
            'status_kawin' => $status_kawin,
            'status' => $status,
            'tanda_tangan' => $tanda_tangan,
            'tgl_update' => $tgl_update
        ];
    
        // Update data melalui model
        $update = $this->M_all->update_surat_status($id, $data);
    
        if ($update) {
            $this->session->set_flashdata('success', 'Data surat status berhasil diperbarui.');
            $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'status', 'Mengupdate data pengajuan surat status');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data surat status.');
        }
    
        redirect('admin/suratstatus');
    }
    
    
// Delete surat lahir
public function delete($id) {
    // Get the surat lahir data
    $status = $this->M_all->get_status_by_id($id);

    // Cek apakah data surat lahir ditemukan
    if (!$status) {
        $this->session->set_flashdata('error', 'Data surat status tidak ditemukan.');
        redirect('admin/suratstatus');
    }

    // Delete the data
    if ($this->M_all->delete_status($id)) {
        $this->session->set_flashdata('success', 'Surat status berhasil dihapus.');
        $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'status', 'Menghapus data pengajuan surat status');
                }
    } else {
        $this->session->set_flashdata('error', 'Gagal menghapus surat status.');
    }

    redirect('admin/suratstatus');
}


public function downloadss($encoded_alias = null) {
    if ($encoded_alias === null) {
        show_404();
    }

    $id_alias = trim(base64_decode(urldecode($encoded_alias)));

    if (empty($id_alias)) {
        show_404();
    }

    // Ambil data dari tb_surat_status
    $data['item'] = $this->Model_warga->get_ss_by_alias($id_alias)->row();

    if (!$data['item']) {
        show_404();
    }

    // Ambil data profil desa dari tb_profil_desa
    // $data['profil_desa'] = $this->Model_warga->get_nama_desa(); 
    $data['profil_desa'] = $this->Model_warga->get_profil_desa();


    // Cek expired_at (jika ada)
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
    $pdf->SetTitle('Surat Keterangan Status');
    $pdf->SetSubject('Surat Keterangan Status');

    // Atur Logo di Kiri
$pdf->Image(base_url('assets/aplikasi/Kabupaten_Sukabumi.png'), 10, 25, 20); // (file, X, Y, Width)

    // Load View dan Generate PDF
    $html = $this->load->view('admin/surat/sks', $data, true);
    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('surat_keterangan_Status.pdf', 'I');
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
            redirect('admin/suratstatus');
        }
    
        // Ambil data surat status berdasarkan id_alias
        $pengajuan = $this->Model_warga->get_ss_by_alias($id_alias)->row();
        
        if (!$pengajuan) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('admin/suratstatus');
        }
    
        // Ambil nama desa dari tb_profil_desa
        $profil_desa = $this->Model_warga->get_nama_desa();
        $nama_desa = $profil_desa ? $profil_desa->nama_desa : "Desa";
    
        // Set expired_at ke 24 jam ke depan
        $this->db->set('expired_at', date('Y-m-d H:i:s', strtotime('+24 hours')));
        $this->db->set('tgl_update', date('Y-m-d H:i:s')); // ← update tgl_update
        $this->db->where('id_alias', $id_alias);
        $this->db->update('tb_surat_status');
    
        // Encode ulang alias_id untuk link PDF
        $encoded_alias = urlencode(base64_encode($pengajuan->id_alias));
        
        // Tentukan link PDF
        $link_pdf = base_url('admin/suratstatus/downloadss/' . $encoded_alias);
    
        // Format pesan WhatsApp
        $pesan = "Hallo, Bapak/Ibu *{$pengajuan->nama}*\n\n".
                 "No Pengajuan : *{$pengajuan->no_pengajuan}*\n".
                 "Pengajuan *Surat Keterangan status* siap diunduh:\n".
                 "$link_pdf\n\n".
                 "Link berlaku selama 24 jam.\n".
                 "Link akan kadaluarsa pada : *" . date('d-M-Y H:i:s', strtotime('+24 hours')) . "*\n".
                 "Pastikan anda sudah mendownload file surat PDF.\n\n".
                 "Terima kasih,\nStaff Pelayanan - " .
                 "Desa *{$nama_desa}*";
    
        // Kirim pesan ke WhatsApp
        $response = $this->kirim_whatsapp($pengajuan->no_wa, $pesan);
    
        // Beri notifikasi berdasarkan hasil pengiriman
        if ($response) {
            $this->session->set_flashdata('error', 'Gagal mengirimkan pesan!');
        } else {
            $this->session->set_flashdata('success', 'Pesan berhasil dikirim!');
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'status', 'Mengirimkan file PDF pengajuan surat status');
                }
        }
    
        redirect('admin/suratstatus');
    }
    
    
    

    
    
}