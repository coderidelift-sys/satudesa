<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aduan extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('M_all'); 
}
  public function index()
  {
    $data['hari_ini'] = $this->M_all->count_aduan_hari_ini();
    $data['minggu_ini'] = $this->M_all->count_aduan_minggu_ini();
    $data['bulan_ini'] = $this->M_all->count_aduan_bulan_ini();
    $data['tahun_ini'] = $this->M_all->count_aduan_tahun_ini();
    $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
    $this->load->view('aduan');
    $this->load->view('layouts/footer');
  }
}