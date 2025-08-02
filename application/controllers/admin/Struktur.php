<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Struktur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
    }

    // Display the settings form
    public function index() {
        $data['struktur'] = $this->M_all->get_struktur(); // Fetch struktur data
        $data['visi_misi'] = $this->M_all->get_visi_misi(); // Fetch visi misi data
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
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/struktur', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

    // Handle update struktur organisasi
    public function update_struktur() {
        // Handle file upload
        $config['upload_path'] = './assets/foto_struktur/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 10000; // 10MB
        $this->upload->initialize($config);

        if (!empty($_FILES['foto_struktur']['name'])) {
            if ($this->upload->do_upload('foto_struktur')) {
                $upload_data = $this->upload->data();
                $foto_struktur = $upload_data['file_name'];
                // Delete old foto if exists
                if ($this->input->post('old_foto_struktur')) {
                    unlink('./assets/foto_struktur/' . $this->input->post('old_foto_struktur'));
                }
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('admin/struktur');
            }
        } else {
            $foto_struktur = $this->input->post('old_foto_struktur');
        }

        // Prepare data for update
        $data = [
            'foto_struktur' => $foto_struktur,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Update the database
        if ($this->M_all->update_struktur($data)) {
            $this->session->set_flashdata('success', 'Struktur organisasi berhasil diperbarui.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Struktur', 'Menambahkanupdate data struktur');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui struktur organisasi.');
        }

        redirect('admin/struktur');
    }

    // Handle update visi misi
    public function update_visi_misi() {
        // Validate form input
        $this->form_validation->set_rules('visi', 'Visi', 'required');
        $this->form_validation->set_rules('misi', 'Misi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'visi' => $this->input->post('visi'),
                'misi' => $this->input->post('misi'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_visi_misi($data)) {
                $this->session->set_flashdata('success', 'Visi misi berhasil diperbarui.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Visi Misi', 'Menambahkan/update data visi misi');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui visi misi.');
            }

            redirect('admin/struktur');
        }
    }
}