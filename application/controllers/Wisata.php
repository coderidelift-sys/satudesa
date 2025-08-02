<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wisata extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); 
    }
	public function index()
	{  
		$data['wisata'] = $this->M_all->get_wisata(); 
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
		$this->load->view('wisata', $data);
		$this->load->view('layouts/footer');
	}
}