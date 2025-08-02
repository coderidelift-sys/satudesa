<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bansos extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); 
    }

    public function index() {
        $data['bansos'] = $this->M_all->get_bansos();
        $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
        $this->load->view('bansos', $data);
        $this->load->view('layouts/footer');
    }
}