<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
    }

    // Display the settings form
    public function index() {
        $data['profil'] = $this->M_all->get_profil_desa(); // Fetch profil desa data
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
        $this->load->view('admin/profil', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

    // Handle form submissions
public function update() {
    // Validasi input form
    $this->form_validation->set_rules('tentang', 'Tentang Desa', 'required');
    $this->form_validation->set_rules('alamat_desa', 'Alamat', 'required');
    $this->form_validation->set_rules('telepon', 'Telepon', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('situs_desa', 'Situs', 'required');
    $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');

    // Tambahan validasi untuk kolom baru
    $this->form_validation->set_rules('nama_desa', 'Nama Desa', 'required');
    $this->form_validation->set_rules('nama_kecamatan', 'Kecamatan', 'required');
    $this->form_validation->set_rules('nama_kabupaten', 'Kabupaten', 'required');
    $this->form_validation->set_rules('nama_propinsi', 'Provinsi', 'required');
    $this->form_validation->set_rules('kode_pos', 'Kode Pos', 'required|numeric');
    $this->form_validation->set_rules('kode_desa', 'Kode Desa', 'required');
    $this->form_validation->set_rules('luas_wilayah', 'Luas Wilayah', 'required|numeric');
    $this->form_validation->set_rules('nama_kades', 'Nama Kepala Desa', 'required');
    $this->form_validation->set_rules('nikp', 'NIK Kepala Desa', 'required|numeric');
    $this->form_validation->set_rules('masa_jabatan', 'Masa Jabatan', 'required');

    if ($this->form_validation->run() == FALSE) {
        // Jika validasi gagal, kembali ke halaman profil dengan error
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/profil');
    } else {
        // Handle file upload
        $config['upload_path'] = './assets/profil/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 5000; // 5MB
        $this->upload->initialize($config);

        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $foto = $upload_data['file_name'];
                // Delete old foto if exists
                if ($this->input->post('old_foto')) {
                    unlink('./assets/profil/' . $this->input->post('old_foto'));
                }
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('admin/profil');
            }
        } else {
            $foto = $this->input->post('old_foto');
        }

        // Prepare data untuk update
        $data = [
            'tentang'       => $this->input->post('tentang'),
            'alamat_desa'        => $this->input->post('alamat_desa'),
            'telepon'       => $this->input->post('telepon'),
            'email'         => $this->input->post('email'),
            'situs_desa'         => $this->input->post('situs_desa'),
            'foto'          => $foto,
            'lokasi'        => $this->input->post('lokasi'),
            'nama_desa'     => $this->input->post('nama_desa'),
            'nama_kecamatan'     => $this->input->post('nama_kecamatan'),
            'nama_kabupaten'     => $this->input->post('nama_kabupaten'),
            'nama_propinsi'      => $this->input->post('nama_propinsi'),
            'kode_pos'      => $this->input->post('kode_pos'),
            'kode_desa'     => $this->input->post('kode_desa'),
            'luas_wilayah'  => $this->input->post('luas_wilayah'),
            'nama_kades'    => $this->input->post('nama_kades'),
            'nikp'          => $this->input->post('nikp'),
            'masa_jabatan'  => $this->input->post('masa_jabatan'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        // Update database
        if ($this->M_all->update_profil_desa($data)) {
            $this->session->set_flashdata('success', 'Profil desa berhasil diperbarui.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Profil', 'Menambahkan/Update data profil aplikasi');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profil desa.');
        }

        redirect('admin/profil');
    }
}

}