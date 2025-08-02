<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wisata extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

    // Display all wisata data in a table
    public function index() {
        $data['wisata'] = $this->M_all->get_all_wisata(); // Fetch all wisata data
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
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('errors/error_404'); 
         $this->load->view('admin/layouts/footer');
        return; 
    }
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/wisata', $data); 
        $this->load->view('admin/layouts/footer');
    }
}


public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_wisata',
        'column_order' => ['nama_wisata', 'deskripsi', 'lokasi', null],
        'column_search' => ['nama_wisata', 'deskripsi', 'lokasi'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $item) {
        $no++;

        $data[] = [
            $no,
            htmlspecialchars($item->nama_wisata),
            '<textarea class="form-control" rows="3" readonly style="resize: none;font-size: 12px;">' . htmlspecialchars($item->deskripsi) . '</textarea>',
            '<a href="' . htmlspecialchars($item->lokasi) . '" target="_blank" class="btn btn-info btn-sm">Lihat Lokasi</a>',
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editWisataModal' . $item->id . '">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\'' . base_url('admin/wisata/delete/' . $item->id) . '\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Edit Modal -->
            <div class="modal fade" id="editWisataModal' . $item->id . '" tabindex="-1" aria-labelledby="editWisataModalLabel' . $item->id . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editWisataModalLabel' . $item->id . '">Edit Wisata</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="' . base_url('admin/wisata/edit/' . $item->id) . '" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_wisata" class="form-label">Nama Wisata</label>
                                    <input type="text" class="form-control" name="nama_wisata" value="' . htmlspecialchars($item->nama_wisata) . '" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="deskripsi" rows="3" required>' . htmlspecialchars($item->deskripsi) . '</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi 
                                        <a href="https://maps.app.goo.gl/dDvBUF44CnxY2ztp7" target="_blank">Maps</a>
                                        <sup> Buka Maps, lalu share dan copy link nya ke kolom lokasi.</sup>
                                    </label>
                                    <input type="text" class="form-control" name="lokasi" value="' . htmlspecialchars($item->lokasi) . '" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            '
        ];
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_wisata'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}


    // Add new wisata
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('nama_wisata', 'Nama Wisata', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'nama_wisata' => $this->input->post('nama_wisata'),
                'deskripsi' => $this->input->post('deskripsi'),
                'lokasi' => $this->input->post('lokasi'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_wisata($data)) {
                $this->session->set_flashdata('success', 'Data wisata berhasil ditambahkan.');
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Wisata', 'Menambahkan data wisata');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data wisata.');
            }

            redirect('admin/wisata');
        }
    }

    // Edit wisata
    public function edit($id) {
        // Validate form input
        $this->form_validation->set_rules('nama_wisata', 'Nama Wisata', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'nama_wisata' => $this->input->post('nama_wisata'),
                'deskripsi' => $this->input->post('deskripsi'),
                'lokasi' => $this->input->post('lokasi'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_wisata($id, $data)) {
                $this->session->set_flashdata('success', 'Data wisata berhasil diperbarui.');
                                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Wisata', 'Mengupdate data wisata');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data wisata.');
            }

            redirect('admin/wisata');
        }
    }

    // Delete wisata
    public function delete($id) {
        // Delete the data
        if ($this->M_all->delete_wisata($id)) {
            $this->session->set_flashdata('success', 'Data wisata berhasil dihapus.');
                            $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Wisata', 'Menghapus data wisata');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data wisata.');
        }

        redirect('admin/wisata');
    }
}