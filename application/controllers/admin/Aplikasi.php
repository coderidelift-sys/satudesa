<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aplikasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
        check_access('super_admin');
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
    // Cek jika status aplikasi = 0
    if ($status_aplikasi == 0) {
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('errors/error_404'); 
         $this->load->view('admin/layouts/footer');
        return; 
    }

    // Jika status aplikasi aktif, lanjutkan menampilkan halaman aplikasi
   
    $this->load->view('admin/layouts/header',$data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/aplikasi', $data); 
    $this->load->view('admin/layouts/footer');
}
}
    // Handle form submissions
    public function update() {
        // Validate form input
        $this->form_validation->set_rules('nama_aplikasi', 'Nama Aplikasi', 'required');
        $this->form_validation->set_rules('nama_kepanjangan', 'Nama Kepanjangan', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Handle file upload
            $config['upload_path'] = './assets/aplikasi/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 5000; 
            $this->upload->initialize($config);

            if (!empty($_FILES['logo_aplikasi']['name'])) {
                if ($this->upload->do_upload('logo_aplikasi')) {
                    $upload_data = $this->upload->data();
                    $logo_aplikasi = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/aplikasi');
                }
            } else {
                $logo_aplikasi = $this->input->post('old_logo');
            }

            // Prepare data for update
            $data = [
                'nama_aplikasi' => $this->input->post('nama_aplikasi'),
                'nama_kepanjangan' => $this->input->post('nama_kepanjangan'),
                'logo_aplikasi' => $logo_aplikasi,
            ];

            // Update the database
            if ($this->M_all->update_aplikasi($data)) {
                $this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui.');
                 // Ambil user_id dan username sebelum sesi dihancurkan
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Aplikasi', 'Mengupdate data aplikasi');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui pengaturan.');
            }

            redirect('admin/aplikasi');
        }
    }
}