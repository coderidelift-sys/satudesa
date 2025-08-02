<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); 
    }

    public function index() {
        $data['kegiatan'] = $this->M_all->get_kegiatan();
        $data['aplikasi'] = $this->M_all->get_aplikasi();
		$this->load->view('layouts/header',$data);
        $this->load->view('kegiatan', $data);
        $this->load->view('layouts/footer');
    }
}