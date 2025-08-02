<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
    }

    // Display all pengumuman data in a table
    public function index() {
        $data['pengumuman'] = $this->M_all->get_all_pengumuman(); // Fetch all pengumuman data
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
        $this->load->view('admin/pengumuman', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_pengumuman',
        'column_order' => ['judul', 'isi', 'lokasi', 'waktu', 'maps', 'link', 'lampiran', null],
        'column_search' => ['judul', 'isi', 'lokasi'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;
        $lampiran = $row->lampiran 
            ? '<a href="' . base_url('assets/lampiran_pengumuman/' . $row->lampiran) . '" target="_blank">Download</a>' 
            : 'Tidak ada lampiran';

        $data[] = [
            $no,
            $row->judul,
            $row->isi,
            $row->lokasi,
            $row->waktu,
            '<a href="' . $row->maps . '" target="_blank">Lihat Peta</a>',
            '<a href="' . $row->link . '" target="_blank">Link</a>',
            $lampiran,
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editModal'.$row->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1"
                onclick="konfirmasiDelete(\''.base_url('admin/pengumuman/delete/'.$row->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit -->
<div class="modal fade" id="editModal'.$row->id.'" tabindex="-1" aria-labelledby="editLabel'.$row->id.'" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="'.base_url('admin/pengumuman/update').'" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLabel'.$row->id.'">Edit Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="'.$row->id.'">
                    <input type="hidden" name="old_lampiran" value="'.$row->lampiran.'">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" class="form-control" value="'.$row->judul.'" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" value="'.$row->lokasi.'" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Isi Pengumuman</label>
                            <textarea name="isi" class="form-control" rows="3" required>'.$row->isi.'</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="datetime-local" name="waktu" class="form-control" value="'.date('Y-m-d\TH:i', strtotime($row->waktu)).'" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maps</label>
                            <input type="text" name="maps" class="form-control" value="'.$row->maps.'" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Link</label>
                            <input type="text" name="link" class="form-control" value="'.$row->link.'" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lampiran Baru (Opsional)</label>
                            <input type="file" name="lampiran" class="form-control">
                            '.($row->lampiran ? '<small class="text-muted">Lampiran lama: '.$row->lampiran.'</small>' : '').'
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
        "recordsTotal" => $this->M_all->count_all_datatables('tb_pengumuman'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];
    echo json_encode($output);
}


    // Add new pengumuman
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('judul', 'Judul', 'required');
        $this->form_validation->set_rules('isi', 'Isi', 'required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('maps', 'Maps', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Handle file upload
            $config['upload_path'] = './assets/lampiran_pengumuman/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|csv|xlsx';
            $config['max_size'] = 5000; // 5MB
            $this->upload->initialize($config);

            if (!empty($_FILES['lampiran']['name'])) {
                if ($this->upload->do_upload('lampiran')) {
                    $upload_data = $this->upload->data();
                    $lampiran = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/pengumuman');
                }
            } else {
                $lampiran = null; // No file uploaded
            }

            // Prepare data for insertion
            $data = [
                'judul' => $this->input->post('judul'),
                'isi' => $this->input->post('isi'),
                'lokasi' => $this->input->post('lokasi'),
                'waktu' => $this->input->post('waktu'),
                'maps' => $this->input->post('maps'),
                 'link' => $this->input->post('link'),
                'lampiran' => $lampiran,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_pengumuman($data)) {
                $this->session->set_flashdata('success', 'Pengumuman berhasil ditambahkan.');
                 $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pengumuman', 'Menambahkan data pengumuman');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengumuman.');
            }

            redirect('admin/pengumuman');
        }
    }

    // Edit pengumuman
public function update()
{
    $id = $this->input->post('id');
    if (!$id) {
        $this->session->set_flashdata('error', 'ID pengumuman tidak ditemukan.');
        redirect('admin/pengumuman');
        return;
    }

    // Validasi input
    $this->form_validation->set_rules('judul', 'Judul', 'required');
    $this->form_validation->set_rules('isi', 'Isi', 'required');
    $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
    $this->form_validation->set_rules('waktu', 'Waktu', 'required');
    $this->form_validation->set_rules('maps', 'Maps', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/pengumuman');
        return;
    }

    // Proses upload lampiran jika ada
    $lampiran = $this->input->post('old_lampiran');
    if (!empty($_FILES['lampiran']['name'])) {
        $config['upload_path'] = './assets/lampiran_pengumuman/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|csv|xlsx';
        $config['max_size'] = 5000;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('lampiran')) {
            $upload_data = $this->upload->data();
            $lampiran = $upload_data['file_name'];

            // Hapus lampiran lama jika ada
            if (!empty($this->input->post('old_lampiran')) && file_exists('./assets/lampiran_pengumuman/' . $this->input->post('old_lampiran'))) {
                unlink('./assets/lampiran_pengumuman/' . $this->input->post('old_lampiran'));
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/pengumuman');
            return;
        }
    }

    // Data yang akan disimpan
    $data = [
        'judul' => $this->input->post('judul'),
        'isi' => $this->input->post('isi'),
        'lokasi' => $this->input->post('lokasi'),
        'waktu' => $this->input->post('waktu'),
        'maps' => $this->input->post('maps'),
        'link' => $this->input->post('link'),
        'lampiran' => $lampiran,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    if ($this->M_all->update_pengumuman($id, $data)) {
        // Simpan log aktivitas
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');

        if ($user_id && $username) {
            $this->load->model('M_log');
            $this->M_log->log_activity($user_id, $username, 'Pengumuman', 'Mengupdate data pengumuman');
        }

        $this->session->set_flashdata('success', 'Pengumuman berhasil diperbarui.');
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui pengumuman.');
    }

    redirect('admin/pengumuman');
}


    // Delete pengumuman
    public function delete($id) {
        // Get the pengumuman data
        $pengumuman = $this->M_all->get_pengumuman_by_id($id);

        // Delete the lampiran if exists
        if ($pengumuman->lampiran && file_exists('./assets/lampiran/' . $pengumuman->lampiran)) {
            unlink('./assets/lampiran/' . $pengumuman->lampiran);
        }

        // Delete the data
        if ($this->M_all->delete_pengumuman($id)) {
            $this->session->set_flashdata('success', 'Pengumuman berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pengumuman', 'Menghapus data pengumuman');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengumuman.');
        }

        redirect('admin/pengumuman');
    }
}