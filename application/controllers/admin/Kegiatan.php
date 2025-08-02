<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
    }

    // Display all kegiatan data in a table
    public function index() {
        $data['kegiatan'] = $this->M_all->get_all_kegiatan(); // Fetch all kegiatan data
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
        $this->load->view('admin/kegiatan', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_kegiatan',
        'column_order' => ['judul', 'isi', 'lokasi', 'waktu', 'maps', 'lampiran', 'created_at', null],
        'column_search' => ['judul', 'isi', 'lokasi', 'maps'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $kegiatan) {
        $no++;
        $row = [];
        $row[] = $no;
        $row[] = $kegiatan->judul;
        $row[] = $kegiatan->isi;
        $row[] = $kegiatan->lokasi;
        $row[] = date('d-m-Y H:i', strtotime($kegiatan->waktu));
        $row[] = '<a href="' . $kegiatan->maps . '" target="_blank">Lihat Peta</a>';

        $row[] = $kegiatan->lampiran 
            ? '<a href="' . base_url('assets/lampiran_kegiatan/' . $kegiatan->lampiran) . '" target="_blank">Download</a>' 
            : 'Tidak ada lampiran';

        $row[] = '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editKegiatanModal' . $kegiatan->id . '">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1"
                onclick="konfirmasiDelete(\'' . base_url('admin/kegiatan/delete/' . $kegiatan->id) . '\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Kegiatan -->
            <div class="modal fade" id="editKegiatanModal' . $kegiatan->id . '" tabindex="-1" aria-labelledby="editKegiatanModalLabel' . $kegiatan->id . '" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form action="' . base_url('admin/kegiatan/edit/' . $kegiatan->id) . '" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kegiatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="judul" value="' . $kegiatan->judul . '" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" class="form-control" name="lokasi" value="' . $kegiatan->lokasi . '" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu</label>
                                        <input type="datetime-local" class="form-control" name="waktu" value="' . date('Y-m-d\TH:i', strtotime($kegiatan->waktu)) . '" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Maps</label>
                                        <input type="text" class="form-control" name="maps" value="' . $kegiatan->maps . '" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Lampiran (Optional)</label>
                                        <input type="file" class="form-control" name="lampiran">
                                        <input type="hidden" name="old_lampiran" value="' . $kegiatan->lampiran . '">
                                        ' . ($kegiatan->lampiran ? '<p>Lampiran saat ini: <a href="' . base_url('assets/lampiran_kegiatan/' . $kegiatan->lampiran) . '" target="_blank">' . $kegiatan->lampiran . '</a></p>' : '') . '
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Isi</label>
                                        <textarea class="form-control" name="isi" rows="3" required>' . $kegiatan->isi . '</textarea>
                                    </div>
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
        ';

        $data[] = $row;
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_kegiatan'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}


    // Add new kegiatan
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
            $config['upload_path'] = './assets/lampiran_kegiatan/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|csv|xlsx';
            $config['max_size'] = 5000; // 5MB
            $this->upload->initialize($config);

            if (!empty($_FILES['lampiran']['name'])) {
                if ($this->upload->do_upload('lampiran')) {
                    $upload_data = $this->upload->data();
                    $lampiran = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/kegiatan');
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
                'lampiran' => $lampiran,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_kegiatan($data)) {
                $this->session->set_flashdata('success', 'Kegiatan berhasil ditambahkan.');
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kegiatan', 'Menambahkan data kegiatan');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kegiatan.');
            }

            redirect('admin/kegiatan');
        }
    }

    // Edit kegiatan
    public function edit($id) {
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
            $config['upload_path'] = './assets/lampiran_kegiatan/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|csv|xlsx';
            $config['max_size'] = 5000; // 5MB
            $this->upload->initialize($config);

            if (!empty($_FILES['lampiran']['name'])) {
                if ($this->upload->do_upload('lampiran')) {
                    $upload_data = $this->upload->data();
                    $lampiran = $upload_data['file_name'];
                    // Delete old lampiran if exists
                    if ($this->input->post('old_lampiran')) {
                        unlink('./assets/lampiran_kegiatan/' . $this->input->post('old_lampiran'));
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/kegiatan');
                }
            } else {
                $lampiran = $this->input->post('old_lampiran');
            }

            // Prepare data for update
            $data = [
                'judul' => $this->input->post('judul'),
                'isi' => $this->input->post('isi'),
                'lokasi' => $this->input->post('lokasi'),
                'waktu' => $this->input->post('waktu'),
                'maps' => $this->input->post('maps'),
                'lampiran' => $lampiran,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_kegiatan($id, $data)) {
                $this->session->set_flashdata('success', 'Kegiatan berhasil diperbarui.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kegiatan', 'Mengupdate data kegiatan');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kegiatan.');
            }

            redirect('admin/kegiatan');
        }
    }

    // Delete kegiatan
    public function delete($id) {
        // Get the kegiatan data
        $kegiatan = $this->M_all->get_kegiatan_by_id($id);

        // Delete the lampiran if exists
        if ($kegiatan->lampiran && file_exists('./assets/lampiran/' . $kegiatan->lampiran)) {
            unlink('./assets/lampiran/' . $kegiatan->lampiran);
        }

        // Delete the data
        if ($this->M_all->delete_kegiatan($id)) {
            $this->session->set_flashdata('success', 'Kegiatan berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Kegiatan', 'Menghapus data kegiatan');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kegiatan.');
        }

        redirect('admin/kegiatan');
    }
}