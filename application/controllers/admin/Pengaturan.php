<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_access('super_admin');
        $this->load->model('M_all'); // Load the model
    }

    // Display the settings page
    public function index() {
        $data['pengaturan'] = $this->M_all->get_pengaturan(); // Fetch settings data
        $data['aplikasi'] = $this->M_all->get_aplikasi();
        // Ambil status aplikasi dari database
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('admin/login'); 
        } else {
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/pengaturan', $data);
        $this->load->view('admin/layouts/footer');
    }
}
    // Update application status
    public function update_status() {
        // Ambil nilai status dari form
        $new_status = $this->input->post('status') ? 1 : 0;
    
        // Update status aplikasi di database
        $this->db->where('id', 1); // Sesuaikan dengan ID pengaturan yang ingin diubah
        $this->db->update('tb_pengaturan', array('status_aplikasi' => $new_status));
    
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Status aplikasi berhasil diubah.');
             $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pengaturan Aplikasi', 'Mengupdate status aplikasi');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah status aplikasi.');
        }
    
        redirect('admin/pengaturan');
    }

    public function backup_database() {
        // Load library dan helper yang diperlukan
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
    
        // Konfigurasi backup
        $prefs = array(
            'format' => 'sql', // Format backup (sql, txt, gzip, zip)
            'filename' => 'database_backup.sql', // Nama file backup
            'add_drop' => TRUE, // Tambahkan DROP TABLE IF EXISTS
            'add_insert' => TRUE, // Tambahkan INSERT statement
            'newline' => "\n" // Baris baru untuk file SQL
        );
    
        // Generate backup
        $backup = $this->dbutil->backup($prefs);
    
        // Nama file backup
        $backup_name = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
    
        // Simpan file backup ke folder sementara (opsional)
        $backup_path = './backup/' . $backup_name;
        write_file($backup_path, $backup);
    
        // Download file SQL
        force_download($backup_name, $backup);
    
        // Set pesan sukses
        $this->session->set_flashdata('success', 'Backup database berhasil diunduh dalam bentuk SQL.');
         $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pengaturan Aplikasi', 'Download database backup');
                }
        redirect('admin/pengaturan');
    }
}