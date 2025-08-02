<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '../vendor/autoload.php'; // Sesuaikan path jika vendor berada di root

class Surlah extends CI_Controller {
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
        $data['suratlahir'] = $this->M_all->get_all_suratlahir(); // Fetch all bansos data
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/suratlahir', $data); 
        $this->load->view('admin/layouts/footer');
    }
    }

    public function add() {
        // Validasi form input
        $this->form_validation->set_rules('nama_anak', 'Nama Anak', 'required', [
            'required' => 'Nama Anak wajib diisi.'
        ]);
        $this->form_validation->set_rules('nama_ibu', 'Nama Ibu', 'required', [
            'required' => 'Nama Ibu wajib diisi.'
        ]);
        $this->form_validation->set_rules('nama_ayah', 'Nama Ayah', 'required', [
            'required' => 'Nama Ayah wajib diisi.'
        ]);
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required', [
            'required' => 'Tanggal Lahir wajib diisi.'
        ]);
        $this->form_validation->set_rules('lokasi_lahir', 'Lokasi Lahir', 'required', [
            'required' => 'Lokasi Lahir wajib diisi.'
        ]);
        $this->form_validation->set_rules('anak_ke', 'Anak Ke', 'required|numeric', [
            'required' => 'Anak Ke wajib diisi.',
            'numeric' => 'Anak Ke harus berupa angka.'
        ]);
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required', [
            'required' => 'Jenis Kelamin wajib diisi.'
        ]);
        $this->form_validation->set_rules('agama_ibu', 'Agama Ibu', 'required', [
            'required' => 'Agama Ibu wajib diisi.'
        ]);
        $this->form_validation->set_rules('agama_ayah', 'Agama Ayah', 'required', [
            'required' => 'Agama Ayah wajib diisi.'
        ]);
        $this->form_validation->set_rules('alamat', 'Alamat', 'required', [
            'required' => 'Alamat wajib diisi.'
        ]);
        $this->form_validation->set_rules('no_wa', 'No WA', 'required', [
            'required' => 'No HP wajib diisi.'
        ]);
        $this->form_validation->set_rules('status', 'Status', 'required', [
            'required' => 'Status wajib diisi.'
        ]);
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/surlah');
        } else {
            // **Generate Nomor Pengajuan**
            $date = date('dmy'); // Format tanggal ddmmyy
            $random_number = mt_rand(1000000, 9999999); // Angka random 7 digit
            $no_pengajuan = 'PS' . $date . $random_number; // Format: PSddmmyyXXXXXXX
    
            // **Siapkan Data untuk Disimpan**
            $data = [
                'no_pengajuan' => $no_pengajuan,
                'nama_anak' => $this->input->post('nama_anak'),
                'nama_ibu' => $this->input->post('nama_ibu'),
                'umur_ibu' => $this->input->post('umur_ibu'),
                'nama_ayah' => $this->input->post('nama_ayah'),
                'umur_ayah' => $this->input->post('umur_ayah'),
                'tgl_lahir' => $this->input->post('tgl_lahir'),
                'lokasi_lahir' => $this->input->post('lokasi_lahir'),
                'anak_ke' => $this->input->post('anak_ke'),
                'gender' => $this->input->post('gender'),
                'agama_ibu' => $this->input->post('agama_ibu'),
                'agama_ayah' => $this->input->post('agama_ayah'),
                'alamat' => $this->input->post('alamat'),
                'no_wa' => $this->input->post('no_wa'),
                'tgl_pengajuan' => date('Y-m-d H:i:s'),
                'status' => $this->input->post('status'),
                'metode' => 'Offline',
                'tgl_update' => null,
            ];
    
            // **Insert ke Database**
            if ($this->M_all->insert_suratlahir($data)) {
                $this->session->set_flashdata('success', 'Pengajuan surat lahir berhasil ditambahkan. No Pengajuan: ' . $no_pengajuan);
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengajuan surat lahir.');
            }
    
            redirect('admin/surlah');
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
        $status = $this->input->post('status');
        $nama_anak = $this->input->post('nama_anak');
        $nama_ibu = $this->input->post('nama_ibu');
        $nama_ayah = $this->input->post('nama_ayah');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $lokasi_lahir = $this->input->post('lokasi_lahir');
        $anak_ke = $this->input->post('anak_ke');
        $gender = $this->input->post('gender');
        $agama_ibu = $this->input->post('agama_ibu');
        $agama_ayah = $this->input->post('agama_ayah');
        $alamat = $this->input->post('alamat');
        $no_wa = $this->input->post('no_wa');
        $tgl_update = date('Y-m-d H:i:s'); // Ambil tanggal saat ini

    
        // Validasi
        if (!$id || !$status) {
            $this->session->set_flashdata('error', 'Data tidak valid!');
            redirect('admin/surlah');
        }
    
        // Ambil data lama berdasarkan ID
        $data_lama = $this->db->get_where('tb_surat_lahir', ['id' => $id])->row();
    
        if (!$data_lama) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('admin/surlah');
        }
    
        // Siapkan data untuk diupdate
        $data = [
            'nama_anak' => $nama_anak,
            'nama_ibu' => $nama_ibu,
            'nama_ayah' => $nama_ayah,
            'tgl_lahir' => $tgl_lahir,
            'lokasi_lahir' => $lokasi_lahir,
            'anak_ke' => $anak_ke,
            'gender' => $gender,
            'agama_ibu' => $agama_ibu,
            'agama_ayah' => $agama_ayah,
            'alamat' => $alamat,
            'no_wa' => $no_wa,
            'status' => $status,
            'tgl_update' => $tgl_update,
        ];
    
    
        // Panggil model untuk update data surat lahir
        $update = $this->M_all->update_suratlahir($id, $data);
    
        if ($update) {
            $this->session->set_flashdata('success', 'Data surat lahir berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data surat lahir.');
        }
    
        redirect('admin/surlah');
    }

public function get_nama_ortu_options() {
    $this->db->select("nama_lengkap, nik, posisi, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur");
    $this->db->from("anggota_keluarga");
    $this->db->where_in("posisi", ["Istri", "Kepala Rumah Tangga"]); // Ambil hanya posisi Istri & Ayah
    $this->db->order_by("nama_lengkap", "ASC");
    $query = $this->db->get();

    echo json_encode($query->result());
}



    public function get_alamat_by_nama() {
        $nama = $this->input->get('nama'); // Ambil nama dari request
        $this->db->select('alamat, rt, rw, desa, kecamatan, kota, kode_pos, propinsi');
        $this->db->from('data_warga');
        $this->db->where('kepala_keluarga', $nama); // Sesuaikan dengan kolom yang menyimpan nama ayah
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            echo json_encode($query->row());
        } else {
            echo json_encode(['alamat' => '', 'rt' => '', 'rw' => '', 'desa' => '', 'kecamatan' => '', 'kota' => '', 'kode_pos' => '', 'propinsi' => '']);
        }
    }
    
    
// Delete surat lahir
public function delete($id) {
    // Get the surat lahir data
    $suratlahir = $this->M_all->get_suratlahir_by_id($id);

    // Cek apakah data surat lahir ditemukan
    if (!$suratlahir) {
        $this->session->set_flashdata('error', 'Data surat lahir tidak ditemukan.');
        redirect('admin/surlah');
    }

    // Jika ada lampiran, hapus file lampiran
    if ($suratlahir->lampiran && file_exists('./assets/lampiran/' . $suratlahir->lampiran)) {
        unlink('./assets/lampiran/' . $surat_lahir->lampiran);
    }

    // Delete the data
    if ($this->M_all->delete_suratlahir($id)) {
        $this->session->set_flashdata('success', 'Surat lahir berhasil dihapus.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menghapus surat lahir.');
    }

    redirect('admin/surlah');
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

    public function downloadskl($encoded_alias = null) {
        if ($encoded_alias === null) {
            show_404();
        }
    
        $id_alias = trim(base64_decode(urldecode($encoded_alias)));
    
        if (empty($id_alias)) {
            show_404();
        }
    
        // Ambil data dari tb_surat_lahir
        $data['item'] = $this->Model_warga->get_skl_by_alias($id_alias)->row();
    
        if (!$data['item']) {
            show_404();
        }
    
        // Ambil semua data dari tb_profil_desa
        $data['profil_desa'] = $this->Model_warga->get_kepala_desa();
    
        // Cek expired_at (hanya jika ada)
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
    
        // Load View dan Generate PDF
        $html = $this->load->view('admin/surat/skl', $data, true);
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $pdf->Output('surat_keterangan_lahir.pdf', 'I');
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
            redirect('admin/surlah');
        }
    
        // Ambil data berdasarkan id_alias
        $pengajuan = $this->Model_warga->get_skl_by_alias($id_alias)->row();
        
        if (!$pengajuan) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('admin/surlah');
        }
    
        // Set expired_at ke 24 jam ke depan
        $this->db->set('expired_at', date('Y-m-d H:i:s', strtotime('+24 hours')));
        $this->db->set('tgl_update', date('Y-m-d H:i:s')); // ← update tgl_update
        $this->db->where('id_alias', $id_alias);
        $this->db->update('tb_surat_lahir');
    
        // Encode ulang alias_id untuk link PDF
        $encoded_alias = urlencode(base64_encode($pengajuan->id_alias));
        
        // Tentukan link PDF langsung tanpa kondisi jenis surat
        $link_pdf = base_url('admin/surlah/downloadskl/' . $encoded_alias);
    
        // Format pesan WhatsApp
        $pesan = "Hallo, Bapak/Ibu *{$pengajuan->nama_ayah}* | *{$pengajuan->nama_ibu}*\n\n".
                 "No Pengajuan : *{$pengajuan->no_pengajuan}*\n".
                 "Pengajuan *Surat Keterangan Lahir* siap diunduh:\n".
                 "$link_pdf\n\n".
                 "Link berlaku selama 24 jam.\n".
                 "Link akan kadaluarsa pada : *" . date('d-M-Y H:i:s', strtotime('+24 hours')) . "*\n".
                 "Pastikan anda sudah mendownload file surat PDF.\n\n".
                 "Terima kasih,\nStaff Pelayanan - ".
                 "Desa *{$pengajuan->nama_desa}*";
    
        // Kirim pesan ke WhatsApp
        $response = $this->kirim_whatsapp($pengajuan->no_wa, $pesan);
    
        // Beri notifikasi berdasarkan hasil pengiriman
        if ($response) {
            $this->session->set_flashdata('error', 'Gagal mengirimkan pesan!');
        } else {
            $this->session->set_flashdata('success', 'Pesan berhasil dikirim!');
        }
    
        redirect('admin/surlah');
    }
    

    
    
}