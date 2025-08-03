<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buatsurat extends CI_Controller
{
	private $token = '$2y$10$byA1Efq.uQ0V03tQB5m03.R06Ro0Eln8LazYUXGkIdw2zsG0rua5e';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_all');
		$this->load->model('M_template_surat');
		$this->load->model('Model_warga');
		$this->load->library('upload');
		$this->load->library('form_validation');

		// Load helper jika ada
		if (file_exists(APPPATH . 'helpers/template_surat_helper.php')) {
			$this->load->helper('template_surat');
		}
		// Inisialisasi cache driver
		$this->load->driver('cache', ['adapter' => 'file']);
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
		try {
			$this->form_validation->set_rules('nama_template', 'Nama Template', 'required|trim');
			$this->form_validation->set_rules('tipe_surat', 'Tipe Surat', 'required|trim');
			$this->form_validation->set_rules('konten', 'Konten Surat', 'required');

			if ($this->form_validation->run() === FALSE) {
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode([
						'status' => false,
						'message' => validation_errors()
					]));
			}

			$header_logo = null;
			if (!empty($_FILES['header_logo']['name'])) {
				$config['upload_path'] = './uploads/template_headers/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
				$config['max_size'] = 2048;
				$config['file_name'] = 'header_logo_' . time();

				if (!is_dir($config['upload_path'])) {
					mkdir($config['upload_path'], 0755, true);
				}

				$this->upload->initialize($config);

				if ($this->upload->do_upload('header_logo')) {
					$upload_data = $this->upload->data();
					$header_logo = 'uploads/template_headers/' . $upload_data['file_name'];
				} else {
					return $this->output
						->set_content_type('application/json')
						->set_output(json_encode([
							'status' => false,
							'message' => 'Gagal upload logo header: ' . strip_tags($this->upload->display_errors())
						]));
				}
			}

			$template_data = [
				'nama_template'  => $this->input->post('nama_template'),
				'tipe_surat'     => $this->input->post('tipe_surat'),
				'konten'         => $this->input->post('konten'),
				'use_header'     => $this->input->post('use_header') ? 1 : 0,
				'header_content' => $this->input->post('header_content'),
				'header_alamat'  => $this->input->post('header_alamat'),
				'created_at'     => date('Y-m-d H:i:s'),
				'updated_at'     => date('Y-m-d H:i:s'),
			];

			if ($header_logo) {
				$template_data['header_logo'] = $header_logo;
				$template_data['header_logo_position'] = $this->input->post('logo_position') ?: 'top';
			}

			$template_id = $this->M_template_surat->insert_template($template_data);

			if (!$template_id) {
				throw new Exception('Gagal menyimpan template ke database');
			}

			$fields = $this->input->post('fields');
			if ($fields && is_array($fields)) {
				foreach ($fields as $f) {
					if (empty($f['label']) || empty($f['nama_field']) || empty($f['tipe_input'])) continue;

					$payload = [
						'template_id'   => $template_id,
						'label'         => trim($f['label']),
						'nama_field'    => trim($f['nama_field']),
						'tipe_input'    => $f['tipe_input'],
						'placeholder'   => isset($f['placeholder']) ? trim($f['placeholder']) : '',
						'is_required'   => isset($f['is_required']) ? (bool)$f['is_required'] : true,
						'sumber_data'   => null,
						'opsi_static'   => null,
						'tabel_relasi'  => null,
						'kolom_value'   => null,
						'kolom_label'   => null,
					];

					if ($f['tipe_input'] === 'select') {
						$payload['sumber_data'] = $f['sumber_data'] ?? 'static';

						if ($payload['sumber_data'] === 'static' && !empty($f['opsi_static'])) {
							$payload['opsi_static'] = is_array($f['opsi_static'])
								? json_encode($f['opsi_static'], JSON_UNESCAPED_UNICODE)
								: json_encode(array_map('trim', explode(',', $f['opsi_static'])), JSON_UNESCAPED_UNICODE);
						} elseif ($payload['sumber_data'] === 'relasi') {
							$payload['tabel_relasi'] = $f['tabel_relasi'] ?? '';
							$payload['kolom_value']  = $f['kolom_value'] ?? '';
							$payload['kolom_label']  = $f['kolom_label'] ?? '';
						}
					}

					$this->M_template_surat->insert_field($payload);
				}
			}

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => true,
					'message' => 'Template berhasil disimpan.'
				]));
		} catch (Exception $e) {
			log_message('error', 'Error in konfigurasi_store: ' . $e->getMessage());

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => false,
					'message' => 'Terjadi kesalahan: ' . $e->getMessage()
				]));
		}
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
		try {
			$id = $this->input->post('id');
			if (!$id) throw new Exception('ID template tidak ditemukan');

			$template = $this->M_template_surat->get_template($id);
			if (!$template) throw new Exception('Template tidak ditemukan');

			$this->form_validation->set_rules('nama_template', 'Nama Template', 'required|trim');
			$this->form_validation->set_rules('tipe_surat', 'Tipe Surat', 'required|trim');
			$this->form_validation->set_rules('konten', 'Konten Surat', 'required');

			if ($this->form_validation->run() === FALSE) {
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode([
						'status' => false,
						'message' => validation_errors()
					]));
			}

			// Upload logo jika ada
			$header_logo = $template->header_logo;
			if (!empty($_FILES['header_logo']['name'])) {
				$config = [
					'upload_path'   => './uploads/template_headers/',
					'allowed_types' => 'gif|jpg|png|jpeg|svg',
					'max_size'      => 2048,
					'file_name'     => 'header_logo_' . time(),
				];

				if (!is_dir($config['upload_path'])) {
					mkdir($config['upload_path'], 0755, true);
				}

				$this->upload->initialize($config);

				if ($this->upload->do_upload('header_logo')) {
					if ($header_logo && file_exists('./' . $header_logo)) {
						unlink('./' . $header_logo);
					}
					$upload_data = $this->upload->data();
					$header_logo = 'uploads/template_headers/' . $upload_data['file_name'];
				} else {
					return $this->output
						->set_content_type('application/json')
						->set_output(json_encode([
							'status' => false,
							'message' => 'Gagal upload logo header: ' . strip_tags($this->upload->display_errors())
						]));
				}
			}

			$template_data = [
				'nama_template'  => $this->input->post('nama_template'),
				'tipe_surat'     => $this->input->post('tipe_surat'),
				'konten'         => $this->input->post('konten'),
				'use_header'     => $this->input->post('use_header') ? 1 : 0,
				'header_content' => $this->input->post('header_content'),
				'header_alamat'  => $this->input->post('header_alamat'),
				'header_logo'    => $header_logo,
				'header_logo_position' => $this->input->post('logo_position') ?: 'top',
				'updated_at'     => date('Y-m-d H:i:s'),
			];

			$this->M_template_surat->update_template($id, $template_data);

			// Reset fields
			$this->db->delete('field_template_surat', ['template_id' => $id]);

			$fields = $this->input->post('fields');
			if (is_array($fields)) {
				foreach ($fields as $f) {
					if (empty($f['label']) || empty($f['nama_field']) || empty($f['tipe_input'])) continue;

					$payload = [
						'template_id'   => $id,
						'label'         => trim($f['label']),
						'nama_field'    => trim($f['nama_field']),
						'tipe_input'    => $f['tipe_input'],
						'placeholder'   => $f['placeholder'] ?? '',
						'is_required'   => isset($f['is_required']) ? (bool)$f['is_required'] : true,
						'sumber_data'   => null,
						'opsi_static'   => null,
						'tabel_relasi'  => null,
						'kolom_value'   => null,
						'kolom_label'   => null,
					];

					if ($f['tipe_input'] === 'select') {
						$payload['sumber_data'] = $f['sumber_data'] ?? 'static';

						if ($payload['sumber_data'] === 'static' && !empty($f['opsi_static'])) {
							$payload['opsi_static'] = is_array($f['opsi_static'])
								? json_encode($f['opsi_static'], JSON_UNESCAPED_UNICODE)
								: json_encode(array_map('trim', explode(',', $f['opsi_static'])), JSON_UNESCAPED_UNICODE);
						} elseif ($payload['sumber_data'] === 'relasi') {
							$payload['tabel_relasi'] = $f['tabel_relasi'] ?? '';
							$payload['kolom_value']  = $f['kolom_value'] ?? '';
							$payload['kolom_label']  = $f['kolom_label'] ?? '';
						}
					}

					$this->M_template_surat->insert_field($payload);
				}
			}

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => true,
					'message' => 'Template berhasil diperbarui.'
				]));
		} catch (Exception $e) {
			log_message('error', 'Error in konfigurasi_update: ' . $e->getMessage());

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => false,
					'message' => 'Terjadi kesalahan: ' . $e->getMessage()
				]));
		}
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
		try {
			// Optimasi query dengan eager loading untuk mencegah N+1 query
			$this->db->select('
				ts.id,
				ts.nama_template,
				ts.tipe_surat,
				ts.konten,
				ts.use_header,
				ts.header_content,
				ts.header_logo,
				ts.header_alamat,
				ts.header_logo_position,
				GROUP_CONCAT(
					CONCAT(
						\'{"id":"\', fts.nama_field, \'",\',
						\'"label":"\', fts.label, \'",\',
						\'"type":"\', fts.tipe_input, \'",\',
						\'"placeholder":"\', IFNULL(fts.placeholder, \'\'), \'",\',
						\'"is_required":\', fts.is_required, \',\',
						\'"sumber_data":"\', IFNULL(fts.sumber_data, \'\'), \'",\',
						\'"opsi_static":"\', IFNULL(fts.opsi_static, \'\'), \'",\',
						\'"tabel_relasi":"\', IFNULL(fts.tabel_relasi, \'\'), \'",\',
						\'"kolom_value":"\', IFNULL(fts.kolom_value, \'\'), \'",\',
						\'"kolom_label":"\', IFNULL(fts.kolom_label, \'\'), \'"}\'
					) SEPARATOR \',\'
				) as fields_json
			');
			$this->db->from('template_surat ts');
			$this->db->join('field_template_surat fts', 'fts.template_id = ts.id', 'left');
			$this->db->group_by('ts.id');
			$this->db->order_by('ts.created_at', 'DESC');

			$query = $this->db->get();

			if (!$query) {
				throw new Exception('Database query failed');
			}

			$results = $query->result_array();
			$templates = [];

			foreach ($results as $row) {
				$fields = [];
				if (!empty($row['fields_json'])) {
					$fieldsArray = explode(',', $row['fields_json']);
					foreach ($fieldsArray as $fieldJson) {
						$field = json_decode($fieldJson, true);
						if ($field && is_array($field)) {
							$fields[] = $field;
						}
					}
				}

				$templates[] = [
					'id' => $row['id'],
					'name' => $row['nama_template'],
					'type' => $row['tipe_surat'],
					'content_template' => $row['konten'],
					'use_header' => (bool)$row['use_header'],
					'header_content' => $row['header_content'],
					'header_logo' => $row['header_logo'],
					'header_alamat' => $row['header_alamat'],
					'header_logo_position' => $row['header_logo_position'],
					'fields' => $fields
				];
			}

			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'success',
				'templates' => $templates
			]);
		} catch (Exception $e) {
			log_message('error', 'Error in get_all_template_surat: ' . $e->getMessage());
			header('Content-Type: application/json');
			http_response_code(500);
			echo json_encode([
				'status' => 'error',
				'message' => 'Gagal memuat template surat',
				'error' => ENVIRONMENT === 'development' ? $e->getMessage() : 'Internal server error'
			]);
		}
	}

	public function get_data_warga()
	{
		try {
			// Optimasi query dengan caching dan limit
			$cache_key = 'warga_data_' . md5('all_warga');
			$warga = $this->cache->get($cache_key);

			if (!$warga) {
				$this->db->select('
					ak.nik,
					ak.nama_lengkap,
					ak.tgl_lahir,
					ak.gender,
					ak.agama,
					ak.status_kawin,
					ak.posisi,
					ak.pekerjaan,
					dw.nomor_kk,
					dw.kepala_keluarga,
					dw.alamat,
					dw.rt,
					dw.rw,
					dw.desa,
					dw.kecamatan,
					dw.kota,
					dw.kode_pos,
					dw.propinsi
				');
				$this->db->from('anggota_keluarga ak');
				$this->db->join('data_warga dw', 'dw.nomor_kk = ak.nomor_kk', 'left');
				$this->db->where('ak.nama_lengkap IS NOT NULL');
				$this->db->where('ak.nama_lengkap !=', '');
				$this->db->order_by('ak.nama_lengkap', 'ASC');
				$this->db->limit(1000); // Limit untuk performance

				$query = $this->db->get();

				if (!$query) {
					throw new Exception('Database query failed');
				}

				$warga = $query->result_array();

				// Cache for 30 minutes
				$this->cache->save($cache_key, $warga, 1800);
			}

			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'success',
				'warga' => $warga ?: []
			]);
		} catch (Exception $e) {
			log_message('error', 'Error in get_data_warga: ' . $e->getMessage());
			header('Content-Type: application/json');
			http_response_code(500);
			echo json_encode([
				'status' => 'error',
				'message' => 'Gagal memuat data warga',
				'error' => ENVIRONMENT === 'development' ? $e->getMessage() : 'Internal server error'
			]);
		}
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

		$data = $this->input->post();

		// Validasi wajib
		if (empty($data['template_id']) || empty($data['nik'])) {
			echo json_encode(['success' => false, 'message' => 'Template dan NIK wajib diisi.']);
			return;
		}

		$filePath = $surat['file']; // default: gunakan file lama

		// Penanganan file baru (jika ada)
		$file = $_FILES['file'] ?? null;
		$uploadedNewFile = false;
		$newFilePath = '';

		if ($file && $file['error'] === UPLOAD_ERR_OK) {
			$uploadDir = FCPATH . 'uploads/surat/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0755, true);
			}

			$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
			$filename = 'surat_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
			$fullPath = $uploadDir . $filename;

			if (move_uploaded_file($file['tmp_name'], $fullPath)) {
				$uploadedNewFile = true;
				$newFilePath = 'uploads/surat/' . $filename;
			} else {
				echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file baru.']);
				return;
			}
		}

		// Siapkan payload update
		$payload = [
			'nik' => $data['nik'],
			'template_id' => $data['template_id'],
			'no_wa' => $data['no_wa'] ?? '',
			'isi_surat' => $data['isi_surat'] ?? '',
			'tgl_update' => date('Y-m-d H:i:s')
		];

		// Tambahkan file jika berhasil upload
		if ($uploadedNewFile) {
			$payload['file'] = $newFilePath;
		}

		$updated = $this->db->update('tb_new_surat', $payload, ['id_alias' => $id]);

		// Gunakan affected_rows sebagai fallback jika data tidak berubah
		$isUpdated = $updated || $this->db->affected_rows() >= 0;

		if ($isUpdated) {
			// Jika ada file baru diupload, hapus file lama
			if ($uploadedNewFile && !empty($surat['file'])) {
				$oldFilePath = FCPATH . $surat['file'];
				if (file_exists($oldFilePath)) {
					@unlink($oldFilePath); // gunakan @unlink untuk suppress warning jika file terkunci
				}
			}

			echo json_encode([
				'success' => true,
				'message' => 'Surat berhasil diperbarui.'
			]);
		} else {
			// Rollback: hapus file baru yang sudah diupload jika update gagal
			if ($uploadedNewFile && !empty($newFilePath)) {
				$newFullPath = FCPATH . $newFilePath;
				if (file_exists($newFullPath)) {
					@unlink($newFullPath);
				}
			}

			echo json_encode([
				'success' => false,
				'message' => 'Gagal memperbarui surat.'
			]);
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

		$deleted = $this->db->delete('tb_new_surat', ['id_alias' => $id]);
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

		$updated = $this->db->update('tb_new_surat', [
			'status' => $status,
			'tgl_update' => date('Y-m-d H:i:s')
		], ['id_alias' => $id_alias]);

		if ($updated) {
			$this->session->set_flashdata('success', 'Status surat berhasil diperbarui.');
		} else {
			$this->session->set_flashdata('error', 'Gagal memperbarui status surat.');
		}

		redirect('admin/buatsurat/list');
	}

	private function kirim_whatsapp($target, $pesan) {
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
		$surat = $this->M_new_surat->get_by_id_alias($id);
		if (!$surat) {
			$this->session->set_flashdata('error', 'Surat tidak ditemukan.');
			redirect('admin/buatsurat/list');
			return;
		}

		$warga = $this->db->get_where('anggota_keluarga', ['nik' => $surat['nik']])->row();
		$template = $this->db->get_where('template_surat', ['id' => $surat['template_id']])->row();
		$desa = $this->Model_warga->get_nama_desa();
		$link_pdf = base_url($surat['file']);

		// Format pesan WhatsApp
		$pesan = "Hallo, *{$warga->nama_lengkap}*\n\n" .
				"No Pengajuan : *{$surat['no_pengajuan']}*\n" .
				"Pengajuan *{$template->tipe_surat}* siap diunduh:\n" .
				"$link_pdf\n\n" .
				"Link berlaku selama 24 jam.\n" .
				"Link akan kadaluarsa pada : *" . date('d-M-Y H:i:s', strtotime('+24 hours')) . "*\n".
				"Pastikan anda sudah mendownload file surat PDF.\n\n" .
				"Terima kasih,\nStaff Pelayanan - " .
				"Desa *{$desa->nama_desa}*";

		// Kirim pesan ke WhatsApp
		$this->kirim_whatsapp($surat['no_wa'], $pesan);

		// Setelah berhasil mengirim, update status surat
		$this->db->update('tb_new_surat', [
			'status' => 'Selesai',
			'tgl_update' => date('Y-m-d H:i:s'),
			'expired_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
		], ['id_alias' => $id]);

		$this->session->set_flashdata('success', 'Surat berhasil dikirim.');
		redirect('admin/buatsurat/list');
	}
}
