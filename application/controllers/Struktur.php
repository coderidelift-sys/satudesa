<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Struktur extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load model
    }

    public function index() {
        // Ambil data struktur dan visi misi dari database
        $data['struktur_desa'] = $this->M_all->get_struktur();
        $data['visi_misi'] = $this->M_all->get_visi_misi();
        $data['aplikasi'] = $this->M_all->get_aplikasi(); // Data aplikasi (jika diperlukan)

        // Load view
        $this->load->view('layouts/header', $data);
        $this->load->view('struktur', $data);
        $this->load->view('layouts/footer');
    }
}