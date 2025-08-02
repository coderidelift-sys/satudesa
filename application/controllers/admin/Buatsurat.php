<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '../vendor/autoload.php'; // Sesuaikan path jika vendor berada di root

class Buatsurat extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_all'); // Load the model
		$this->load->library('upload'); // Load the upload library
		$this->load->model('Model_warga'); // Load the model
		$this->load->model('M_template_surat'); // Load the template surat model
		$this->load->helper('indo_helper');
		$this->load->helper('terbilang_helper');
		$this->load->helper('format_alamat_helper');
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library("pdf/TCPDF");
	}

	public function konfigurasi()
	{
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		// Ambil status aplikasi dari database
		$status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
		$data['pending_aduan'] = $this->M_all->get_aduan_pending();
		$data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in != TRUE || empty($logged_in)) {
			redirect('admin/login');
		} else {
			if ($status_aplikasi == 0) {
				$this->load->view('admin/layouts/header', $data);
				$this->load->view('errors/error_404');
				$this->load->view('admin/layouts/footer');
				return;
			}

			$data['status'] = $this->M_all->get_all_suratstatus();
			$data['profil_desa'] = $this->Model_warga->get_nama_desa();

			// 🔹 Ambil daftar perangkat desa dari tb_perangkat_desa
			$data['perangkat_desa'] = $this->db->get('tb_perangkat_desa')->result();
			$data['templates'] = $this->M_template_surat->get_all_templates(); // Ambil semua template surat

			$this->load->view('admin/layouts/header', $data);
			$this->load->view('admin/layouts/sidebar');
			$this->load->view('admin/surat_konfigurasi/konfigurasi_surat', $data);
			$this->load->view('admin/layouts/footer');
		}
	}

	public function konfigurasi_store()
	{
		$template_id = $this->M_template_surat->insert_template([
			'nama_template' => $this->input->post('nama_template'),
			'tipe_surat'    => $this->input->post('tipe_surat'),
			'konten'        => $this->input->post('konten'),
		]);

		$fields = $this->input->post('fields');
		if ($fields && is_array($fields)) {
			foreach ($fields as $f) {
				// Validasi minimal yang harus ada
				if (empty($f['label']) || empty($f['nama_field']) || empty($f['tipe_input'])) {
					continue;
				}

				// Payload dasar
				$payload = [
					'template_id'   => $template_id,
					'label'         => $f['label'],
					'nama_field'    => $f['nama_field'],
					'tipe_input'    => $f['tipe_input'],
					'placeholder'   => $f['placeholder'] ?? '',
					'is_required'   => isset($f['wajib']) ? (bool)$f['wajib'] : true,
					'sumber_data'   => null,
					'opsi_static'   => null,
					'tabel_relasi'  => null,
					'kolom_value'   => null,
					'kolom_label'   => null,
				];

				// Penanganan khusus untuk input bertipe select
				if ($f['tipe_input'] === 'select') {
					$payload['sumber_data'] = $f['sumber_data'] ?? 'static';

					if ($payload['sumber_data'] === 'static' && !empty($f['opsi_static'])) {
						// opsi_static harus berupa array, encode ke JSON
						if (is_array($f['opsi_static'])) {
							$payload['opsi_static'] = json_encode($f['opsi_static'], JSON_UNESCAPED_UNICODE);
						}
					} elseif ($payload['sumber_data'] === 'relasi') {
						// ambil informasi relasi
						$payload['tabel_relasi'] = $f['tabel_relasi'] ?? '';
						$payload['kolom_value']  = $f['kolom_value'] ?? '';
						$payload['kolom_label']  = $f['kolom_label'] ?? '';
					}
				}

				// Simpan ke database
				$this->M_template_surat->insert_field($payload);
			}
		}

		$this->session->set_flashdata('success', 'Template berhasil ditambahkan.');
		redirect('admin/buatsurat/konfigurasi');
	}

	public function konfigurasi_get($id)
	{
		$template = $this->M_template_surat->get_template($id);
		if (!$template) {
			show_404();
		}

		$fields = $this->M_template_surat->get_fields_by_template($id);

		echo json_encode([
			'template' => $template,
			'fields'   => $fields,
		]);
	}

	public function konfigurasi_update()
	{
		$id = $this->input->post('id');
		$template = $this->M_template_surat->get_template($id);
		if (!$template) {
			show_404();
		}

		// Update template
		$this->M_template_surat->update_template($id, [
			'nama_template' => $this->input->post('nama_template'),
			'tipe_surat'    => $this->input->post('tipe_surat'),
			'konten'        => $this->input->post('konten'),
		]);

		// Hapus semua field terkait template ini
		$this->db->delete('field_template_surat', ['template_id' => $id]);

		// Tambah field baru
		$fields = $this->input->post('fields');
		if ($fields && is_array($fields)) {
			foreach ($fields as $f) {
				if (empty($f['label']) || empty($f['nama_field']) || empty($f['tipe_input'])) {
					continue;
				}

				$payload = [
					'template_id'   => $id,
					'label'         => $f['label'],
					'nama_field'    => $f['nama_field'],
					'tipe_input'    => $f['tipe_input'],
					'placeholder'   => $f['placeholder'] ?? '',
					'is_required'   => isset($f['wajib']) ? (bool)$f['wajib'] : true,
					'sumber_data'   => null,
					'opsi_static'   => null,
					'tabel_relasi'  => null,
					'kolom_value'   => null,
					'kolom_label'   => null,
				];

				if ($f['tipe_input'] === 'select') {
					$payload['sumber_data'] = $f['sumber_data'] ?? 'static';

					if ($payload['sumber_data'] === 'static' && !empty($f['opsi_static'])) {
						if (is_array($f['opsi_static'])) {
							$payload['opsi_static'] = json_encode($f['opsi_static'], JSON_UNESCAPED_UNICODE);
						}
					} elseif ($payload['sumber_data'] === 'relasi') {
						$payload['tabel_relasi'] = $f['tabel_relasi'] ?? '';
						$payload['kolom_value']  = $f['kolom_value'] ?? '';
						$payload['kolom_label']  = $f['kolom_label'] ?? '';
					}
				}

				// Simpan ke database
				$this->M_template_surat->insert_field($payload);
			}
		}

		$this->session->set_flashdata('success', 'Template berhasil diperbarui.');
		redirect('admin/buatsurat/konfigurasi');
	}

	public function konfigurasi_delete($id)
	{
		$template = $this->M_template_surat->get_template($id);
		if (!$template) {
			show_404();
		}

		// Hapus template dan semua field terkait
		$this->db->delete('field_template_surat', ['template_id' => $id]);
		$this->M_template_surat->delete_template($id);

		$this->session->set_flashdata('success', 'Template berhasil dihapus.');
		redirect('admin/buatsurat/konfigurasi');
	}

	public function get_all_template_surat()
	{
		$templates = $this->M_template_surat->get_all_templates_formatted(); // join field_template_surat

		header('Content-Type: application/json');
		echo json_encode(['templates' => $templates ?: []]);
	}

	public function get_data_warga()
	{
		$warga = $this->M_template_surat->get_all_warga();

		header('Content-Type: application/json');
		echo json_encode(['warga' => $warga ?: []]);
	}

	public function index()
	{
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		// Ambil status aplikasi dari database
		$status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
		$data['pending_aduan'] = $this->M_all->get_aduan_pending();
		$data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in != TRUE || empty($logged_in)) {
			redirect('admin/login');
		} else {
			if ($status_aplikasi == 0) {
				$this->load->view('admin/layouts/header', $data);
				$this->load->view('errors/error_404');
				$this->load->view('admin/layouts/footer');
				return;
			}

			$data['status'] = $this->M_all->get_all_suratstatus();
			$data['profil_desa'] = $this->Model_warga->get_nama_desa();

			// 🔹 Ambil daftar perangkat desa dari tb_perangkat_desa
			$data['perangkat_desa'] = $this->db->get('tb_perangkat_desa')->result();

			$this->load->view('admin/layouts/header', $data);
			$this->load->view('admin/layouts/sidebar');
			$this->load->view('admin/surat_konfigurasi/buat_surat', $data);
			$this->load->view('admin/layouts/footer');
		}
	}

	public function simpan_surat()
	{
		// ajax
		header('Content-Type: application/json');

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in != TRUE || empty($logged_in)) {
			echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk mengakses halaman ini.']);
			return;
		}

		// Ambil data form biasa
		$data = $this->input->post();

		// Validasi data
		if (empty($data['template_id']) || empty($data['nik'])) {
			echo json_encode(['status' => 'error', 'message' => 'Template atau Warga tidak boleh kosong.']);
			return;
		}

		// Ambil file jika ada
		$file = $_FILES['file'] ?? null;
		$filePath = null;

		if ($file && $file['error'] === UPLOAD_ERR_OK) {
			$uploadDir = FCPATH . 'uploads/surat/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0755, true);
			}

			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$filename = 'surat_' . time() . '.' . $ext;
			$fullPath = $uploadDir . $filename;

			if (move_uploaded_file($file['tmp_name'], $fullPath)) {
				$filePath = 'uploads/surat/' . $filename;
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file.']);
				return;
			}
		}

		// **Generate Nomor Pengajuan**
		$date = date('dmy'); // Format tanggal ddmmyy
		$random_number = mt_rand(1000000, 9999999); // Angka random 7 digit
		$no_pengajuan = 'NP' . $date . $random_number; // Format: PDddmmyyXXXXXXX

		// Siapkan payload untuk disimpan
		$payload = [
			'id_alias' => bin2hex(random_bytes(10)),
			'no_pengajuan' => $no_pengajuan, // Nomor pengajuan yang sudah dibuat
			'nik' => $data['nik'],
			'template_id' => $data['template_id'],
			'no_wa' => $data['no_wa'], // pastikan key-nya cocok!
			'isi_surat' => $data['isi_surat'],
			'file' => $filePath,
		];

		// Simpan data surat
		$this->load->model('M_new_surat');
		$inserted = $this->M_new_surat->insert($payload);
		if ($inserted) {
			echo json_encode(['status' => 'success', 'message' => 'Surat berhasil disimpan.', 'id_alias' => $payload['id_alias']]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan surat.']);
		}
	}

	public function list()
	{
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		// Ambil status aplikasi dari database
		$status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
		$data['pending_aduan'] = $this->M_all->get_aduan_pending();
		$data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in != TRUE || empty($logged_in)) {
			redirect('admin/login');
		} else {
			if ($status_aplikasi == 0) {
				$this->load->view('admin/layouts/header', $data);
				$this->load->view('errors/error_404');
				$this->load->view('admin/layouts/footer');
				return;
			}

			$data['status'] = $this->M_all->get_all_suratstatus();
			$data['profil_desa'] = $this->Model_warga->get_nama_desa();

			// 🔹 Ambil daftar perangkat desa dari tb_perangkat_desa
			$data['perangkat_desa'] = $this->db->get('tb_perangkat_desa')->result();

			// Load model untuk mengambil data surat
			$this->load->model('M_new_surat');
			$data['surat'] = $this->M_new_surat->get_all(); // Ambil semua surat

			$this->load->view('admin/layouts/header', $data);
			$this->load->view('admin/layouts/sidebar');
			$this->load->view('admin/surat_konfigurasi/list_surat', $data);
			$this->load->view('admin/layouts/footer');
		}
	}

	public function edit($id)
	{
		$data['aplikasi'] = $this->M_all->get_aplikasi();
		// Ambil status aplikasi dari database
		$status_aplikasi = $this->M_all->get_pengaturan()->status_aplikasi;
		$data['pending_aduan'] = $this->M_all->get_aduan_pending();
		$data['pending_notifications'] = $this->M_all->get_all_pending_notifications();

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in != TRUE || empty($logged_in)) {
			redirect('admin/login');
		} else {
			if ($status_aplikasi == 0) {
				$this->load->view('admin/layouts/header', $data);
				$this->load->view('errors/error_404');
				$this->load->view('admin/layouts/footer');
				return;
			}

			$data['status'] = $this->M_all->get_all_suratstatus();
			$data['profil_desa'] = $this->Model_warga->get_nama_desa();

			// 🔹 Ambil daftar perangkat desa dari tb_perangkat_desa
			$data['perangkat_desa'] = $this->db->get('tb_perangkat_desa')->result();
			$this->load->model('M_new_surat');
			$data['surat'] = $this->M_new_surat->get_all($id); // Ambil surat berdasarkan id_alias

			$this->load->view('admin/layouts/header', $data);
			$this->load->view('admin/layouts/sidebar');
			$this->load->view('admin/surat_konfigurasi/edit_surat', $data);
			$this->load->view('admin/layouts/footer');
		}
	}

	public function update_surat($id)
	{
		header('Content-Type: application/json');
		$this->load->model('M_new_surat');
		$surat = $this->M_new_surat->get_by_id_alias($id);
		if (!$surat) {
			echo json_encode(['success' => false, 'message' => 'Surat tidak ditemukan.']);
			return;
		}

		// Ambil data form
		$data = $this->input->post();

		// Validasi data
		if (empty($data['template_id']) || empty($data['nik'])) {
			echo json_encode(['success' => false, 'message' => 'Template atau Warga tidak boleh kosong.']);
			return;
		}

		// Penanganan file
		$file = $_FILES['file'] ?? null;
		$filePath = $surat['file']; // Default: file lama

		if ($file && $file['error'] === UPLOAD_ERR_OK) {
			$uploadDir = FCPATH . 'uploads/surat/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0755, true);
			}

			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$filename = 'surat_' . time() . '.' . $ext;
			$fullPath = $uploadDir . $filename;

			if (move_uploaded_file($file['tmp_name'], $fullPath)) {
				$filePath = 'uploads/surat/' . $filename;
				// Hapus file lama jika ada
				if ($surat['file'] && file_exists(FCPATH . $surat['file'])) {
					unlink(FCPATH . $surat['file']);
				}
			} else {
				echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file.']);
				return;
			}
		}

		// Siapkan payload update
		$payload = [
			'nik' => $data['nik'],
			'template_id' => $data['template_id'],
			'no_wa' => $data['no_wa'],
			'isi_surat' => $data['isi_surat'],
			'file' => $filePath,
			'tgl_update' => date('Y-m-d H:i:s'),
		];

		$updated = $this->M_new_surat->update($surat['id'], $payload);
		if ($updated) {
			echo json_encode(['success' => true, 'message' => 'Surat berhasil diperbarui.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Gagal memperbarui surat.']);
		}
	}

	public function delete_surat($id)
	{
		header('Content-Type: application/json');
		$this->load->model('M_new_surat');
		$surat = $this->M_new_surat->get_by_id_alias($id);
		if (!$surat) {
			echo json_encode(['success' => false, 'message' => 'Surat tidak ditemukan.']);
			return;
		}

		if ($surat['file'] && file_exists(FCPATH . $surat['file'])) {
			unlink(FCPATH . $surat['file']);
		}

		$deleted = $this->M_new_surat->delete($surat['id']);
		if ($deleted) {
			echo json_encode(['success' => true, 'message' => 'Surat berhasil dihapus.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Gagal menghapus surat.']);
		}
	}

	public function update_status()
	{
		$this->load->model('M_new_surat');

		$id_alias = $this->input->post('id');
		$status = $this->input->post('status');

		if (empty($id_alias) || empty($status)) {
			$this->session->set_flashdata('error', 'ID atau status tidak boleh kosong.');
			redirect('admin/buatsurat/list');
			return;
		}

		$surat = $this->M_new_surat->get_by_id_alias($id_alias);
		if (!$surat) {
			$this->session->set_flashdata('error', 'Surat tidak ditemukan.');
			redirect('admin/buatsurat/list');
			return;
		}

		// Update status surat
		$updated = $this->db->update('tb_new_surat', ['status' => $status], ['id_alias' => $id_alias]);
		if ($updated) {
			$this->session->set_flashdata('success', 'Status surat berhasil diperbarui.');
		} else {
			$this->session->set_flashdata('error', 'Gagal memperbarui status surat.');
		}
		redirect('admin/buatsurat/list');
	}

	private $token = '$2y$10$byA1Efq.uQ0V03tQB5m03.R06Ro0Eln8LazYUXGkIdw2zsG0rua5e'; // Token API
	private function kirim_whatsapp($target, $pesan)
	{
		$url = 'https://notificationwa.com/api/post';

		$data = array(
			'isi_pesan' => $pesan,
			'nomor_recieved' => $target
		);

		$headers = array(
			"Authorization: $this->token"
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => http_build_query($data), // Pastikan data dikirim dalam format yang benar
			CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$error = curl_error($curl);
		curl_close($curl);

		// Debugging: Log response API WhatsApp
		log_message('debug', "WhatsApp API Response: HTTP Code - $http_code | Response - $response | cURL Error - $error");

		// Jika terjadi error saat cURL
		if ($error) {
			log_message('error', "WhatsApp API Error: $error");
			return false;
		}

		// Decode JSON response
		$result = json_decode($response, true);

		// Pastikan API mengembalikan sukses
		if ($http_code == 200 && isset($result['status']) && strtolower($result['status']) == 'success') {
			return true;
		}

		// Jika API tidak sukses, log pesan error
		log_message('error', "WhatsApp API Error: " . json_encode($result));
		return false;
	}

	public function kirim_surat($id)
	{
		$this->load->model('M_new_surat');
		$this->load->model('M_all');
		$surat = $this->M_new_surat->get_by_id_alias($id);
		$profile_desa = $this->M_all->get_profil_desa();

		if (!$surat) {
			$this->session->set_flashdata('error', 'Surat tidak ditemukan.');
			redirect('admin/buatsurat/list');
			return;
		}

		// Buat link unduh PDF
		$link_pdf = base_url($surat['file']);
		if (!$link_pdf) {
			$this->session->set_flashdata('error', 'File surat tidak ditemukan.');
			redirect('admin/buatsurat/list');
			return;
		}

		// Set expired_at ke 24 jam ke depan dan update tgl_update ke waktu sekarang
		$expired_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
		$update_data = [
			'expired_at' => $expired_at,
			'tgl_update' => date('Y-m-d H:i:s'),
		];
		$this->M_new_surat->update($surat['id'], $update_data);

		// Format pesan WhatsApp
		$pesan = "Hallo, *{$surat['nama_lengkap']}*\n\n" .
			"No Pengajuan : *{$surat['no_pengajuan']}*\n" .
			"Pengajuan *{$surat['jenis_surat']}* siap diunduh:\n" .
			"$link_pdf\n\n" .
			"Link berlaku selama 24 jam.\n" .
			"Link akan kadaluarsa pada : *" . date('d-M-Y H:i:s', strtotime('+24 hours')) . "*\n" .
			"Pastikan anda sudah mendownload file surat PDF.\n\n" .
			"Terima kasih,\nStaff Pelayanan - " .
			"Desa *{$profile_desa->nama_desa}*";

		// Kirim pesan ke WhatsApp
		$response = $this->kirim_whatsapp($surat['no_wa'], $pesan);

		// Beri notifikasi berdasarkan hasil pengiriman
		if ($response) {
			$this->session->set_flashdata('error', 'Gagal mengirimkan pesan');
		} else {
			$this->session->set_flashdata('success', 'Pesan berhasil dikirim!');
		}

		redirect('admin/buatsurat/list');
	}
}
