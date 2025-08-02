<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Map extends CI_Controller {

        public function __construct() {
            parent::__construct();
            $this->load->model('M_all');
            check_access('super_admin');
        }

        public function index() {
            $data['aplikasi'] = $this->M_all->get_aplikasi();
            // Ambil status aplikasi dari database
            $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
            $data['pending_aduan'] = $this->M_all->get_aduan_pending();
            $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
            $data['wilayah'] = $this->M_all->get_all_wilayah();
            
            if (!$this->session->userdata('logged_in')) {
                redirect('admin/login');
            }
        
            if ($status_aplikasi == 0) {
                $this->load->view('admin/layouts/header', $data);
                $this->load->view('errors/error_404');
                $this->load->view('admin/layouts/footer');
                return;
            }
        
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('admin/layouts/sidebar');
            $this->load->view('admin/map', $data);
            $this->load->view('admin/layouts/footer');
        }
        
        
        public function save_wilayah() {
            $nama_wilayah = $this->input->post('nama_wilayah');
            $koordinat = $this->input->post('koordinat');
            $kategori_wilayah = $this->input->post('kategori_wilayah');
        
            $data = [
                'nama_wilayah' => $nama_wilayah,
                'koordinat' => $koordinat,
                'kategori_wilayah' => $kategori_wilayah
            ];
        
            if ($this->M_all->save_wilayah($data)) {
                echo json_encode(['status' => 'success']);
                 $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Map', 'Menambahkan/update mapping');
                }
            } else {
                echo json_encode(['status' => 'error']);
            }
        }

        public function edit_wilayah($id) {
            $data['wilayah'] = $this->M_all->get_wilayah_by_id($id);
        
            if (!$data['wilayah']) {
                $this->session->set_flashdata('error', 'Wilayah tidak ditemukan.');
                redirect('admin/map');
            }
        
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('admin/layouts/sidebar');
            $this->load->view('admin/edit_wilayah', $data);
            $this->load->view('admin/layouts/footer');
        }


        public function delete_wilayah($id) {
            if ($this->M_all->delete_wilayah($id)) {
                $this->session->set_flashdata('success', 'Wilayah berhasil dihapus.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kegiatan', 'Menghapus data mapping');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus wilayah.');
            }
        
            redirect('admin/map');
        }
        
        

        
        
    }