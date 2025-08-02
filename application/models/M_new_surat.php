<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_new_surat extends CI_Model
{
	private $table = 'tb_new_surat';
	
	public function get_all($id_alias = null)
	{
		$this->db->order_by('tgl_pengajuan', 'DESC');
		$this->db->select('tb_new_surat.*, template_surat.tipe_surat AS jenis_surat');
		$this->db->join('template_surat', 'tb_new_surat.template_id = template_surat.id', 'left');
		if ($id_alias !== null) {
			$this->db->where('tb_new_surat.id_alias', $id_alias);
		}
		return $this->db->get($this->table)->result_array();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row_array();
	}

	public function get_by_id_alias($id_alias)
	{
		$this->db->select('tb_new_surat.*, template_surat.tipe_surat AS jenis_surat');
		$this->db->join('template_surat', 'tb_new_surat.template_id = template_surat.id', 'left');
		$this->db->join('anggota_keluarga', 'tb_new_surat.nik = anggota_keluarga.nik', 'inner');
		$this->db->select('anggota_keluarga.*');
		return $this->db->get_where($this->table, ['id_alias' => $id_alias])->row_array();
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}
	
	public function update($id, $data)
	{
		return $this->db->update($this->table, $data, ['id' => $id]);
	}

	public function update_status($id, $status)
	{
		return $this->db->update($this->table, ['status' => $status], ['id' => $id]);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, ['id' => $id]);
	}
	
	public function count_all()
	{
		return $this->db->count_all($this->table);
	}
}
