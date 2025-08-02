<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usaha extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); 
    }
	public function index()
	{
		$data['umkm'] = $this->M_all->get_umkm(); 
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
		$this->load->view('usaha',$data);
		$this->load->view('layouts/footer');
	}
}