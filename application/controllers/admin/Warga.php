<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '../vendor/autoload.php'; // Sesuaikan path jika vendor berada di root

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Gunakan ini tanpa duplikasi
class Warga extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_access('super_admin','admin');
        $this->load->model('M_all'); // Load the model
        $this->load->model('Model_warga'); // Load the model
        $this->load->library('session');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library("pdf/TCPDF");
    }

public function index() {
    $data['aplikasi'] = $this->M_all->get_aplikasi();
    // Ambil status aplikasi dari database
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

    // Ambil session akses_warga
    $akses = $this->session->userdata('akses_warga'); 

    // Ambil data warga, kirim status akses_warga ke model
    $data['warga'] = $this->Model_warga->get_all_warga($akses);
    $data['dusun'] = $this->M_all->get_all_dusun();
    // ambil dari tabel profil
    $data['profil_desa'] = $this->M_all->get_profil_desa();

    $this->load->view('admin/layouts/header', $data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/warga', $data); 
    $this->load->view('admin/layouts/footer');
}


public function ajax_list() {
    $this->load->model('Warga_datatable_model'); // Buat model baru untuk server-side

    $list = $this->Warga_datatable_model->get_datatables();
    $data = [];
    $no = $_POST['start'];
    foreach ($list as $item) {
        $no++;
        $row = [];

        $row[] = $no;
        $row[] = '<a href="' . base_url('admin/warga/detail/' . $item->nomor_kk) . '" class="badge bg-success text-white">' . $item->nomor_kk . '</a>';
        $row[] = $item->kepala_keluarga;
        $row[] = $item->alamat;
        $row[] = $item->rt;
        $row[] = $item->rw;
        $row[] = $item->desa;
        $row[] = $item->kecamatan;
        $row[] = $item->kota;
        $row[] = $item->kode_pos;
        $row[] = $item->propinsi;
        $row[] = $item->jumlah_anggota . ' Orang';

        $row[] = '<div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Aksi
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="' . base_url('admin/warga/detail/' . $item->nomor_kk) . '"><i class="bi bi-eye me-2"></i> Lihat</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editWarga(
    \'' . $item->id . '\',
    \'' . $item->nomor_kk . '\',
    \'' . addslashes($item->kepala_keluarga) . '\',
    \'' . addslashes($item->alamat) . '\',
    \'' . $item->rt . '\',
    \'' . $item->rw . '\',
    \'' . addslashes($item->desa) . '\',
    \'' . addslashes($item->kecamatan) . '\',
    \'' . addslashes($item->kota) . '\',
    \'' . $item->kode_pos . '\',
    \'' . addslashes($item->propinsi) . '\'
)">
<i class="bi bi-pencil me-2"></i> Edit</a></li>

                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#hapusWargaModal' . $item->id . '"><i class="bi bi-trash me-2"></i> Hapus</a></li>
                    </ul>
                </div>';

        $data[] = $row;
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->Warga_datatable_model->count_all(),
        "recordsFiltered" => $this->Warga_datatable_model->count_filtered(),
        "data" => $data,
    ];
    echo json_encode($output);
}


public function tambah() {
    // Load model
    $this->load->model('Model_warga');

    // Validasi form
    $this->form_validation->set_rules(
        'nomor_kk', 
        'Nomor KK', 
        'required|numeric|exact_length[16]|is_unique[data_warga.nomor_kk]', 
        [
            'required' => 'Nomor KK wajib diisi.',
            'numeric' => 'Nomor KK harus berupa angka.',
            'exact_length' => 'Nomor KK harus tepat 16 digit.',
            'is_unique' => 'Nomor KK sudah terdaftar.'
        ]
    );
    $this->form_validation->set_rules('kepala_keluarga', 'Kepala Keluarga', 'required');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required');
    $this->form_validation->set_rules('rt', 'RT', 'required|numeric');
    $this->form_validation->set_rules('rw', 'RW', 'required|numeric');
    $this->form_validation->set_rules('kecamatan', 'Kecamatan', 'required');
    $this->form_validation->set_rules('kota', 'Kota', 'required');
    $this->form_validation->set_rules('kode_pos', 'Kode Pos', 'required|numeric');
    $this->form_validation->set_rules('propinsi', 'Propinsi', 'required');

    if ($this->form_validation->run() == FALSE) {
        // Jika validasi gagal, kembali ke halaman utama dengan pesan error
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/warga');
    } else {
        // Cek apakah nomor_kk sudah ada di database secara manual (jika tidak ingin pakai 'is_unique')
        $nomor_kk = $this->input->post('nomor_kk');
        if ($this->Model_warga->cek_nomor_kk($nomor_kk)) {
            $this->session->set_flashdata('error', 'Nomor KK sudah terdaftar.');
            redirect('admin/warga');
        }

        // Jika validasi berhasil, simpan data
        $data = array(
            'nomor_kk'    => $nomor_kk,
            'kepala_keluarga' => $this->input->post('kepala_keluarga'),
            'alamat'      => $this->input->post('alamat'),
            'rt'          => $this->input->post('rt'),
            'rw'          => $this->input->post('rw'),
            'desa'        => $this->input->post('desa'),
            'kecamatan'   => $this->input->post('kecamatan'),
            'kota'        => $this->input->post('kota'),
            'kode_pos'    => $this->input->post('kode_pos'),
            'propinsi'    => $this->input->post('propinsi')
        );

        $insert = $this->Model_warga->insert_warga($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data warga berhasil ditambahkan');
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');

            if ($user_id && $username) {
                $this->load->model('M_log');
                $this->M_log->log_activity($user_id, $username, 'Warga', 'Menambahkan data warga');
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data warga');
        }
        redirect('admin/warga');
    }
}

public function edit($id)
{
    $data = $this->input->post();
    $this->db->where('id', $id);
    if ($this->db->update('data_warga', $data)) {
        $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
          $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Mengupdate data warga');
                }
    } else {
        $this->session->set_flashdata('error', 'Data gagal diperbarui.');
    }
    redirect('admin/warga');
}

public function hapus($id)
{
    // Ambil nomor KK berdasarkan id warga
    $warga = $this->db->get_where('data_warga', ['id' => $id])->row();

    if ($warga) {
        $nomor_kk = $warga->nomor_kk;

        // Hapus data di tabel anggota_keluarga berdasarkan nomor_kk
        $this->db->delete('anggota_keluarga', ['nomor_kk' => $nomor_kk]);

        // Hapus data di tabel data_warga berdasarkan id
        if ($this->db->delete('data_warga', ['id' => $id])) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Menghapus data warga');
                }
        } else {
            $this->session->set_flashdata('error', 'Data gagal dihapus.');
        }
    } else {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
    }

    redirect('admin/warga');
}



public function detail($nomor_kk)
{
    $data['nomor_kk'] = $nomor_kk;

    // Ambil data kepala keluarga dari tabel data_warga berdasarkan nomor_kk
    $kepala_keluarga = $this->Model_warga->getWargaByKK($nomor_kk);
    $data['kepala_keluarga'] = $kepala_keluarga ? $kepala_keluarga->kepala_keluarga : 'Tidak Ditemukan';
    $data['anggota_keluarga'] = $this->Model_warga->getAnggotaByKK($nomor_kk);
    $data['aplikasi'] = $this->M_all->get_aplikasi_admin();
    $data['pending_aduan'] = $this->M_all->get_aduan_pending();
    $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

    $this->load->view('admin/layouts/header', $data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/detail_warga', $data);
    $this->load->view('admin/layouts/footer');
}

public function ajax_list_anggota()
{
    $this->load->model('M_all');

    $nomor_kk = $this->input->post('nomor_kk'); // ✅ sekarang ambil dari POST

    $config = [
        'table' => 'anggota_keluarga',
        'column_order' => ['nik', 'nama_lengkap', 'tgl_lahir', 'gender', 'agama', 'status_kawin', 'posisi', 'pekerjaan', null],
        'column_search' => ['nik', 'nama_lengkap', 'posisi', 'pekerjaan'],
        'order' => ['id' => 'asc'],
        'where' => ['nomor_kk' => $nomor_kk] // ✅ filter berdasarkan input
    ];

    $list = $this->M_all->get_datatables_result($config);
    $data = [];
    $no = $_POST['start'];

    foreach ($list as $anggota) {
        $no++;
        $row = [];
        $row[] = $no;
        $row[] = $anggota->nik;
        $row[] = $anggota->nama_lengkap;
        $row[] = date('d-m-Y', strtotime($anggota->tgl_lahir));
        $row[] = ucfirst($anggota->gender);
        $row[] = $anggota->agama;
        $row[] = ucfirst($anggota->status_kawin);
        $row[] = ucfirst($anggota->posisi);
        $row[] = $anggota->pekerjaan;

        $row[] = '
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAnggotaModal'.$anggota->id.'">
                <i class="bi bi-pencil"></i>
            </button>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm"
                onclick="konfirmasiDelete(\''.base_url('admin/anggota_keluarga/delete/'.$anggota->id).'\')">
                <i class="bi bi-trash"></i>
            </a>
        ';

        $data[] = $row;
    }

    $output = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->M_all->count_all_datatables('anggota_keluarga', ['nomor_kk' => $nomor_kk]),
        "recordsFiltered" => $this->M_all->count_filtered_datatables($config),
        "data" => $data,
    ];
    echo json_encode($output);
}



public function tambah_anggota()
{
    // Validasi input
    $this->form_validation->set_rules('nik', 'NIK', 'required|is_unique[anggota_keluarga.nik]|min_length[16]|max_length[16]', [
        'required' => 'NIK wajib diisi.',
        'is_unique' => 'NIK sudah terdaftar.',
        'min_length' => 'NIK harus 16 karakter.',
        'max_length' => 'NIK harus 16 karakter.'
    ]);
    $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required', [
        'required' => 'Nama lengkap wajib diisi.'
    ]);
    $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required', [
        'required' => 'Tanggal lahir wajib diisi.'
    ]);
    $this->form_validation->set_rules('gender', 'Gender', 'required', [
        'required' => 'Jenis kelamin wajib dipilih.'
    ]);
    $this->form_validation->set_rules('agama', 'Agama', 'required', [
        'required' => 'Agama wajib dipilih.'
    ]);
    $this->form_validation->set_rules('status_kawin', 'Satus Kawin', 'required', [
        'required' => 'Status kawin wajib dipilih.'
    ]);
    $this->form_validation->set_rules('posisi', 'Posisi', 'required', [
        'required' => 'Posisi wajib dipilih.'
    ]);
    $this->form_validation->set_rules('pekerjaan', 'Pekerjaan', 'required', [
        'required' => 'Pekerjaan wajib diisi.'
    ]);

    if ($this->form_validation->run() == FALSE) {
        // Kembalikan pesan error ke halaman sebelumnya
        $this->session->set_flashdata('error', validation_errors());
        redirect(base_url('admin/warga/detail/' . $this->input->post('nomor_kk')));
    } else {
        // Ambil data dari form
        $data = [
            'nomor_kk' => $this->input->post('nomor_kk'),
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'tgl_lahir' => $this->input->post('tgl_lahir'),
            'gender' => $this->input->post('gender'),
            'agama' => $this->input->post('agama'),
            'status_kawin' => $this->input->post('status_kawin'),
            'posisi' => $this->input->post('posisi'),
            'pekerjaan' => $this->input->post('pekerjaan'),
        ];

        // Simpan ke database
        $insert = $this->Model_warga->tambah_anggota($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Anggota keluarga berhasil ditambahkan.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Menambahkan data anggota keluarga');
                }
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan anggota keluarga.');
        }

        // Redirect kembali ke halaman detail warga
        redirect(base_url('admin/warga/detail/' . $this->input->post('nomor_kk')));
    }
}

public function edit_anggota()
{
    $id = $this->input->post('id');
    $nomor_kk = $this->input->post('nomor_kk'); // Ambil nomor KK dari input form
    $data = [
        'nik' => $this->input->post('nik'),
        'nama_lengkap' => $this->input->post('nama_lengkap'),
        'tgl_lahir' => $this->input->post('tgl_lahir'),
        'gender' => $this->input->post('gender'),
        'agama' => $this->input->post('agama'),
        'status_kawin' => $this->input->post('status_kawin'),
        'posisi' => $this->input->post('posisi'),
        'pekerjaan' => $this->input->post('pekerjaan'),
    ];

    $update = $this->Model_warga->update_anggota($id, $data);

    if ($update) {
        $this->session->set_flashdata('success', 'Data anggota berhasil diperbarui.');
          $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Mengupdate data anggota keluarga');
                }
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui data anggota.');
    }

    // Redirect kembali ke halaman detail keluarga
    redirect(base_url('admin/warga/detail/' . $nomor_kk));
}

public function hapus_anggota()
{
    $id = $this->input->post('id');
    $nomor_kk = $this->input->post('nomor_kk'); // Ambil nomor KK dari input form
    $delete = $this->Model_warga->hapus_anggota($id);

    if ($delete) {
        $this->session->set_flashdata('success', 'Data anggota berhasil dihapus.');
          $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Menghapus data anggota keluarga');
                }
    } else {
        $this->session->set_flashdata('error', 'Gagal menghapus data anggota.');
    }

    // Redirect kembali ke halaman detail keluarga
    redirect(base_url('admin/warga/detail/' . $nomor_kk));
}

public function import()
{
    $config['upload_path']   = './assets/uploads/'; // Simpan ke folder assets/uploads
    $config['allowed_types'] = 'xlsx';
    $config['max_size']      = 10000;
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file_import')) {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect('admin/warga');
    } else {
        $file = $this->upload->data();
        $filePath = './assets/uploads/' . $file['file_name'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $errorMessages = [];
        $successCount = 0;
        $errorCount = 0;
        $duplikatKK = []; // Untuk menyimpan nomor KK yang duplikat

        foreach ($sheetData as $index => $row) {
            if ($index == 1) continue; // Lewati header

            $nomor_kk = trim($row['A']);
            $data = [
                'nomor_kk'  => $nomor_kk,
                'kepala_keluarga'    => trim($row['B']),
                'alamat'    => trim($row['C']),
                'rt'        => trim($row['D']),
                'rw'        => trim($row['E']),
                'desa'      => trim($row['F']),
                'kecamatan' => trim($row['G']),
                'kota'      => trim($row['H']),
                'kode_pos'  => trim($row['I']),
                'propinsi'  => trim($row['J']),
            ];

            // Cek apakah nomor KK sudah ada di database
            $cekDuplikat = $this->db->get_where('data_warga', ['nomor_kk' => $nomor_kk])->num_rows();
            
            if (empty($nomor_kk) || empty($data['alamat'])) {
                $errorMessages[] = "Baris $index: Data tidak lengkap.";
                $errorCount++;
            } elseif ($cekDuplikat > 0) {
                $duplikatKK[] = $nomor_kk;
                $errorCount++;
            } elseif (!$this->Model_warga->insert_warga($data)) {
                $errorMessages[] = "Baris $index: Gagal menyimpan ke database.";
                $errorCount++;
            } else {
                $successCount++;
            }
        }

        // Pesan error untuk data duplikat
        if (!empty($duplikatKK)) {
            $duplikatKK = array_unique($duplikatKK); // Hilangkan duplikasi dalam daftar error
            $errorMessages[] = "Terdapat " . count($duplikatKK) . " Nomor KK yang sudah ada: <br>" . implode(", ", $duplikatKK);
        }

        if ($errorCount > 0) {
            $this->session->set_flashdata('error', implode("<br>", $errorMessages));
        } else {
            $this->session->set_flashdata('success', "Berhasil mengimport $successCount data. File disimpan di <b>assets/uploads/{$file['file_name']}</b>");
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Mengimport data per KK');
                }
        }

        redirect('admin/warga');
    }
}

public function import_anggota_keluarga()
{
    $config['upload_path']   = './assets/uploads/'; // Simpan ke folder assets/uploads
    $config['allowed_types'] = 'xlsx';
    $config['max_size']      = 20000;
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file_import')) {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect('admin/warga');
    } else {
        $file = $this->upload->data();
        $filePath = './assets/uploads/' . $file['file_name'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $errorMessages = [];
        $successCount = 0;
        $errorCount = 0;
        $duplikatNIK = []; // Untuk menyimpan NIK yang duplikat

        foreach ($sheetData as $index => $row) {
            if ($index == 1) continue; // Lewati header

            $data = [
                'nomor_kk'      => trim($row['A']),
                'nik'           => trim($row['B']),
                'nama_lengkap'  => trim($row['C']),
                'tgl_lahir'     => trim($row['D']),
                'gender'        => trim($row['E']),
                'agama'        => trim($row['F']),
                'status_kawin' => trim($row['G']),
                'posisi'        => trim($row['H']),
                'pekerjaan'     => trim($row['I']),
            ];

            // Cek apakah NIK sudah ada di database
            $cekDuplikat = $this->db->get_where('anggota_keluarga', ['nik' => $data['nik']])->num_rows();
            
            if (empty($data['nomor_kk']) || empty($data['nik']) || empty($data['nama_lengkap'])) {
                $errorMessages[] = "Baris $index: Data tidak lengkap.";
                $errorCount++;
            } elseif ($cekDuplikat > 0) {
                $duplikatNIK[] = $data['nik'];
                $errorCount++;
            } elseif (!$this->db->insert('anggota_keluarga', $data)) {
                $errorMessages[] = "Baris $index: Gagal menyimpan ke database.";
                $errorCount++;
            } else {
                $successCount++;
            }
        }

        // Pesan error untuk data duplikat
        if (!empty($duplikatNIK)) {
            $duplikatNIK = array_unique($duplikatNIK); // Hilangkan duplikasi dalam daftar error
            $errorMessages[] = "Terdapat " . count($duplikatNIK) . " NIK yang sudah ada: <br>" . implode(", ", $duplikatNIK);
        }

        if ($errorCount > 0) {
            $this->session->set_flashdata('error', implode("<br>", $errorMessages));
        } else {
            $this->session->set_flashdata('success', "Berhasil mengimport $successCount data. File disimpan di <b>assets/uploads/{$file['file_name']}</b>");
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Mengimport data anggota keluarga');
                }
        }

        redirect('admin/warga');
    }
}


    public function download_template() {
        $this->load->helper('download'); // Tambahkan ini
        force_download('assets/template/template_import_warga_per_KK.xlsx', NULL);
    }

    public function download_template_anggota_keluarga() {
        $this->load->helper('download'); // Tambahkan ini
        force_download('assets/template/template_import_anggota_keluarga.xlsx', NULL);
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor KK');
        $sheet->setCellValue('C1', 'Nama Kepala Keluarga');
        $sheet->setCellValue('D1', 'Alamat');
        $sheet->setCellValue('E1', 'RT');
        $sheet->setCellValue('F1', 'RW');
        $sheet->setCellValue('G1', 'Desa');
        $sheet->setCellValue('H1', 'Kecamatan');
        $sheet->setCellValue('I1', 'Kabupaten');
        $sheet->setCellValue('J1', 'Kode Pos');
        $sheet->setCellValue('K1', 'Provinsi');
    
        // Ambil Data dari Database
        $query = $this->db->get('data_warga'); // Sesuaikan nama tabel
        $data_warga = $query->result();
    
        $row = 2; // Baris mulai untuk data
        $idCounter = 1; // Inisialisasi ID otomatis
    
        foreach ($data_warga as $warga) {
            // ID sebagai penomoran otomatis
            $sheet->setCellValue('A' . $row, $idCounter);
            // Atur Nomor KK sebagai teks
            $sheet->setCellValueExplicit('B' . $row, $warga->nomor_kk, DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $warga->kepala_keluarga);
            // Isi data lainnya
            $sheet->setCellValue('D' . $row, $warga->alamat);
            $sheet->setCellValue('E' . $row, $warga->rt);
            $sheet->setCellValue('F' . $row, $warga->rw);
            $sheet->setCellValue('G' . $row, $warga->desa);
            $sheet->setCellValue('H' . $row, $warga->kecamatan);
            $sheet->setCellValue('I' . $row, $warga->kota);
            $sheet->setCellValue('J' . $row, $warga->kode_pos);
            $sheet->setCellValue('K' . $row, $warga->propinsi);
    
            $row++;
            $idCounter++; // Tambahkan ID
        }
    
        // Set Auto Size untuk Semua Kolom
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // Nama file
        $filename = 'Data_Warga.xlsx';
    
        // Header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    

    public function export_anggota_keluarga($nomor_kk)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set Header Kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nomor KK');
    $sheet->setCellValue('C1', 'NIK');
    $sheet->setCellValue('D1', 'Nama Lengkap');
    $sheet->setCellValue('E1', 'Tanggal Lahir');
    $sheet->setCellValue('F1', 'Gender');
    $sheet->setCellValue('G1', 'Agama');
    $sheet->setCellValue('H1', 'Status Kawin');
    $sheet->setCellValue('I1', 'Posisi');
    $sheet->setCellValue('J1', 'Pekerjaan');

    // Ambil Data dari Database Berdasarkan Nomor KK
    $this->db->where('nomor_kk', $nomor_kk);
    $query = $this->db->get('anggota_keluarga'); // Sesuaikan nama tabel
    $data_keluarga = $query->result();

    $row = 2; // Mulai dari baris kedua (baris pertama untuk header)
    $idCounter = 1; // Inisialisasi ID otomatis
    foreach ($data_keluarga as $anggota) {
        $sheet->setCellValue('A' . $row, $idCounter); // Penomoran otomatis
        $sheet->setCellValueExplicit('B' . $row, $anggota->nomor_kk, DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('C' . $row, $anggota->nik, DataType::TYPE_STRING);
        $sheet->setCellValue('D' . $row, $anggota->nama_lengkap);
        $sheet->setCellValue('E' . $row, $anggota->tgl_lahir);
        $sheet->setCellValue('F' . $row, $anggota->gender);
        $sheet->setCellValue('G' . $row, $anggota->agama);
        $sheet->setCellValue('H' . $row, $anggota->status_kawin);
        $sheet->setCellValue('I' . $row, $anggota->posisi);
        $sheet->setCellValue('J' . $row, $anggota->pekerjaan);

        $row++;
        $idCounter++; // Increment ID
    }

    // Set Auto Size untuk Semua Kolom
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Nama file
    $filename = 'Data_Anggota_Keluarga_' . $nomor_kk . '.xlsx';

    // Header untuk download file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// download pdf
 public function download_pdf() {
        // Ambil data warga dan nama desa dari database
        $data_warga = $this->Model_warga->get_all_warga();
        $desa = $this->Model_warga->get_nama_desa();

        // Ambil nama desa, jika tidak ada default "Desa Tidak Diketahui"
        $nama_desa = ($desa) ? $desa->nama_desa : 'Desa Tidak Diketahui';

        // Buat objek TCPDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Data Warga');
        
        // Header PDF
        $pdf->SetHeaderData('', 0, 'Laporan Data Warga - ' . strtoupper($nama_desa), 'Dicetak pada: ' . date('d-m-Y H:i:s'));

        // Set margin dan font
        $pdf->SetMargins(10, 20, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->SetFont('dejavusans', '',7);
        $pdf->AddPage();

        // HTML untuk tabel data
        $html = '<h3 align="center">Data Warga - ' . strtoupper($nama_desa) . '</h3>';
        $html .= '<table border="1" cellpadding="5">
                    <thead>
                        <tr style="background-color:#f2f2f2;">
                            <th>No</th>
                            <th>Nomor KK</th>
                            <th>Kepala Keluarga</th>
                            <th>Alamat</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th>Desa</th>
                            <th>Kecamatan</th>
                            <th>Kota</th>
                            <th>Kode Pos</th>
                            <th>Provinsi</th>
                            <th>Anggota</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        $no = 1;
        foreach ($data_warga as $warga) {
            $html .= '<tr>
                        <td align="center">' . $no++ . '</td>
                        <td>' . htmlspecialchars($warga->nomor_kk) . '</td>
                        <td>' . htmlspecialchars($warga->kepala_keluarga) . '</td>
                        <td>' . htmlspecialchars($warga->alamat) . '</td>
                        <td align="center">' . htmlspecialchars($warga->rt) . '</td>
                        <td align="center">' . htmlspecialchars($warga->rw) . '</td>
                        <td>' . htmlspecialchars($warga->desa) . '</td>
                        <td>' . htmlspecialchars($warga->kecamatan) . '</td>
                        <td>' . htmlspecialchars($warga->kota) . '</td>
                        <td align="center">' . htmlspecialchars($warga->kode_pos) . '</td>
                        <td>' . htmlspecialchars($warga->propinsi) . '</td>
                        <td align="center">' . htmlspecialchars($warga->jumlah_anggota) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        // Tulis ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output file PDF
        $pdf->Output('Data_Warga_' . strtolower(str_replace(' ', '_', $nama_desa)) . '.pdf', 'D');
    }
    
// download PDF DETAIL
public function download_pdf_anggota($nomor_kk)
{
    // Ambil data warga dan anggota keluarga
    $data_warga = $this->Model_warga->getWargaByKK($nomor_kk);
    $anggota_keluarga = $this->Model_warga->getAnggotaByKK($nomor_kk);

    // Ambil data kepala keluarga dari posisi "Kepala Rumah Tangga"
    $kepala_keluarga = $this->Model_warga->getKepalaKeluargaByKK($nomor_kk);

    // Cek apakah data ditemukan
    if (!$data_warga || !$kepala_keluarga) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('admin/warga/detail/' . $nomor_kk);
    }

    // Buat objek PDF
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Detail Warga - ' . $kepala_keluarga->nama_lengkap); // Nama kepala keluarga di judul
    $pdf->SetHeaderData('', 0, 'Detail Warga', 'Nomor KK: ' . $nomor_kk . ' | Dicetak pada: ' . date('d-m-Y H:i:s'));
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(5, 5, 5);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->AddPage();

    // Header Warga
    $html = '<h2 align="center">Detail Keluarga</h2>';
    $html .= '<table border="1" cellpadding="5">
                <tr><td><strong>Nomor KK</strong></td><td>' . $data_warga->nomor_kk . '</td></tr>
                <tr><td><strong>Nama Kepala Keluarga</strong></td><td>' . $kepala_keluarga->nama_lengkap . '</td></tr> <!-- Nama kepala keluarga -->
                <tr><td><strong>Alamat</strong></td><td>' . $data_warga->alamat . '</td></tr>
                <tr><td><strong>RT/RW</strong></td><td>' . $data_warga->rt . ' / ' . $data_warga->rw . '</td></tr>
                <tr><td><strong>Kecamatan</strong></td><td>' . $data_warga->kecamatan . '</td></tr>
                <tr><td><strong>Kota</strong></td><td>' . $data_warga->kota . '</td></tr>
                <tr><td><strong>Propinsi</strong></td><td>' . $data_warga->propinsi . '</td></tr>
              </table><br>';

    // Data Anggota Keluarga
    $html .= '<h3>Anggota Keluarga</h3>';
    $html .= '<table border="1" cellpadding="5">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>Tgl Lahir</th>
                    <th>Gender</th>
                    <th>Agama</th>
                    <th>Status Kawin</th>
                    <th>Posisi</th>
                    <th>Pekerjaan</th>
                </tr>';
    $no = 1;
    foreach ($anggota_keluarga as $anggota) {
        // Ubah format tanggal lahir (tgl_lahir) ke format dd/mm/yyyy
        $tgl_lahir = date("d-m-Y", strtotime($anggota->tgl_lahir));

        $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . $anggota->nama_lengkap . '</td>
                    <td>' . $anggota->nik . '</td>
                    <td>' . $tgl_lahir . '</td> <!-- Tanggal Lahir dengan format dd/mm/yyyy -->
                    <td>' . $anggota->gender . '</td>
                    <td>' . $anggota->agama . '</td>
                    <td>' . $anggota->status_kawin . '</td>
                    <td>' . $anggota->posisi . '</td>
                    <td>' . $anggota->pekerjaan . '</td>
                  </tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    // Output file PDF
    $pdf->Output('Detail_Warga_' . $nomor_kk . '.pdf', 'D');
}

public function verifikasi_hapus()
{
    // Ambil user ID dari sesi
    $user_id = $this->session->userdata('user_id');

    // Cek apakah user sudah login
    if (!$user_id) {
        $this->session->set_flashdata('error', 'Anda harus login untuk melakukan aksi ini.');
        redirect('admin/login');
    }

    // Ambil password yang diinputkan
    $input_password = $this->input->post('password');

    // Ambil password asli dari database
    $this->load->model('M_auth');
    $user = $this->M_auth->get_user_by_id($user_id);

    // Verifikasi password
    if ($user && password_verify($input_password, $user->password)) {
        // Jika password benar, hapus data warga
        $this->db->trans_start();
        $this->db->empty_table('anggota_keluarga');
        $this->db->empty_table('data_warga');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'gagal mengahapus semua data warga dan anggota keluarga.');
                }
        } else {
            $this->session->set_flashdata('success', 'Semua data warga berhasil dihapus.');
              $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'Mengapus semua data warga dan anggota keluarga');
                }
        }
    } else {
        // Jika password salah
        $this->session->set_flashdata('error', 'Password salah! Data tidak dihapus.');
        $user_id = $this->session->userdata('user_id');
                $username = $this->session->userdata('username');
            
                // Cek apakah user ada dalam sesi sebelum logout
                if ($user_id && $username) {
                    // Simpan log aktivitas logout
                    $this->load->model('M_log');
                    $this->M_log->log_activity($user_id, $username, 'Warga', 'gagal mengahapus semua data warga dan anggota keluarga.');
                }
    }

    redirect('admin/pengaturan'); // Redirect kembali ke halaman pengaturan
}




    
}