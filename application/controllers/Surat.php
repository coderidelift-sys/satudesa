<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('M_all'); 
}
  public function index()
  {
    $data['regulasi'] = $this->M_all->get_all_regulasi(); // Fetch all regulation data
    $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
    $this->load->view('surat');
    $this->load->view('layouts/footer');
  }
}
