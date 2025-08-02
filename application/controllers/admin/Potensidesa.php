<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Potensidesa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load model
        $this->load->library('form_validation');
    }

    public function index() {
        $data['potensi'] = $this->M_all->get_potensi_desa(); // Ambil data potensi desa
        $data['aplikasi'] = $this->M_all->get_aplikasi();
        // Ambil status aplikasi dari database
        $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('admin/login'); 
        } else {

        // Cek jika status aplikasi dinonaktifkan
        if ($status_aplikasi == 0) {
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('errors/error_404');
            $this->load->view('admin/layouts/footer');
            return;
        }

        $this->load->view('admin/layouts/header', $data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/potensi', $data);
        $this->load->view('admin/layouts/footer');
    }
}

public function ajax_list()
{
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_potensi',
        'select' => 'tb_potensi.*, anggota_keluarga.nama_lengkap, data_warga.alamat',
        'join' => [
            ['anggota_keluarga', 'anggota_keluarga.nik = tb_potensi.nik', 'left'],
            ['data_warga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left']
        ],
        'column_order' => ['tb_potensi.id', 'tb_potensi.nik', 'anggota_keluarga.nama_lengkap', 'data_warga.alamat', 'tb_potensi.bidang', 'tb_potensi.objek', 'tb_potensi.penghasilan_tahunan', 'tb_potensi.nominal', 'tb_potensi.created_at', null],
        'column_search' => ['tb_potensi.nik', 'anggota_keluarga.nama_lengkap', 'data_warga.alamat', 'tb_potensi.bidang', 'tb_potensi.objek'],
        'order' => ['tb_potensi.created_at' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $item) {
        $no++;
        $row = [];
        $row[] = $no;
        $row[] = $item->nik;
        $row[] = $item->nama_lengkap;
        $row[] = $item->alamat;
        $row[] = $item->bidang;
        $row[] = $item->objek;
        $row[] = $item->penghasilan_tahunan . ' ' . $item->satuan;
        $row[] = 'Rp ' . number_format($item->nominal, 0, ',', '.');
        $row[] = date('d-m-Y H:i', strtotime($item->created_at));

        // Tombol aksi dan modal edit
        $row[] = '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editPotensiModal'.$item->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1"
                onclick="konfirmasiDelete(\''.base_url('admin/potensidesa/delete/'.$item->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Potensi -->
            <div class="modal fade" id="editPotensiModal'.$item->id.'" tabindex="-1" aria-labelledby="editPotensiLabel'.$item->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="'.base_url('admin/potensidesa/update').'" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPotensiLabel'.$item->id.'">Edit Potensi Desa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="'.$item->id.'">

                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" name="nik" class="form-control" value="'.$item->nik.'" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="bidang" class="form-label">Bidang</label>
                                    <input type="text" name="bidang" class="form-control" value="'.$item->bidang.'" required>
                                </div>

                                <div class="mb-3">
                                    <label for="objek" class="form-label">Objek</label>
                                    <input type="text" name="objek" class="form-control" value="'.$item->objek.'" required>
                                </div>

                                <div class="mb-3">
                                    <label for="penghasilan_tahunan" class="form-label">Penghasilan Tahunan</label>
                                    <input type="number" name="penghasilan_tahunan" class="form-control" value="'.$item->penghasilan_tahunan.'" required>
                                </div>

                                <div class="mb-3">
                                    <label for="satuan" class="form-label">Satuan</label>
                                    <input type="text" name="satuan" class="form-control" value="'.$item->satuan.'" required>
                                </div>

                                <div class="mb-3">
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <input type="number" name="nominal" class="form-control" value="'.$item->nominal.'" required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        ';

        $data[] = $row;
    }

    $output = [
        "draw" => intval($_POST['draw']),
        "recordsTotal" => $this->M_all->count_all_datatables($config['table']),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];

    echo json_encode($output);
}


    public function add() {
        // Validasi input
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('bidang', 'Bidang', 'required');
        $this->form_validation->set_rules('objek', 'Objek', 'required');
        $this->form_validation->set_rules('penghasilan_tahunan', 'Penghasilan Tahunan', 'required|numeric');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric');
    
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form kembali dengan pesan error
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/potensidesa');
        } else {
            // Jika validasi berhasil, siapkan data untuk disimpan
            $data = [
                'nik' => $this->input->post('nik'),
                'bidang' => $this->input->post('bidang'),
                'objek' => $this->input->post('objek'),
                'penghasilan_tahunan' => $this->input->post('penghasilan_tahunan'),
                'satuan' => $this->input->post('satuan'),
                'nominal' => $this->input->post('nominal')
            ];
    
            // Simpan data ke database
            if ($this->M_all->insert_potensi($data)) {
                $this->session->set_flashdata('success', 'Potensi berhasil ditambahkan.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Potensi Desa', 'Menambahkan data potensi desa');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan potensi.');
            }
    
            redirect('admin/potensidesa');
        }
    }

    // Ambil data potensi untuk modal edit
public function get($id) {
    $data = $this->M_all->get_by_id('tb_potensi', ['id' => $id]);
    echo json_encode($data);
}

public function update()
{
    $id = $this->input->post('id');
    if (!$id) {
        $this->session->set_flashdata('error', 'ID tidak ditemukan.');
        redirect('admin/potensidesa');
        return;
    }

    $this->form_validation->set_rules('nik', 'NIK', 'required');
    $this->form_validation->set_rules('bidang', 'Bidang', 'required');
    $this->form_validation->set_rules('objek', 'Objek', 'required');
    $this->form_validation->set_rules('penghasilan_tahunan', 'Penghasilan Tahunan', 'required|numeric');
    $this->form_validation->set_rules('satuan', 'Satuan', 'required');
    $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/potensidesa');
    } else {
        $data = [
            'nik' => $this->input->post('nik'),
            'bidang' => $this->input->post('bidang'),
            'objek' => $this->input->post('objek'),
            'penghasilan_tahunan' => $this->input->post('penghasilan_tahunan'),
            'satuan' => $this->input->post('satuan'),
            'nominal' => $this->input->post('nominal')
        ];

        if ($this->M_all->update_potensi($id, $data)) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            if ($user_id && $username) {
                $this->load->model('M_log');
                $this->M_log->log_activity($user_id, $username, 'Potensi Desa', 'Update data potensi');
            }

            $this->session->set_flashdata('success', 'Data potensi berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data potensi.');
        }

        redirect('admin/potensidesa');
    }
}


    // Hapus potensi
    public function delete($id) {
        if ($this->M_all->delete_potensi($id)) {
            $this->session->set_flashdata('success', 'Potensi berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Pengumuman', 'Menghapus data potensi desa');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus potensi.');
        }

        redirect('admin/potensidesa');
    }
    
    public function get_nik_options() {
        $this->load->model('M_all');
        $data = $this->M_all->get_nik_list();
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    
        
}
?>