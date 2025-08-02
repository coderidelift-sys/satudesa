<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

public function index() {
          // Data umum
$data['aplikasi'] = $this->M_all->get_aplikasi();
          // Ambil status aplikasi dari database
  $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
      $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in != TRUE || empty($logged_in)) {
        redirect('admin/login'); 
    } else {
    // Cek jika status aplikasi = 0
    if ($status_aplikasi == 0) {
         $this->load->view('admin/layouts/header',data);
        $this->load->view('errors/error_404'); 
         $this->load->view('admin/layouts/footer');
        return; 
    }

    $this->load->view('admin/layouts/header',$data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/about', $data); 
    $this->load->view('admin/layouts/footer');
}
}

public function get_realtime_notifications()
{
    $data = [
        'pending_aduan' => $this->M_all->get_aduan_pending(),
        'pending_notifications' => $this->M_all->get_all_pending_notifications()
    ];

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
}

    
}