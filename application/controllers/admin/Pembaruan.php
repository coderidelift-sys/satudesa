<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembaruan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load model
    }

    // Tampilkan semua data pembaruan
    public function index() {
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
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('errors/error_404');
            $this->load->view('admin/layouts/footer');
            return;
        }

        // Ambil semua data pembaruan
        $data['pembaruan'] = $this->M_all->get_all_pembaruan();
        $this->load->view('admin/layouts/header', $data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/pembaruan', $data);
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_pembaruan',
        'column_order' => ['nama_fitur', 'deskripsi', 'created_at', null],
        'column_search' => ['nama_fitur', 'deskripsi', 'created_at'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;
        $data[] = [
            $no,
            htmlspecialchars($row->nama_fitur),
            htmlspecialchars($row->deskripsi),
            date('d/m/Y H:i', strtotime($row->created_at)),
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editPembaruanModal'.$row->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/pembaruan/delete/'.$row->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Pembaruan -->
            <div class="modal fade" id="editPembaruanModal'.$row->id.'" tabindex="-1" aria-labelledby="editPembaruanModalLabel'.$row->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="'.base_url('admin/pembaruan/edit/'.$row->id).'" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Pembaruan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Fitur</label>
                                    <input type="text" name="nama_fitur" class="form-control" value="'.htmlspecialchars($row->nama_fitur).'" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" required>'.htmlspecialchars($row->deskripsi).'</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            '
        ];
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_pembaruan'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}



    // Tambah data pembaruan
    public function add() {
        // Validasi form
        $this->form_validation->set_rules('nama_fitur', 'Nama Fitur', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form tambah
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'nama_fitur' => $this->input->post('nama_fitur'),
                'deskripsi' => $this->input->post('deskripsi'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_pembaruan($data)) {
                $this->session->set_flashdata('success', 'Pembaruan berhasil ditambahkan.');
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pembaruan', 'Menambahkan data pembaruan');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pembaruan.');
            }

            redirect('admin/pembaruan');
        }
    }

    // Edit data pembaruan
    public function edit($id) {
        // Validasi form
        $this->form_validation->set_rules('nama_fitur', 'Nama Fitur', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form edit
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'nama_fitur' => $this->input->post('nama_fitur'),
                'deskripsi' => $this->input->post('deskripsi'),
            ];

            // Update the database
            if ($this->M_all->update_pembaruan($id, $data)) {
                $this->session->set_flashdata('success', 'Pembaruan berhasil diperbarui.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pembaruan', 'Mengupdate data pembaruan');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui pembaruan.');
            }

            redirect('admin/pembaruan');
        }
    }

    // Hapus data pembaruan
    public function delete($id) {
        // Hapus data dari database
        if ($this->M_all->delete_pembaruan($id)) {
            $this->session->set_flashdata('success', 'Pembaruan berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pembaruan', 'Menghapus data pembaruan');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pembaruan.');
        }

        redirect('admin/pembaruan');
    }
}