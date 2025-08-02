<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umkm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

    // Display all UMKM data in a table
    public function index() {
        $data['umkm'] = $this->M_all->get_all_umkm(); // Fetch all UMKM data
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
        $this->load->view('admin/umkm', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_umkm',
        'column_order' => ['nama_usaha', 'deskripsi', 'telepon', 'lokasi', null],
        'column_search' => ['nama_usaha', 'deskripsi', 'telepon', 'lokasi'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $item) {
        $no++;

        $data[] = [
            $no,
            htmlspecialchars($item->nama_usaha),
            '<textarea class="form-control" rows="3" readonly style="resize: none;font-size: 12px;">' . htmlspecialchars($item->deskripsi) . '</textarea>',
            htmlspecialchars($item->telepon),
            '<a href="' . htmlspecialchars($item->lokasi) . '" target="_blank" class="btn btn-info btn-sm">Lihat Lokasi</a>',
            '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editUmkmModal' . $item->id . '">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\'' . base_url('admin/umkm/delete/' . $item->id) . '\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Edit Modal -->
            <div class="modal fade" id="editUmkmModal' . $item->id . '" tabindex="-1" aria-labelledby="editUmkmModalLabel' . $item->id . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUmkmModalLabel' . $item->id . '">Edit UMKM</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="' . base_url('admin/umkm/edit/' . $item->id) . '" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Nama Usaha</label>
                                    <input type="text" class="form-control" name="nama_usaha" value="' . htmlspecialchars($item->nama_usaha) . '" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="deskripsi" rows="3" required>' . htmlspecialchars($item->deskripsi) . '</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon (Misal: 62856xxx)</label>
                                    <input type="number" class="form-control" name="telepon" value="' . htmlspecialchars($item->telepon) . '" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi 
                                        <a href="https://maps.app.goo.gl/dDvBUF44CnxY2ztp7" target="_blank">Maps</a>
                                        <sup> Buka Maps, lalu share dan copy link-nya ke kolom lokasi.</sup>
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
        "recordsTotal" => $this->M_all->count_all_datatables('tb_umkm'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}


    // Add new UMKM
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('nama_usaha', 'Nama Usaha', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'nama_usaha' => $this->input->post('nama_usaha'),
                'deskripsi' => $this->input->post('deskripsi'),
                'telepon' => $this->input->post('telepon'),
                'lokasi' => $this->input->post('lokasi'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_umkm($data)) {
                $this->session->set_flashdata('success', 'Data UMKM berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data UMKM.');
            }

            redirect('admin/umkm');
        }
    }

    // Edit UMKM
    public function edit($id) {
        // Validate form input
        $this->form_validation->set_rules('nama_usaha', 'Nama Usaha', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'nama_usaha' => $this->input->post('nama_usaha'),
                'deskripsi' => $this->input->post('deskripsi'),
                'telepon' => $this->input->post('telepon'),
                'lokasi' => $this->input->post('lokasi'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_umkm($id, $data)) {
                $this->session->set_flashdata('success', 'Data UMKM berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data UMKM.');
            }

            redirect('admin/umkm');
        }
    }

    // Delete UMKM
    public function delete($id) {
        // Delete the data
        if ($this->M_all->delete_umkm($id)) {
            $this->session->set_flashdata('success', 'Data UMKM berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data UMKM.');
        }

        redirect('admin/umkm');
    }
}