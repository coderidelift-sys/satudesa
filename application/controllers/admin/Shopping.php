<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load model untuk data produk
    }

    public function index() {
        $data['produk_list'] = $this->M_all->get_all_shopping(); // Fetch all user data
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
        $this->load->view('admin/shopping', $data); 
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_shopping',
        'column_order' => ['nama_produk', 'harga', 'satuan', 'nama_penjual', 'no_wa', 'alamat_penjual', null],
        'column_search' => ['nama_produk', 'harga', 'satuan', 'nama_penjual', 'no_wa', 'alamat_penjual'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;

        $gambar = $row->gambar
            ? '<img src="'.base_url('assets/upload/produk/'.$row->gambar).'" width="40" height="40" class="rounded-circle shadow-sm preview-gambar" style="object-fit: cover; aspect-ratio: 1 / 1; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#previewGambarModal'.$row->id.'">'
            : '<span class="text-muted">-</span>';

        $modal_preview = $row->gambar
            ? '<div class="modal fade" id="previewGambarModal'.$row->id.'" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <img src="'.base_url('assets/upload/produk/'.$row->gambar).'" class="img-fluid rounded shadow">
                            </div>
                        </div>
                    </div>
                </div>'
            : '';

        $data[] = [
            $no,
            htmlspecialchars($row->nama_produk),
            'Rp'.number_format($row->harga, 0, ',', '.'),
            htmlspecialchars($row->satuan),
            htmlspecialchars($row->nama_penjual),
            htmlspecialchars($row->no_wa),
            htmlspecialchars($row->alamat_penjual),
            $gambar.$modal_preview,
            '
            <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editProdukModal'.$row->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/shopping/delete/'.$row->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <div class="modal fade" id="editProdukModal'.$row->id.'" tabindex="-1" aria-labelledby="editProdukModalLabel'.$row->id.'" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="'.base_url('admin/shopping/edit/'.$row->id).'" method="post" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label>Nama Produk</label>
                                        <input type="text" class="form-control" name="nama_produk" value="'.htmlspecialchars($row->nama_produk).'" required>
                                    </div>
                                    <div class="col">
                                        <label>Harga</label>
                                        <input type="number" class="form-control" name="harga" value="'.$row->harga.'" required>
                                    </div>
                                    <div class="col">
                                        <label>Satuan</label>
                                        <input type="text" class="form-control" name="satuan" value="'.htmlspecialchars($row->satuan).'" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label>Nama Penjual</label>
                                        <input type="text" class="form-control" name="nama_penjual" value="'.htmlspecialchars($row->nama_penjual).'" required>
                                    </div>
                                    <div class="col">
                                        <label>No. WhatsApp</label>
                                        <input type="text" class="form-control" name="no_wa" value="'.htmlspecialchars($row->no_wa).'" required>
                                    </div>
                                    <div class="col">
                                        <label>Alamat Penjual</label>
                                        <input type="text" class="form-control" name="alamat_penjual" value="'.htmlspecialchars($row->alamat_penjual).'" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Ganti Gambar</label>
                                    <input type="file" class="form-control" name="gambar">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
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
        "recordsTotal" => $this->M_all->count_all_datatables('tb_shopping'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}



    // Tambah produk baru
    public function add() {
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('nama_penjual', 'Nama Penjual', 'required');
        $this->form_validation->set_rules('no_wa', 'Nomor WA', 'required|numeric');
        $this->form_validation->set_rules('alamat_penjual', 'Alamat Penjual', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index(); // Tampilkan form jika validasi gagal
        } else {
            $gambar = $this->upload_image();
            $data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga' => $this->input->post('harga'),
                'satuan' => $this->input->post('satuan'),
                'nama_penjual' => $this->input->post('nama_penjual'),
                'no_wa' => $this->input->post('no_wa'),
                'alamat_penjual' => $this->input->post('alamat_penjual'),
                'gambar' => $gambar,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->M_all->insert_shopping($data)) {
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk.');
            }

            redirect('admin/shopping');
        }
    }

    // Edit produk
    public function edit($id) {
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('nama_penjual', 'Nama Penjual', 'required');
        $this->form_validation->set_rules('no_wa', 'Nomor WA', 'required|numeric');
        $this->form_validation->set_rules('alamat_penjual', 'Alamat Penjual', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index(); // Tampilkan form jika validasi gagal
        } else {
            $data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga' => $this->input->post('harga'),
                'satuan' => $this->input->post('satuan'),
                'nama_penjual' => $this->input->post('nama_penjual'),
                'no_wa' => $this->input->post('no_wa'),
                'alamat_penjual' => $this->input->post('alamat_penjual'),
            ];

            if (!empty($_FILES['gambar']['name'])) {
                $data['gambar'] = $this->upload_image();
            }

            if ($this->M_all->update_shopping($id, $data)) {
                $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui produk.');
            }

            redirect('admin/shopping');
        }
    }

    // Fungsi upload gambar
    private function upload_image() {
        $config['upload_path'] = './assets/upload/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data('file_name');
        } else {
            log_message('error', $this->upload->display_errors());
            return '';
        }
    }


    // Hapus produk
    public function delete($id) {
        // Hapus produk dari database
        if ($this->M_all->delete_shopping($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk.');
        }

        redirect('admin/shopping');
    }
}
