<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hukum extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all');
        $this->load->library('upload');
    }

    public function index() {
        $data['jenis_hukum'] = $this->M_all->get_all_jenis_hukum();
        $data['aplikasi'] = $this->M_all->get_aplikasi();
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

        $logged_in = $this->session->userdata('logged_in');
        if (!$logged_in) {
            redirect('admin/login');
        }

        if ($status_aplikasi == 0) {
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('errors/error_404');
            $this->load->view('admin/layouts/footer');
            return;
        }

        $this->load->view('admin/layouts/header', $data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/hukum', $data);
        $this->load->view('admin/layouts/footer');
    }
    
    public function daftar($jenis_id)
{
    // Cek login
    if (!$this->session->userdata('logged_in')) {
        redirect('admin/login');
    }

    // Ambil data jenis hukum
    $jenis = $this->M_all->get_jenis_hukum_by_id($jenis_id);
    if (!$jenis) {
        show_404(); // Jenis tidak ditemukan
    }

    // Ambil status aplikasi
    $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;

    // Data yang dikirim ke view
    $data['jenis'] = $jenis;
    $data['aplikasi'] = $this->M_all->get_aplikasi();
    $data['pending_aduan'] = $this->M_all->get_aduan_pending();
    $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
    $data['jenis_hukum'] = $this->M_all->get_jenis_hukum_by_id($jenis_id);
    $data['daftar_hukum'] = $this->M_all->get_daftar_hukum_by_jenis($jenis_id);

    // Jika aplikasi nonaktif
    if ($status_aplikasi == 0) {
        $this->load->view('admin/layouts/header', $data);
        $this->load->view('errors/error_404');
        $this->load->view('admin/layouts/footer');
        return;
    }

    // Tampilkan halaman daftar hukum
    $this->load->view('admin/layouts/header', $data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/daftar_hukum', $data);
    $this->load->view('admin/layouts/footer');
}

public function ajax_daftar_hukum($id)
{
    $config = [
        'table' => 'tb_daftar_hukum',
        'column_order' => ['id', 'judul', 'isi', 'lampiran', 'created_at'],
        'column_search' => ['judul', 'isi'],
        'order' => ['id' => 'desc'],
        'where' => ['id_jenis' => $id]
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;

        $lampiran = $row->lampiran 
            ? '<a href="' . base_url('assets/lampiran_hukum/' . $row->lampiran) . '" target="_blank" class="btn btn-sm btn-secondary">Lihat</a>'
            : '<span class="text-muted">Tidak ada</span>';

        $data[] = [
    $no,
    htmlspecialchars($row->judul),
    '<textarea class="form-control" rows="2" readonly style="font-size: 10px;">' . htmlspecialchars($row->isi) . '</textarea>',
    $lampiran,
    $row->created_at,
    '
    <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '">
      <i class="bi bi-pencil-square"></i>
    </button>
    <a href="javascript:void(0)" onclick="konfirmasiDelete(\'' . base_url('admin/hukum/delete_daftar_hukum/' . $row->id) . '\')" class="btn btn-danger btn-sm mb-1">
                <i class="bi bi-trash"></i>
            </a>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form action="' . base_url('admin/hukum/edit_daftar_hukum') . '" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="' . $row->id . '">
            <input type="hidden" name="id_jenis" value="' . $id . '">
            <input type="hidden" name="lampiran_lama" value="' . $row->lampiran . '">
            <div class="modal-header">
              <h5 class="modal-title">Edit Daftar Hukum</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="' . htmlspecialchars($row->judul) . '" required>
              </div>
              <div class="mb-3">
                <label>Isi</label>
                <textarea name="isi" class="form-control" rows="5" required>' . htmlspecialchars($row->isi) . '</textarea>
              </div>
              <div class="mb-3">
                <label>Lampiran Baru (opsional)</label>
                <input type="file" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                ' . ($row->lampiran ? '<small class="text-muted">Lampiran saat ini: ' . htmlspecialchars($row->lampiran) . '</small>' : '') . '
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    '
];

    }

    echo json_encode([
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_daftar_hukum', ['id_jenis' => $id]),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ]);
}

public function add_daftar_hukum()
{
    // Validasi input form
    $this->form_validation->set_rules('id_jenis', 'Jenis Hukum', 'required');
    $this->form_validation->set_rules('judul', 'Judul', 'required');
    $this->form_validation->set_rules('isi', 'Isi', 'required');

    if ($this->form_validation->run() === FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/hukum');
    } else {
        // Konfigurasi upload file
        $config['upload_path'] = './assets/lampiran_hukum/';
        $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
        $config['max_size'] = 5000; // 2MB

        $this->load->library('upload');
        $this->upload->initialize($config);

        $lampiran = null;
        if (!empty($_FILES['lampiran']['name'])) {
            if ($this->upload->do_upload('lampiran')) {
                $upload_data = $this->upload->data();
                $lampiran = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('admin/hukum/daftar/' . $this->input->post('id_jenis'));
            }
        }

        // Data yang akan disimpan
        $data = [
            'id_jenis'   => $this->input->post('id_jenis'),
            'judul'      => $this->input->post('judul'),
            'isi'        => $this->input->post('isi'),
            'lampiran'   => $lampiran,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->M_all->insert_daftar_hukum($data)) {
            // Simpan log
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            if ($user_id && $username) {
                $this->load->model('M_log');
                $this->M_log->log_activity($user_id, $username, 'Daftar Hukum', 'Menambahkan data daftar hukum');
            }

            $this->session->set_flashdata('success', 'Data hukum berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data hukum.');
        }

        redirect('admin/hukum/daftar/' . $this->input->post('id_jenis'));
    }
}


public function ajax_list() {
    $config = [
        'table' => 'tb_jenis_hukum',
        'column_order' => ['id', 'nama_hukum'],
        'column_search' => ['nama_hukum'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $row) {
        $no++;

        $data[] = [
            $no,
            htmlspecialchars($row->nama_hukum),
            '
            <a href="' . base_url('admin/hukum/daftar/' . $row->id) . '" class="btn btn-info btn-sm mb-1">
                <i class="bi bi-folder2-open"></i> Daftar Hukum
            </a>
            <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editJenisModal' . $row->id . '">
                <i class="bi bi-pencil-square"></i>
            </button>
            <a href="javascript:void(0)" onclick="konfirmasiDelete(\'' . base_url('admin/hukum/delete_jenis/' . $row->id) . '\')" class="btn btn-danger btn-sm mb-1">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Jenis Hukum -->
            <div class="modal fade" id="editJenisModal' . $row->id . '" tabindex="-1">
                <div class="modal-dialog">
                    <form action="' . base_url('admin/hukum/update_jenis') . '" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Jenis Hukum</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="' . $row->id . '">
                                <div class="mb-3">
                                    <label class="form-label">Nama Jenis Hukum</label>
                                    <input type="text" name="nama_hukum" class="form-control" value="' . htmlspecialchars($row->nama_hukum) . '" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            '
        ];
    }

    echo json_encode([
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_jenis_hukum'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ]);
}



public function add_jenis() {
    $this->form_validation->set_rules('nama_hukum', 'Nama Jenis Hukum', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/hukum');
    }

    $data = [
        'nama_hukum' => $this->input->post('nama_hukum')
    ];

    if ($this->M_all->insert_jenis_hukum($data)) {
        $this->_log('Menambahkan jenis hukum');
        $this->session->set_flashdata('success', 'Jenis hukum berhasil ditambahkan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menambahkan jenis hukum.');
    }

    redirect('admin/hukum');
}

public function update_jenis() {
    $id = $this->input->post('id');
    if (!$id) {
        $this->session->set_flashdata('error', 'ID tidak ditemukan.');
        redirect('admin/hukum');
    }

    $this->form_validation->set_rules('nama_hukum', 'Nama Jenis Hukum', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/hukum');
    }

    $data = [
        'nama_hukum' => $this->input->post('nama_hukum')
    ];

    if ($this->M_all->update_jenis_hukum($id, $data)) {
        $this->_log('Mengupdate jenis hukum');
        $this->session->set_flashdata('success', 'Jenis hukum berhasil diperbarui.');
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui jenis hukum.');
    }

    redirect('admin/hukum');
}

public function delete_jenis($id) {
    if (!$id) {
        $this->session->set_flashdata('error', 'ID tidak ditemukan.');
        redirect('admin/hukum');
    }

    if ($this->M_all->delete_jenis_hukum($id)) {
        $this->_log('Menghapus jenis hukum');
        $this->session->set_flashdata('success', 'Jenis hukum berhasil dihapus.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menghapus jenis hukum.');
    }

    redirect('admin/hukum');
}

public function edit_daftar_hukum() {
    $id = $this->input->post('id');
    $id_jenis = $this->input->post('id_jenis');

    if (!$id || !$id_jenis) {
        $this->session->set_flashdata('error', 'ID tidak ditemukan.');
        redirect('admin/hukum/daftar/' . $id_jenis);
    }

    $this->form_validation->set_rules('judul', 'Judul', 'required');
    $this->form_validation->set_rules('isi', 'Isi', 'required');

    if ($this->form_validation->run() === FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/hukum/daftar/' . $id_jenis);
    }

    $data = [
        'judul' => $this->input->post('judul'),
        'isi' => $this->input->post('isi'),
        'created_at' => date('Y-m-d H:i:s')
    ];

    if (!empty($_FILES['lampiran']['name'])) {
    $config['upload_path']   = './assets/lampiran_hukum/';
    $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
    $config['max_size']      = 5000;
    $config['file_name']     = time() . '_' . $_FILES['lampiran']['name'];

    $this->load->library('upload');
    $this->upload->initialize($config); // ← INI PENTING!

    if ($this->upload->do_upload('lampiran')) {
        $upload_data = $this->upload->data();
        $data['lampiran'] = $upload_data['file_name'];

        // Hapus lampiran lama
        $lampiran_lama = $this->input->post('lampiran_lama');
        if ($lampiran_lama && file_exists('./assets/lampiran_hukum/' . $lampiran_lama)) {
            unlink('./assets/lampiran_hukum/' . $lampiran_lama);
        }
    } else {
        $this->session->set_flashdata('error', 'Upload gagal: ' . strip_tags($this->upload->display_errors()));
        redirect('admin/hukum/daftar/' . $id_jenis);
    }
}


    if ($this->M_all->update_daftar_hukum($id, $data)) {
        $this->_log('Mengedit daftar hukum');
        $this->session->set_flashdata('success', 'Daftar hukum berhasil diperbarui.');
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui daftar hukum.');
    }

    redirect('admin/hukum/daftar/' . $id_jenis);
}



public function delete_daftar_hukum($id) {
    if (!$id) {
        $this->session->set_flashdata('error', 'ID tidak ditemukan.');
        redirect('admin/daftar');
    }

    $hukum = $this->db->get_where('tb_daftar_hukum', ['id' => $id])->row();
    if (!$hukum) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('admin/daftar');
    }

    // Hapus file lampiran jika ada
    if ($hukum->lampiran && file_exists('./assets/lampiran_hukum/' . $hukum->lampiran)) {
        unlink('./assets/lampiran_hukum/' . $hukum->lampiran);
    }

    if ($this->M_all->delete_daftar_hukum($id)) {
        $this->_log('Menghapus daftar hukum');
        $this->session->set_flashdata('success', 'Daftar hukum berhasil dihapus.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menghapus daftar hukum.');
    }

    redirect('admin/hukum/daftar/' . $hukum->id_jenis);
}



    private function _log($action) {
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');
        if ($user_id && $username) {
            $this->load->model('M_log');
            $this->M_log->log_activity($user_id, $username, 'Hukum', $action);
        }
    }
}
