<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notif extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('M_all'); 
}
  public function index()
  {
    $data['pembaruan'] = $this->M_all->get_all_pembaruan();
    $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
    $this->load->view('notif',$data);
    $this->load->view('layouts/footer');
  }
}