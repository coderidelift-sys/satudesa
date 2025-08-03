<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_template_surat extends CI_Model
{
	private $table = 'template_surat';
	private $field_table = 'field_template_surat';

	// ======== TEMPLATE SURAT ========

	public function get_all_templates()
	{
		return $this->db->order_by('created_at', 'DESC')->get($this->table)->result();
	}

	public function get_template($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row();
	}

	public function insert_template($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id(); // return inserted ID
	}

	public function update_template($id, $data)
	{
		return $this->db->update($this->table, $data, ['id' => $id]);
	}

	public function delete_template($id)
	{
		return $this->db->delete($this->table, ['id' => $id]);
	}

	// ======== FIELD TEMPLATE ========

	public function get_fields_by_template($template_id)
	{
		return $this->db->get_where($this->field_table, ['template_id' => $template_id])->result();
	}

	public function get_field($id)
	{
		return $this->db->get_where($this->field_table, ['id' => $id])->row();
	}

	public function insert_field($data)
	{
		$this->db->insert($this->field_table, $data);
		return $this->db->insert_id();
	}

	public function update_field($id, $data)
	{
		return $this->db->update($this->field_table, $data, ['id' => $id]);
	}

	public function delete_field($id)
	{
		return $this->db->delete($this->field_table, ['id' => $id]);
	}

	public function delete_fields_by_template($template_id)
	{
		return $this->db->delete($this->field_table, ['template_id' => $template_id]);
	}

	public function get_all_templates_formatted()
	{
		$this->db->select('
			ts.id AS template_id,
			ts.nama_template,
			ts.tipe_surat,
			ts.konten,
			ts.use_header,
			ts.header_content,
			ts.header_logo,
			ts.header_alamat,
			fts.nama_field,
			fts.label,
			fts.tipe_input,
			fts.placeholder,
			fts.is_required,
			fts.sumber_data,
			fts.opsi_static,
			fts.tabel_relasi,
			fts.kolom_value,
			fts.kolom_label
		');
		$this->db->from('template_surat ts');
		$this->db->join('field_template_surat fts', 'fts.template_id = ts.id', 'left');
		$this->db->order_by('ts.created_at', 'DESC');
		$this->db->order_by('fts.id', 'ASC');
		$query = $this->db->get()->result_array();

		$out = [];
		foreach ($query as $row) {
			$tid = $row['template_id'];
			if (!isset($out[$tid])) {
				$out[$tid] = [
					'id' => $tid,
					'name' => $row['nama_template'],
					'type' => $row['tipe_surat'],
					'db_table' => '',
					'content_template' => $row['konten'],
					'use_header' => (bool)$row['use_header'],
					'header_content' => $row['header_content'],
					'header_logo' => $row['header_logo'],
					'header_alamat' => $row['header_alamat'],
					'fields' => []
				];
			}
			if (!empty($row['nama_field'])) {
				$out[$tid]['fields'][] = [
					'id' => $row['nama_field'],
					'label' => $row['label'],
					'type' => $row['tipe_input'],
					'placeholder' => $row['placeholder'],
					'is_required' => (bool)$row['is_required'],
					'sumber_data' => $row['sumber_data'],
					'opsi_static' => $row['opsi_static'],
					'tabel_relasi' => $row['tabel_relasi'],
					'kolom_value' => $row['kolom_value'],
					'kolom_label' => $row['kolom_label']
				];
			}
		}

		return array_values($out);
	}

	public function get_all_warga($nomor_kk = null)
	{
		$this->db->select('
			ak.id AS anggota_id,
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

		if (!empty($nomor_kk)) {
			$this->db->where('ak.nomor_kk', $nomor_kk);
		}

		$this->db->order_by('ak.nomor_kk ASC, FIELD(ak.posisi, "Kepala Rumah Tangga", "Istri", "Anak"), ak.nama_lengkap ASC');

		$query = $this->db->get()->result_array();
		return $query;
	}
}
