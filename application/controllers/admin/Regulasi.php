<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regulasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

    // Tampilkan semua data regulasi
    public function index() {
        $data['regulasi'] = $this->M_all->get_all_regulasi(); // Fetch all regulation data
        $data['aplikasi'] = $this->M_all->get_aplikasi();
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

        $logged_in = $this->session->userdata('logged_in');
        if (!$logged_in) {
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
            $this->load->view('admin/regulasi', $data); 
            $this->load->view('admin/layouts/footer');
        }
    }
    
    public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_regulasi',
        'column_order' => ['judul_regulasi', 'deskripsi', 'persyaratan', null],
        'column_search' => ['judul_regulasi', 'deskripsi', 'persyaratan'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;
        $data[] = [
            $no,
            $row->judul_regulasi,
            $row->deskripsi,
            $row->persyaratan,
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editRegulasiModal'.$row->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/regulasi/delete/'.$row->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Regulasi -->
            <div class="modal fade" id="editRegulasiModal'.$row->id.'" tabindex="-1" aria-labelledby="editRegulasiModalLabel'.$row->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="'.base_url('admin/regulasi/edit/'.$row->id).'" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Regulasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Judul Regulasi</label>
                                    <input type="text" name="judul_regulasi" class="form-control" value="'.htmlspecialchars($row->judul_regulasi).'" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" required>'.htmlspecialchars($row->deskripsi).'</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Persyaratan</label>
                                    <textarea name="persyaratan" class="form-control" rows="2">'.htmlspecialchars($row->persyaratan).'</textarea>
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
        "recordsTotal" => $this->M_all->count_all_datatables('tb_regulasi'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}


    // Tambahkan regulasi baru
    public function add() {
        $this->form_validation->set_rules('judul_regulasi', 'Judul Regulasi', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = [
                'judul_regulasi' => $this->input->post('judul_regulasi'),
                'deskripsi' => $this->input->post('deskripsi'),
                'persyaratan' => $this->input->post('persyaratan'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->M_all->insert_regulasi($data)) {
                $this->session->set_flashdata('success', 'Regulasi berhasil ditambahkan.');
                $this->_log_activity('Menambahkan data regulasi');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan regulasi.');
            }

            redirect('admin/regulasi');
        }
    }

    // Edit regulasi
    public function edit($id) {
        $this->form_validation->set_rules('judul_regulasi', 'Judul Regulasi', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = [
                'judul_regulasi' => $this->input->post('judul_regulasi'),
                'deskripsi' => $this->input->post('deskripsi'),
                'persyaratan' => $this->input->post('persyaratan'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->M_all->update_regulasi($id, $data)) {
                $this->session->set_flashdata('success', 'Regulasi berhasil diperbarui.');
                $this->_log_activity('Mengupdate data regulasi');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui regulasi.');
            }

            redirect('admin/regulasi');
        }
    }

    // Hapus regulasi
    public function delete($id) {
        if ($this->M_all->delete_regulasi($id)) {
            $this->session->set_flashdata('success', 'Regulasi berhasil dihapus.');
            $this->_log_activity('Menghapus data regulasi');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus regulasi.');
        }

        redirect('admin/regulasi');
    }

    // Fungsi log aktivitas
    private function _log_activity($aktivitas) {
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');
        
        if ($user_id && $username) {
            $this->load->model('M_log');
            $this->M_log->log_activity($user_id, $username, 'Regulasi', $aktivitas);
        }
    }
}
?>
