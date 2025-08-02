<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('M_all'); 
}
  public function index()
  {
    $data['faq'] = $this->M_all->get_faq();
    $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
    $this->load->view('faq',$data);
    $this->load->view('layouts/footer');
  }
}