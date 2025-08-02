<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    // Pastikan autoload PhpSpreadsheet tersedia
    require_once APPPATH . '../vendor/autoload.php';

    // Import class di dalam method (karena ini bukan namespace global)
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Dusun extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
    }

    // Display all staff contact data in a table
    public function index() {
        $data['dusun'] = $this->M_all->get_all_dusun(); // Fetch all staff contact data
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
        $this->load->view('admin/dusun', $data); 
        $this->load->view('admin/layouts/footer');
    }
}


public function ajax_list() {
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_dusun',
        'column_order' => ['nama_dusun', 'created_at'],
        'column_search' => ['nama_dusun'],
        'order' => ['id' => 'desc']
    ];

    $start = $_POST['start'] ?? 0;
    $draw = $_POST['draw'] ?? 1;

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $start;

    foreach ($list as $dusun) {
        $no++;
        $row = [];
        $row[] = $no;
        $row[] = $dusun->nama_dusun;
        $row[] = $dusun->nama_kadus;

        // Hitung total RW dan RT
        $this->db->select('COUNT(DISTINCT rw) AS total_rw, COUNT(DISTINCT rt) AS total_rt');
        $this->db->from('data_warga');
        $this->db->where('alamat', $dusun->nama_dusun);
        $result = $this->db->get()->row();
        $total_rw = $result->total_rw ?? 0;
        $total_rt = $result->total_rt ?? 0;

        $row[] = $total_rw;
        $row[] = $total_rt;
        $row[] = $dusun->created_at;

        // Tombol Edit
        $modal_edit = '
        <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editModal'.$dusun->id.'">
            <i class="bi bi-pencil"></i>
        </button>
        <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1" onclick="konfirmasiDelete(\''.base_url('admin/dusun/delete/'.$dusun->id).'\')">
            <i class="bi bi-trash"></i>
        </a>

        <div class="modal fade" id="editModal'.$dusun->id.'" tabindex="-1" aria-labelledby="editModalLabel'.$dusun->id.'" aria-hidden="true">
            <div class="modal-dialog">
                <form action="'.base_url('admin/dusun/edit/'.$dusun->id).'" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Dusun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Dusun</label>
                                <input type="text" class="form-control" name="nama_dusun" value="'.$dusun->nama_dusun.'" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Kepala Dusun</label>
                                <input type="text" class="form-control" name="nama_kadus" value="'.$dusun->nama_kadus.'" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>';

        // Modal Detail RW dan RT
        $modal_detail = '
        <button type="button" class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#detailModal'.$dusun->id.'">
            <i class="bi bi-search"></i>
        </button>

        <div class="modal fade" id="detailModal'.$dusun->id.'" tabindex="-1" aria-labelledby="detailModalLabel'.$dusun->id.'" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail RT dan RW Dusun '.$dusun->nama_dusun.'</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Total Warga per RW</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-4">
                                <thead><tr><th>RW</th><th>Total Keluarga</th></tr></thead>
                                <tbody>';

        // Ambil data total warga per RW
        $this->db->select('rw, COUNT(*) as total_warga_rw');
        $this->db->from('data_warga');
        $this->db->where('alamat', $dusun->nama_dusun);
        $this->db->group_by('rw');
        $rw_list = $this->db->get()->result();

        foreach ($rw_list as $rw_row) {
            $modal_detail .= '<tr><td>'.$rw_row->rw.'</td><td>'.$rw_row->total_warga_rw.'</td></tr>';
        }

        $modal_detail .= '
                                </tbody>
                            </table>
                        </div>

                        <h6>Detail per RT</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>RT</th><th>Total Keluarga</th><th>Laki-laki</th><th>Perempuan</th></tr></thead>
                                <tbody>';

        // Ambil daftar RT
        $this->db->select('rt');
        $this->db->from('data_warga');
        $this->db->where('alamat', $dusun->nama_dusun);
        $this->db->group_by('rt');
        $rt_list = $this->db->get()->result();

        foreach ($rt_list as $rt_row) {
            $rt = $rt_row->rt;
            $total_warga = $this->db->where(['alamat' => $dusun->nama_dusun, 'rt' => $rt])->count_all_results('data_warga');

            $total_l = $this->db
                ->from('anggota_keluarga ak')
                ->join('data_warga dw', 'ak.nomor_kk = dw.nomor_kk')
                ->where(['dw.alamat' => $dusun->nama_dusun, 'dw.rt' => $rt, 'ak.gender' => 'Laki-laki'])
                ->count_all_results();

            $total_p = $this->db
                ->from('anggota_keluarga ak')
                ->join('data_warga dw', 'ak.nomor_kk = dw.nomor_kk')
                ->where(['dw.alamat' => $dusun->nama_dusun, 'dw.rt' => $rt, 'ak.gender' => 'Perempuan'])
                ->count_all_results();

            $modal_detail .= '<tr><td>'.$rt.'</td><td>'.$total_warga.'</td><td>'.$total_l.'</td><td>'.$total_p.'</td></tr>';
        }

        $modal_detail .= '
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>';

$export_url = base_url('admin/dusun/export_warga_by_dusun/' . rawurlencode($dusun->nama_dusun));
$btn_export = '
    <a href="javascript:void(0);" 
       class="btn btn-success btn-sm mb-1 btn-export-excel" 
       data-url="' . $export_url . '" 
       title="Export Excel">
        <i class="bi bi-file-earmark-excel"></i>
    </a>';



        // Gabungkan semua tombol
        $row[] = $modal_edit . $modal_detail . $btn_export;

        $data[] = $row;
    }

    $output = [
        "draw" => intval($draw),
        "recordsTotal" => $this->M_all->count_all_datatables('tb_dusun'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];
    echo json_encode($output);
}




    // Add new staff contact
    public function add() {
        // Validate form input
        $this->form_validation->set_rules('nama_dusun', 'Nama Dusun', 'required');
        $this->form_validation->set_rules('nama_kadus', 'Nama Kepala Dusun', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for insertion
            $data = [
                'nama_dusun' => $this->input->post('nama_dusun'),
                'nama_kadus' => $this->input->post('nama_kadus'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Insert the data
            if ($this->M_all->insert_dusun($data)) {
                $this->session->set_flashdata('success', 'Dusun berhasil ditambahkan.');
                  $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Dusun', 'Menambahkan data kontak');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan dusun.');
            }

            redirect('admin/dusun');
        }
    }

    // Edit staff contact
    public function edit($id) {
        // Validate form input
        $this->form_validation->set_rules('nama_dusun', 'Nama Dusun', 'required');
        $this->form_validation->set_rules('nama_kadus', 'Nama Kepala Dusun', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form
            $this->index();
        } else {
            // Prepare data for update
            $data = [
                'nama_dusun' => $this->input->post('nama_dusun'),
                'nama_kadus' => $this->input->post('nama_kadus'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Update the database
            if ($this->M_all->update_dusun($id, $data)) {
                $this->session->set_flashdata('success', 'Dusun berhasil diperbarui.');
                $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Dusun', 'Mengupdate data dusun');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui dusun.');
            }

            redirect('admin/dusun');
        }
    }

    // Delete staff contact
    public function delete($id) {
        // Delete the data
        if ($this->M_all->delete_dusun($id)) {
            $this->session->set_flashdata('success', 'Dusun berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Dusun', 'Menghapus data kontak');
                }
            
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus dusun.');
        }

        redirect('admin/dusun');
    }
    
public function export_warga_by_dusun($nama_dusun)
{

    // Decode nama dusun (jika mengandung spasi)
    $nama_dusun = urldecode($nama_dusun);

    // Ambil data warga berdasarkan dusun
    $this->db->where('alamat', $nama_dusun);
    $data_warga = $this->db->get('data_warga')->result();

    // Ambil data kepala dusun (nama_kadus) dari tabel dusun, sesuaikan nama tabel dan kolom
    $this->db->select('nama_kadus');
    $this->db->where('nama_dusun', $nama_dusun);
    $kadus_row = $this->db->get('tb_dusun')->row();
    $nama_kadus = $kadus_row ? $kadus_row->nama_kadus : '-';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header informasi nama dusun dan kepala dusun di baris 1 dan 2
    $sheet->setCellValue('A1', 'NAMA DUSUN :');
    $sheet->setCellValue('B1', $nama_dusun);
    $sheet->setCellValue('A2', 'NAMA KADUS :');
    $sheet->setCellValue('B2', $nama_kadus);

    // Header kolom data mulai di baris 4 (agar ada jarak)
    $sheet->setCellValue('A4', 'No');
    $sheet->setCellValue('B4', 'Nomor KK');
    $sheet->setCellValue('C4', 'Kepala Keluarga');
    $sheet->setCellValue('D4', 'Alamat');
    $sheet->setCellValue('E4', 'RT');
    $sheet->setCellValue('F4', 'RW');

    $row = 5; // mulai data baris 5
    $no = 1;
    foreach ($data_warga as $warga) {
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValueExplicit('B' . $row, $warga->nomor_kk, DataType::TYPE_STRING); // supaya nomor KK tidak berubah format
        $sheet->setCellValue('C' . $row, $warga->kepala_keluarga);
        $sheet->setCellValue('D' . $row, $warga->alamat);
        $sheet->setCellValue('E' . $row, $warga->rt);
        $sheet->setCellValue('F' . $row, $warga->rw);
        $row++;
    }

    // Auto size kolom A sampai F
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // File name
    $filename = 'Data_Warga_Dusun_' . str_replace(' ', '_', $nama_dusun) . '_' . date('Ymd_His') . '.xlsx';

    // Output ke browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}




}