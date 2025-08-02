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
			ts.konten,
			fts.nama_field,
			fts.label,
			fts.tipe_input,
			fts.placeholder
		');
		$this->db->from('template_surat ts');
		$this->db->join('field_template_surat fts', 'fts.template_id = ts.id', 'left');
		$this->db->order_by('ts.created_at', 'DESC'); // Urut berdasarkan created_at (pastikan kolom ini ada)
		$this->db->order_by('fts.id', 'ASC'); // Optional: urut field dalam template
		$query = $this->db->get()->result_array();

		$out = [];
		foreach ($query as $row) {
			$tid = $row['template_id'];
			if (!isset($out[$tid])) {
				$out[$tid] = [
					'id' => $tid,
					'name' => $row['nama_template'],
					'db_table' => '',
					'content_template' => $row['konten'],
					'fields' => []
				];
			}
			if (!empty($row['nama_field'])) {
				$out[$tid]['fields'][] = [
					'id' => $row['nama_field'],
					'label' => $row['label'],
					'type' => $row['tipe_input'],
					'placeholder' => $row['placeholder']
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
