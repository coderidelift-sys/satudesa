<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    // Method untuk menampilkan halaman 404
    public function index() {
        // Set header HTTP response code ke 404
        $this->output->set_status_header('404');

        // Load view untuk halaman 404
        $this->load->view('admin/layouts/header');
        $this->load->view('errors/error_404');
        $this->load->view('admin/layouts/footer');
    }
}