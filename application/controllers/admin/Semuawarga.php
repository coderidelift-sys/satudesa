<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semuawarga extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all');
    }

    public function index() {
        $data['aplikasi'] = $this->M_all->get_aplikasi();
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
        
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('admin/login'); 
        } else {
            if ($status_aplikasi == 0) {
                $this->load->view('admin/layouts/header',$data);
                $this->load->view('errors/error_404'); 
                $this->load->view('admin/layouts/footer');
                return; 
            }
            
            $this->load->view('admin/layouts/header',$data);
            $this->load->view('admin/layouts/sidebar');
            $this->load->view('admin/semuawarga'); 
            $this->load->view('admin/layouts/footer');
        }
    }
    
    // Server-side processing for datatable
    public function get_warga_json() {
        $this->load->model('M_all');
        
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $search = $this->input->get("search")['value'];
        
        $order = $this->input->get("order");
        $columnIndex = $order[0]['column'];
        $columns = $this->input->get("columns");
        $columnName = $columns[$columnIndex]['data'];
        $columnSortOrder = $order[0]['dir'];
        
        $data = $this->M_all->get_warga_server_side($start, $length, $search, $columnName, $columnSortOrder);
        
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->M_all->count_all_warga(),
            "recordsFiltered" => $this->M_all->count_filtered_warga($search),
            "data" => $data
        );
        
        echo json_encode($output);
    }
}