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
        $this->load->model('M_all'); // Load the model
        $this->load->model('Model_warga'); // Load the model
        $this->load->library('session');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library("pdf/TCPDF");
    }

public function index() {
    // Ambil status aplikasi dari database
    $data['warga'] = $this->Model_warga->get_all_warga(); // Fetch all user data
    $status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
    $data['aplikasi'] = $this->M_all->get_aplikasi();

    // Cek jika status aplikasi = 0
    if ($status_aplikasi == 0) {
        $this->load->view('admin/layouts/header',$data);
        $this->load->view('errors/error_404'); 
         $this->load->view('admin/layouts/footer');
        return; 
    }

    $data['aplikasi'] = $this->M_all->get_aplikasi_admin(); 
    $this->load->view('admin/layouts/header',$data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/warga', $data); 
    $this->load->view('admin/layouts/footer');
}

public function tambah() {
    // Validasi form
    $this->form_validation->set_rules(
        'nomor_kk', 
        'Nomor KK', 
        'required|numeric|exact_length[16]', 
        [
            'required' => 'Nomor KK wajib diisi.',
            'numeric' => 'Nomor KK harus berupa angka.',
            'exact_length' => 'Nomor KK harus tepat 16 digit.'
        ]
    );
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
        // Jika validasi berhasil, simpan data
        $data = array(
            'nomor_kk'    => $this->input->post('nomor_kk'),
            'alamat'      => $this->input->post('alamat'),
            'rt'          => $this->input->post('rt'),
            'rw'          => $this->input->post('rw'),
            'kecamatan'   => $this->input->post('kecamatan'),
            'kota'        => $this->input->post('kota'),
            'kode_pos'    => $this->input->post('kode_pos'),
            'propinsi'    => $this->input->post('propinsi')
        );

        $insert = $this->Model_warga->insert_warga($data);
        
        if ($insert) {
            $this->session->set_flashdata('success', 'Data warga berhasil ditambahkan');
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
    } else {
        $this->session->set_flashdata('error', 'Data gagal diperbarui.');
    }
    redirect('admin/warga');
}

public function hapus($id)
{
    if ($this->db->delete('data_warga', ['id' => $id])) {
        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
    } else {
        $this->session->set_flashdata('error', 'Data gagal dihapus.');
    }
    redirect('admin/warga');
}

public function detail($nomor_kk)
{
    $data['nomor_kk'] = $nomor_kk;
    $data['anggota_keluarga'] = $this->Model_warga->getAnggotaByKK($nomor_kk);
    $data['aplikasi'] = $this->M_all->get_aplikasi_admin(); 
    $this->load->view('admin/layouts/header',$data);
    $this->load->view('admin/layouts/sidebar');
    $this->load->view('admin/detail_warga', $data); 
    $this->load->view('admin/layouts/footer');
}

public function downloadsurat($id = null) {
    if ($id === null) {
        show_404();
    }

    // Load library TCPDF
    $this->load->library('tcpdf');

    // Fetch data with JOIN
    $data['anggota'] = $this->Model_warga->tampil_data($id)->row();

    if (!$data['anggota']) {
        show_404();
    }

    // Create TCPDF instance with F4 size
    $pdf = new TCPDF('P', 'mm', array(210, 330), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);
        $pdf->AddPage();

    // Set metadata
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Desa Sukarame');
    $pdf->SetTitle('Surat Keterangan Miskin');
    $pdf->SetSubject('Surat Keterangan Miskin');
    $pdf->SetKeywords('Surat, Desa, Keterangan, Miskin');

    // HTML Content for Header
$html = '
<div style="text-align:center;">
    <img src="https://kabunan-taman.desa.id/wp-content/uploads/2017/01/download-gambar-logo-garuda-pancasila.jpg" style="line-height: -1;" width="50">
    <h1 style="margin: 0; line-height: 0.60;">KEPALA DESA SUKARAME</h1>
    <h4 style="margin: 0; line-height: -0.70;">KECAMATAN CISOLOK KABUPATEN SUKABUMI</h4>
    <h4 style="margin: 0; text-decoration: underline; line-height: 1.2;">SURAT KETERANGAN MISKIN</h4>
    <h5 style="line-height: -0.5;">Nomor: 410 / .......... / Kesos / 2024</h5>
</div>

<p>Yang bertandatangan di bawah ini:</p>
<table cellpadding="5">
    <tr><td width="150">a. Nama</td><td>: <b>AHMAD ZAENUDIN</b></td></tr>
    <tr><td>b. Jabatan</td><td>: Kepala Desa</td></tr>
</table>

<p>Dengan ini menerangkan bahwa :</p>
<table cellpadding="3">
        <tr>
            <td width="150" style="padding-bottom: 2px;">Nama</td>
            <td width="10" style="padding-bottom: 2px;">:</td>
            <td style="padding-bottom: 2px;"><b>'.strtoupper($data['anggota']->nama_lengkap).'</b></td>
        </tr>
        <tr>
            <td width="150" style="padding-bottom: 2px;">NIK</td>
            <td width="10" style="padding-bottom: 2px;">:</td>
            <td style="padding-bottom: 2px;">'.$data['anggota']->nik.'</td>
        </tr>
        <tr>
            <td width="150" style="padding-bottom: 2px;">Tempat, Tanggal Lahir</td>
            <td width="10" style="padding-bottom: 2px;">:</td>
            <td style="padding-bottom: 2px;">'.$data['anggota']->kota.', '.date('d-m-Y', strtotime($data['anggota']->tgl_lahir)).'</td>
        </tr>
        <tr>
            <td width="150" style="padding-bottom: 2px;">Pekerjaan</td>
            <td width="10" style="padding-bottom: 2px;">:</td>
            <td style="padding-bottom: 2px;">'.$data['anggota']->pekerjaan.'</td>
        </tr>
        <tr>
            <td width="150" style="padding-bottom: 2px;">Alamat</td>
            <td width="10" style="padding-bottom: 2px;">:</td>
            <td style="padding-bottom: 2px;">Kp. '.ucwords(strtolower($data['anggota']->alamat)).' RT '.$data['anggota']->rt.' RW '.$data['anggota']->rw.' Desa '.$data['anggota']->desa.' Kec. '.$data['anggota']->kecamatan.' Kab. '.$data['anggota']->kota.'</td>
        </tr>
    </table>

<p>Nama tersebut di atas adalah warga Desa Sukarame. Sepanjang sepengetahuan kami, yang bersangkutan termasuk kategori Miskin (Pra Sejahtera).</p>
<p>Surat Keterangan ini dibuat untuk Persyaratan JAMPERSAL.</p>

<p>Demikian Surat Keterangan ini dibuat untuk dipergunakan seperlunya.</p>

<br><br>
<div style="text-align:center; line-height:1.5;">
    <p>Sukarame, '.date('d F Y').'</p>
    <p style="margin-top: -10px;">Kepala Desa Sukarame</p>
    <br><br>
    <p><strong>AHMAD ZAENUDIN, S.Kom</strong></p>
</div>';

    // Write HTML to PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Output PDF
    $pdf->Output('surat_keterangan_miskin.pdf', 'I');
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
            'posisi' => $this->input->post('posisi'),
            'pekerjaan' => $this->input->post('pekerjaan'),
        ];

        // Simpan ke database
        $insert = $this->Model_warga->tambah_anggota($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Anggota keluarga berhasil ditambahkan.');
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
        'posisi' => $this->input->post('posisi'),
        'pekerjaan' => $this->input->post('pekerjaan'),
    ];

    $update = $this->Model_warga->update_anggota($id, $data);

    if ($update) {
        $this->session->set_flashdata('success', 'Data anggota berhasil diperbarui.');
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
    $config['max_size']      = 2048;
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
                'alamat'    => trim($row['B']),
                'rt'        => trim($row['C']),
                'rw'        => trim($row['D']),
                'kecamatan' => trim($row['E']),
                'kota'      => trim($row['F']),
                'kode_pos'  => trim($row['G']),
                'propinsi'  => trim($row['H']),
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
        }

        redirect('admin/warga');
    }
}

public function import_anggota_keluarga()
{
    $config['upload_path']   = './assets/uploads/'; // Simpan ke folder assets/uploads
    $config['allowed_types'] = 'xlsx';
    $config['max_size']      = 2048;
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
                'posisi'        => trim($row['F']),
                'pekerjaan'     => trim($row['G']),
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
        }

        redirect('admin/warga');
    }
}


    public function download_template() {
        $this->load->helper('download'); // Tambahkan ini
        force_download('assets/template/template_import.xlsx', NULL);
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
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'RT');
        $sheet->setCellValue('E1', 'RW');
        $sheet->setCellValue('F1', 'Kecamatan');
        $sheet->setCellValue('G1', 'Kota');
        $sheet->setCellValue('H1', 'Kode Pos');
        $sheet->setCellValue('I1', 'Provinsi');
    
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
    
            // Isi data lainnya
            $sheet->setCellValue('C' . $row, $warga->alamat);
            $sheet->setCellValue('D' . $row, $warga->rt);
            $sheet->setCellValue('E' . $row, $warga->rw);
            $sheet->setCellValue('F' . $row, $warga->kecamatan);
            $sheet->setCellValue('G' . $row, $warga->kota);
            $sheet->setCellValue('H' . $row, $warga->kode_pos);
            $sheet->setCellValue('I' . $row, $warga->propinsi);
    
            $row++;
            $idCounter++; // Tambahkan ID
        }
    
        // Set Auto Size untuk Semua Kolom
        foreach (range('A', 'I') as $columnID) {
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
    $sheet->setCellValue('G1', 'Posisi');
    $sheet->setCellValue('H1', 'Pekerjaan');

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
        $sheet->setCellValue('G' . $row, $anggota->posisi);
        $sheet->setCellValue('H' . $row, $anggota->pekerjaan);

        $row++;
        $idCounter++; // Increment ID
    }

    // Set Auto Size untuk Semua Kolom
    foreach (range('A', 'H') as $columnID) {
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

   
    
}