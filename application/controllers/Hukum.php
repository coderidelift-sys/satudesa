<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hukum extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); 
    }

   public function index() {
    $data['hukum'] = $this->M_all->get_all_jenis_hukum();

    // Ambil daftar hukum per jenis
    $data['daftar_hukum'] = [];
    foreach ($data['hukum'] as $jenis) {
        $data['daftar_hukum'][$jenis->id] = $this->M_all->get_daftar_hukum_by_jenis($jenis->id);
    }

    $data['aplikasi'] = $this->M_all->get_aplikasi();
    $this->load->view('layouts/header', $data);
    $this->load->view('hukum', $data);
    $this->load->view('layouts/footer');
}

}