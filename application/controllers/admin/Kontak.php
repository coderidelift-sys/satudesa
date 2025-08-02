<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

    // Display all staff contact data in a table
    public function index() {
        $data['kontak_staff'] = $this->M_all->get_all_kontak_staff(); // Fetch all staff contact data
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
        $this->load->view('admin/kontak', $data); 
        $this->load->view('admin/layouts/footer');
    }
}


public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_kontak_staff',
        'column_order' => ['nama_staff', 'jabatan', 'telepon', null],
        'column_search' => ['nama_staff', 'jabatan', 'telepon'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;
        $data[] = [
            $no,
            $row->nama_staff,
            $row->jabatan,
            $row->telepon,
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editKontakModal'.$row->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/kontak/delete/'.$row->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Kontak -->
            <div class="modal fade" id="editKontakModal'.$row->id.'" tabindex="-1" aria-labelledby="editKontakModalLabel'.$row->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="'.base_url('admin/kontak/edit/'.$row->id).'" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editKontakModalLabel'.$row->id.'">Edit Kontak Staff</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Staff</label>
                                    <input type="text" name="nama_staff" class="form-control" value="'.htmlspecialchars($row->nama_staff).'" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" value="'.htmlspecialchars($row->jabatan).'" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telepon (628xx)</label>
                                    <input type="number" name="telepon" class="form-control" value="'.htmlspecialchars($row->telepon).'" required>
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
        "recordsTotal" => $this->M_all->count_all_datatables('tb_kontak_staff'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}



    // Add new staff contact
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('nama_staff', 'Nama Staff', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'nama_staff' => $this->input->post('nama_staff'),
                'jabatan' => $this->input->post('jabatan'),
                'telepon' => $this->input->post('telepon'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_kontak_staff($data)) {
                $this->session->set_flashdata('success', 'Kontak staff berhasil ditambahkan.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kontak', 'Menambahkan data kontak');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kontak staff.');
            }

            redirect('admin/kontak');
        }
    }

    // Edit staff contact
    public function edit($id) {
        // Validate form input
        $this->form_validation->set_rules('nama_staff', 'Nama Staff', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'nama_staff' => $this->input->post('nama_staff'),
                'jabatan' => $this->input->post('jabatan'),
                'telepon' => $this->input->post('telepon'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_kontak_staff($id, $data)) {
                $this->session->set_flashdata('success', 'Kontak staff berhasil diperbarui.');
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kontak', 'Mengupdate data kontak');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kontak staff.');
            }

            redirect('admin/kontak');
        }
    }

    // Delete staff contact
    public function delete($id) {
        // Delete the data
        if ($this->M_all->delete_kontak_staff($id)) {
            $this->session->set_flashdata('success', 'Kontak staff berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kontak', 'Menghapus data kontak');
                }
            
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kontak staff.');
        }

        redirect('admin/kontak');
    }
}