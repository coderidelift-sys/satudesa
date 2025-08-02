<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

    // Display all FAQ data in a table
    public function index() {
        $data['faq'] = $this->M_all->get_all_faq(); // Fetch all FAQ data
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
        $this->load->view('admin/faq', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_faq',
        'column_order' => ['pertanyaan', 'jawaban', null],
        'column_search' => ['pertanyaan', 'jawaban'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;

        $data[] = [
            $no,
            $row->pertanyaan,
            '<textarea class="form-control" rows="3" readonly style="resize: none;font-size: 12px;">' . htmlspecialchars($row->jawaban) . '</textarea>',
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editFaqModal'.$row->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/faq/delete/'.$row->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit FAQ -->
            <div class="modal fade" id="editFaqModal'.$row->id.'" tabindex="-1" aria-labelledby="editFaqModalLabel'.$row->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="'.base_url('admin/faq/edit/'.$row->id).'" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editFaqModalLabel'.$row->id.'">Edit FAQ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Pertanyaan</label>
                                    <input type="text" name="pertanyaan" class="form-control" value="'.htmlspecialchars($row->pertanyaan).'" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jawaban</label>
                                    <textarea name="jawaban" class="form-control" rows="3" required>'.htmlspecialchars($row->jawaban).'</textarea>
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
        "recordsTotal" => $this->M_all->count_all_datatables('tb_faq'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}



    // Add new FAQ
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
        $this->form_validation->set_rules('jawaban', 'Jawaban', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'pertanyaan' => $this->input->post('pertanyaan'),
                'jawaban' => $this->input->post('jawaban'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_faq($data)) {
                $this->session->set_flashdata('success', 'FAQ berhasil ditambahkan.');
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'FAQ', 'Menambahkan data FAQ');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan FAQ.');
            }

            redirect('admin/faq');
        }
    }

    // Edit FAQ
    public function edit($id) {
        // Validate form input
        $this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
        $this->form_validation->set_rules('jawaban', 'Jawaban', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'pertanyaan' => $this->input->post('pertanyaan'),
                'jawaban' => $this->input->post('jawaban'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_faq($id, $data)) {
                $this->session->set_flashdata('success', 'FAQ berhasil diperbarui.');
                                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'FAQ', 'Mengupdate data FAQ');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui FAQ.');
            }

            redirect('admin/faq');
        }
    }

    // Delete FAQ
    public function delete($id) {
        // Delete the data
        if ($this->M_all->delete_faq($id)) {
            $this->session->set_flashdata('success', 'FAQ berhasil dihapus.');
                            $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'FAQ', 'Menghapus data FAQ');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus FAQ.');
        }

        redirect('admin/faq');
    }
}