<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aduan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all'); // Load the model
        $this->load->library('upload'); // Load the upload library
    }

    // Display all bansos data in a table
    public function index() {
              // Data umum
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
        $data['aduan'] = $this->M_all->get_all_aduan(); // Fetch all bansos data
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/aduan', $data); 
        $this->load->view('admin/layouts/footer');
    }
    }
    
    public function ajax_list() {
    $this->load->model('M_all');

    $config = [
        'table' => 'tb_aduan',
        'column_order' => ['no_tracking', 'nik', 'isi_aduan', 'foto', 'created_at', 'status', 'jawaban', 'tgl_update', null],
        'column_search' => ['no_tracking', 'nik', 'isi_aduan', 'status', 'jawaban'],
        'order' => ['id' => 'desc']
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $aduan) {
        $no++;
        $row = [];
        $row[] = $no;
        $row[] = $aduan->no_tracking;
        $row[] = $aduan->nik;
        $row[] = $aduan->isi_aduan;

        $row[] = $aduan->foto ? '<a href="' . base_url('assets/foto_aduan/' . $aduan->foto) . '" target="_blank">Lihat</a>' : 'Tidak ada foto';

        $row[] = $aduan->created_at;

        // Badge Status
        if ($aduan->status == 'Pending') {
            $statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
        } elseif ($aduan->status == 'Diproses') {
            $statusBadge = '<span class="badge bg-primary">Diproses</span>';
        } elseif ($aduan->status == 'Selesai') {
            $statusBadge = '<span class="badge bg-success">Selesai</span>';
        } else {
            $statusBadge = $aduan->status;
        }

        $row[] = $statusBadge;
        $row[] = $aduan->jawaban;
        $row[] = $aduan->tgl_update;

        // Tombol Aksi + Modal
        $row[] = '
            <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editAduanModal'.$aduan->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm mb-1"
                onclick="konfirmasiDelete(\''.base_url('admin/aduan/delete/'.$aduan->id).'\')">
                <i class="bi bi-trash"></i>
            </a>

            <!-- Modal Edit Aduan -->
            <div class="modal fade" id="editAduanModal'.$aduan->id.'" tabindex="-1" aria-labelledby="editAduanLabel'.$aduan->id.'" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="'.base_url('admin/aduan/update_status').'" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAduanLabel'.$aduan->id.'">Edit Status Aduan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="'.$aduan->id.'">

                                <div class="mb-3">
                                    <label for="status" class="form-label select2">Status Aduan</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Pending" '.($aduan->status == 'Pending' ? 'selected' : '').'>Pending</option>
                                        <option value="Diproses" '.($aduan->status == 'Diproses' ? 'selected' : '').'>Diproses</option>
                                        <option value="Selesai" '.($aduan->status == 'Selesai' ? 'selected' : '').'>Selesai</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="jawaban" class="form-label">Jawaban / Tanggapan</label>
                                    <textarea name="jawaban" class="form-control" rows="3" required>'.$aduan->jawaban.'</textarea>
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
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('tb_aduan'),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];
    echo json_encode($output);
}



public function add()
{
    // Validasi input
    $this->form_validation->set_rules('nik', 'NIK', 'required|exact_length[16]|numeric', [
        'exact_length' => 'NIK harus terdiri dari 16 digit angka.',
        'numeric' => 'NIK hanya boleh angka.'
    ]);
    $this->form_validation->set_rules('isi_aduan', 'Isi Aduan', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('aduan');
    }

    // Bersihkan input dari XSS
    $nik = $this->input->post('nik', TRUE);
    $isi_aduan = $this->input->post('isi_aduan', TRUE);

    // Cek apakah NIK ada di database
    $cek_nik = $this->db->get_where('anggota_keluarga', ['nik' => $nik])->row();
    if (!$cek_nik) {
        $this->session->set_flashdata('error', 'NIK anda tidak terdaftar!');
        redirect('aduan');
    }

    // Buat nomor tracking unik
    $date = date('dmy');
    $random_number = mt_rand(1000000, 9999999);
    $no_tracking = 'AD' . $date . $random_number;

    // Konfigurasi upload file
    $config['upload_path'] = './assets/foto_aduan/';
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size'] = 5000; // dalam KB
    $config['encrypt_name'] = TRUE; // Hindari nama file tabrakan

    $this->upload->initialize($config);

    $foto = null;

    if (!empty($_FILES['foto']['name'])) {
        // Validasi tambahan MIME type untuk keamanan
        $mime = mime_content_type($_FILES['foto']['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            $this->session->set_flashdata('error', 'Tipe file tidak valid (harus JPG/PNG).');
            redirect('aduan');
        }

        if ($this->upload->do_upload('foto')) {
            $upload_data = $this->upload->data();
            $foto = $upload_data['file_name'];
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('aduan');
        }
    }

    // Data untuk disimpan ke database
    $data = [
        'no_tracking' => $no_tracking,
        'nik' => $nik,
        'isi_aduan' => $isi_aduan,
        'foto' => $foto,
        'created_at' => date('Y-m-d H:i:s'),
        'status' => 'Pending',
        'jawaban' => null,
        'tgl_update' => null,
    ];

    if ($this->M_all->insert_aduan($data)) {
        // Notifikasi berhasil
        $this->session->set_flashdata('success', 'Aduan berhasil ditambahkan. No Tracking Anda: ' . $no_tracking);

        // Panggil fungsi kirim notifikasi
        $judul_notif = 'Aduan Baru Masuk';
        $pesan_notif = 'Aduan dari NIK: ' . $nik . ' - ' . substr(strip_tags($isi_aduan), 0, 50) . '...';
        $this->kirim_notifikasi($judul_notif, $pesan_notif);
    } else {
        $this->session->set_flashdata('error', 'Gagal menambahkan aduan.');
    }

    redirect('aduan');
}


function kirim_notifikasi($judul, $pesan)
{
    // Data konten notifikasi
    $content = array(
        "en" => $pesan
    );

    // Data notifikasi
    $fields = array(
        'app_id' => "b04c0041-b146-41fe-89a9-239b30c1ec99", // Ganti dengan app_id yang benar
        'included_segments' => array('All'), // Kirim ke semua user
        'headings' => array("en" => $judul),
        'contents' => $content,
        'url' => 'https://yourdomain.com', // Opsional: arahkan ke URL tertentu
    );

    $fields = json_encode($fields);

    // Kirim request ke OneSignal
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic nbd5ynhbyefquzgs5ruwq75xi' // REST API Key yang benar
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Debug log untuk mengetahui status pengiriman notifikasi
    log_message('error', 'OneSignal Response: ' . $response);
    log_message('error', 'HTTP Code: ' . $http_code);

    return $response;
}


    public function update_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $jawaban = $this->input->post('jawaban');
        $tgl_update = date('Y-m-d H:i:s'); // Ambil tanggal saat ini
    
        // Validasi
        if (!$id || !$status || !$jawaban) {
            $this->session->set_flashdata('error', 'Data tidak valid!');
            redirect('admin/aduan');
        }
    
        $update = $this->M_all->update_status($id, $status, $jawaban, $tgl_update);
        if ($update) {
            $this->session->set_flashdata('success', 'Status dan jawaban berhasil diperbarui.');
                
        // Ambil user_id dan username sebelum sesi dihancurkan
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');
    
        // Cek apakah user ada dalam sesi sebelum logout
        if ($user_id && $username) {
            // Simpan log aktivitas logout
            $this->load->model('M_log');
            $this->M_log->log_activity($user_id, $username, 'Aduan', 'Mengupdate status data aduan');
    }
    
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }
    
        redirect('admin/aduan');
    }
    
    // Delete bansos
    public function delete($id) {
        // Get the bansos data
        $bansos = $this->M_all->get_aduan_by_id($id);

        // Delete the lampiran if exists
        if ($aduan->lampiran && file_exists('./assets/lampiran/' . $aduan->lampiran)) {
            unlink('./assets/lampiran/' . $aduan->lampiran);
        }

        // Delete the data
        if ($this->M_all->delete_aduan($id)) {
            $this->session->set_flashdata('success', 'Aduan berhasil dihapus.');
             // Ambil user_id dan username sebelum sesi dihancurkan
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
        
            // Cek apakah user ada dalam sesi sebelum logout
            if ($user_id && $username) {
                // Simpan log aktivitas logout
                $this->load->model('M_log');
                $this->M_log->log_activity($user_id, $username, 'Aduan', 'Menghapus data aduan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus Aduan.');
        }

        redirect('admin/aduan');
    }
}
    
}