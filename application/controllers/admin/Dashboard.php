<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('M_all'); 
        $this->load->library('upload'); 
    }

    public function index() {
        // Data umum
        $data['aplikasi'] = $this->M_all->get_aplikasi();
         // Ambil status aplikasi dari database
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
                
        $data['total_laki'] = $this->M_all->get_total_laki();
        $data['total_perempuan'] = $this->M_all->get_total_perempuan();
        $data['total_warga'] = $this->M_all->get_total_warga();
        $data['total_kk'] = $this->M_all->get_total_kk();
        $data['pekerjaan'] = $this->M_all->get_total_pekerjaan();
    
        // Data Pengaduan
        $data['total'] = $this->M_all->total_aduan();
        $data['pending'] = $this->M_all->aduan_pending();
        $data['diproses'] = $this->M_all->aduan_diproses();
        $data['selesai'] = $this->M_all->aduan_selesai();
    
        // Data Pengajuan Surat dari semua tabel
        $data['total_surat'] = $this->M_all->total_pengajuan_surat();
        $data['pending_s'] = $this->M_all->count_surat_by_status('Pending');
        $data['diproses_surat'] = $this->M_all->count_surat_by_status('Diproses');
        $data['selesai_surat'] = $this->M_all->count_surat_by_status('Selesai');
    
        // GRAFIK
        $data['grafik_surat'] = $this->M_all->get_surat_count();
        $data['grafik_metode'] = $this->M_all->get_metode_count();
        $data['grafik_data_warga'] = $this->M_all->get_grafik_data_warga();
        $data['grafik_usia_warga'] = $this->M_all->get_grafik_usia_warga();
    
        // Data untuk grafik potensi
        $potensi_chart_data = $this->M_all->get_potensi_chart_data();
        $data['potensi_chart_data'] = $potensi_chart_data;

        // Hitung tabel surat
        $data['data_surat'] = $this->M_all->getJenisSuratCount();
        $data['grafik_surat'] = $this->M_all->getGrafikSurat();
    
        // Siapkan data untuk chart
        $alamat = [];
        $total_nominal = []; // Total nominal dalam format numerik
        $total_nominal_formatted = []; // Total nominal dalam format Rupiah
    
        foreach ($potensi_chart_data as $row) {
            $alamat[] = $row->alamat; // Ambil nama alamat
            $total_nominal[] = (float)$row->total_nominal; // Total nominal (numerik)
            $total_nominal_formatted[] = 'Rp ' . number_format($row->total_nominal, 0, ',', '.'); // Format Rupiah
        }
    
        // Kirim data ke view
        $data['alamat'] = json_encode($alamat); // Data alamat (JSON)
        $data['total_nominal'] = json_encode($total_nominal); // Data total nominal (numerik, JSON)
        $data['total_nominal_formatted'] = json_encode($total_nominal_formatted); // Data total nominal (format Rupiah, JSON)
    
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('admin/login');
        } else {
            if ($status_aplikasi == 0) {
                $this->load->view('admin/layouts/header', $data);
                $this->load->view('errors/error_404');
                $this->load->view('admin/layouts/footer');
                return;
            }
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('admin/layouts/sidebar');
            $this->load->view('admin/dashboard', $data); // Kirim data ke view dashboard
            $this->load->view('admin/layouts/footer');
        }
    }
    public function get_detail_chart($alamat) {
        // Decode alamat jika diperlukan (CodeIgniter biasanya sudah otomatis decode)
        $alamat = urldecode($alamat);
    
        // Ambil data detail berdasarkan alamat
        $detail_chart_data = $this->M_all->get_detail_potensi_chart_data($alamat);
    
        // Format data untuk grafik detail
        $detail_penghasilan = [];
        $detail_categories = [];
        $detail_objek = [];
        $detail_penghasilan_tahunan = [];
        $detail_satuan = [];
    
        foreach ($detail_chart_data as $row) {
            $detail_penghasilan[] = (float)$row->total_nominal;
            $detail_categories[] = $row->bidang;
            $detail_objek[] = $row->objek;
            $detail_penghasilan_tahunan[] = $row->penghasilan_tahunan;
            $detail_satuan[] = $row->satuan;
        }
    
        // Kirim data dalam format JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'detail_penghasilan' => $detail_penghasilan,
                'detail_categories' => $detail_categories,
                'detail_objek' => $detail_objek,
                'detail_penghasilan_tahunan' => $detail_penghasilan_tahunan,
                'detail_satuan' => $detail_satuan
            ]));
    }

}