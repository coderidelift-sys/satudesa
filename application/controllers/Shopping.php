<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('M_all'); 
}
  public function index()
  {
    $data['produk_list'] = $this->M_all->get_all_shopping();
    $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
    $this->load->view('shopping',$data);
    $this->load->view('layouts/footer');
  }
}