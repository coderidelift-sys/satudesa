<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('M_all');
        $this->load->model('Visitor_model');
        $this->Visitor_model->countVisitor();
    }

	public function index()
	{
	    
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		$data['total_laki'] = $this->M_all->get_total_laki();
        $data['total_perempuan'] = $this->M_all->get_total_perempuan();
        $data['total_warga'] = $this->M_all->get_total_warga();
        $data['total_kk'] = $this->M_all->get_total_kk();
		$data['pekerjaan'] = $this->M_all->get_total_pekerjaan();  
		$data['banners'] = $this->M_all->get_all_banners();
		$data['wilayah'] = $this->M_all->get_all_wilayah();
		$data['grafik_usia_warga'] = $this->M_all->get_grafik_usia_warga();
		$data['grafik_data_warga'] = $this->M_all->get_grafik_data_warga();
        // Hitung tabel surat
        $data['grafik_surat'] = $this->M_all->get_surat_count();
        $data['grafik_metode'] = $this->M_all->get_metode_count();
	
		
		$data['total_visitors'] = $this->Visitor_model->getTotalVisitors();
        $data['today_visitors'] = $this->Visitor_model->getTodayVisitors();
		$this->load->view('layouts/header',$data);
		$this->load->view('main',$data);
		$this->load->view('layouts/footer');
	}
}