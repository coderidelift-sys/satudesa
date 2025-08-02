<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load model umum
        $this->load->library('upload'); // Load library upload
    }

    public function index() {
        $data['banners'] = $this->M_all->get_all_banners(); // Ambil data banner
        $data['aplikasi'] = $this->M_all->get_aplikasi();
        // Ambil status aplikasi dari database
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

        $logged_in = $this->session->userdata('logged_in');
        if (!$logged_in) {
            redirect('admin/login'); 
        }

        $this->load->view('admin/layouts/header', $data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/banner', $data);
        $this->load->view('admin/layouts/footer');
    }
    
    public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_banner',
        'column_order' => ['gambar_banner', 'created_at'],
        'column_search' => ['gambar_banner'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $item) {
        $no++;

        // Sesuaikan path ke assets/banner/
        $imageUrl = base_url('assets/banner/' . $item->gambar_banner);

        $data[] = [
            $no,
            '<img src="' . $imageUrl . '" alt="Banner" class="banner-img shadow" onclick="previewImage(\'' . $imageUrl . '\')">',
            '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="konfirmasiDelete(\'' . base_url('admin/banner/delete/' . $item->id) . '\')">
                <i class="bi bi-trash"></i>
            </a>'
        ];
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_banner'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}



    public function add() {
        $config['upload_path'] = './assets/banner/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 10000;
        
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('gambar_banner')) {
            $upload_data = $this->upload->data();
            $data = [
                'gambar_banner' => $upload_data['file_name'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->M_all->insert_banner($data);
            $this->session->set_flashdata('success', 'Banner berhasil diupload.');
              // Ambil user_id dan username sebelum sesi dihancurkan
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Banner', 'Mengupdate data banner');
                }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        }
        
        redirect('admin/banner');
    }
    

    public function delete($id) {
        $banner = $this->M_all->get_banner_by_id($id);
        
        if ($banner && file_exists('./assets/banner/' . $banner->gambar_banner)) {
            unlink('./assets/banner/' . $banner->gambar_banner);
        }
        
        $this->M_all->delete_banner($id);
        $this->session->set_flashdata('success', 'Banner berhasil dihapus.');
          $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Banner', 'Mengahapus data banner');
                }
        redirect('admin/banner');
    }
}