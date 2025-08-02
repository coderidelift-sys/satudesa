<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); 
    }

    public function index() {
      $data['profil_desa'] = $this->M_all->get_profil_desa();
      $data['visi_misi'] = $this->M_all->get_visi_misi();
      $data['potensi'] = $this->M_all->get_potensi();   
      $data['aplikasi'] = $this->M_all->get_aplikasi();
      $this->load->view('layouts/header',$data);
      $this->load->view('desa', $data);
      $this->load->view('layouts/footer');
  }
}