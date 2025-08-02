<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        check_access('super_admin','admin');
    }

    // Display all user data in a table
    public function index() {
        $data['users'] = $this->M_all->get_all_users(); // Fetch all user data
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
        $this->load->view('admin/user', $data); 
        $this->load->view('admin/layouts/footer');
    }
}


public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'users',
        'column_order' => ['id', 'nama', 'jabatan', 'username', 'level', 'status', 'created_at'],
        'column_search' => ['nama', 'jabatan', 'username', 'level', 'status'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $user) {
        $no++;

        $data[] = [
            $no,
            htmlspecialchars($user->nama),
            htmlspecialchars($user->jabatan),
            htmlspecialchars($user->username),
            htmlspecialchars($user->level),
            ucfirst($user->status),
            $user->created_at,
            '<button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editUserModal'.$user->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/user/delete/'.$user->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit User -->
            <div class="modal fade" id="editUserModal'.$user->id.'" tabindex="-1" aria-labelledby="editUserModalLabel'.$user->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="'.base_url('admin/user/edit/'.$user->id).'" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama" value="'.htmlspecialchars($user->nama).'" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" name="jabatan" value="'.htmlspecialchars($user->jabatan).'" required>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" value="'.htmlspecialchars($user->username).'" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="level" class="form-label">Level</label>
                                    <select name="level" class="form-control" required>
                                        <option value="operator" '.($user->level == 'operator' ? 'selected' : '').'>Operator</option>
                                        <option value="Pelayanan" '.($user->level == 'Pelayanan' ? 'selected' : '').'>Pelayanan</option>
                                        <option value="admin" '.($user->level == 'admin' ? 'selected' : '').'>Admin</option>
                                        <option value="super_admin" '.($user->level == 'super_admin' ? 'selected' : '').'>Super Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif" '.($user->status == 'aktif' ? 'selected' : '').'>Aktif</option>
                                        <option value="tidak_aktif" '.($user->status == 'tidak_aktif' ? 'selected' : '').'>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>'
        ];
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('users'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}


    // Add new user
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('level', 'Level', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT), // Hash password
                'level' => $this->input->post('level'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_user($data)) {
                $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
                                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'User', 'Menambahkan data user');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan user.');
                                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'User', 'Gagal menghapus data user');
                }
            }

            redirect('admin/user');
        }
    }

    // Edit user
    public function edit($id) {
        // Validate form input
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('level', 'Level', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
                'username' => $this->input->post('username'),
                'level' => $this->input->post('level'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // If password is provided, hash and update it
            if ($this->input->post('password')) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            // Update the database
            if ($this->M_all->update_user($id, $data)) {
                $this->session->set_flashdata('success', 'User berhasil diperbarui.');
                                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'User', 'Mengupdate data wisata');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui user.');
            }

            redirect('admin/user');
        }
    }

    // Delete user
    public function delete($id) {
        // Delete the data
        if ($this->M_all->delete_user($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
                            $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'User', 'Menghapus data wisata');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user.');
                            $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'User', 'Gagal menghapus data user');
                }
        }

        redirect('admin/user');
    }
}