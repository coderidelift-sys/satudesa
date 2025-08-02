<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_warga extends CI_Model {
    private $encryption_key = '123'; 

 public function get_all_warga() {
        $this->db->select('data_warga.*, COUNT(anggota_keluarga.id) AS jumlah_anggota, tb_profil_desa.nama_desa');
        $this->db->from('data_warga');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.nomor_kk = data_warga.nomor_kk', 'left');
        $this->db->join('tb_profil_desa', 'tb_profil_desa.id = data_warga.id', 'left'); // Join dengan profil desa
        $this->db->group_by('data_warga.nomor_kk');
        return $this->db->get()->result();
    }

public function get_warga_server_side($start, $length, $search, $columnName, $columnSortOrder, $akses) {
    $this->db->select('data_warga.*, COUNT(anggota_keluarga.id) AS jumlah_anggota, tb_profil_desa.nama_desa');
    $this->db->from('data_warga');
    $this->db->join('anggota_keluarga', 'anggota_keluarga.nomor_kk = data_warga.nomor_kk', 'left');
    $this->db->join('tb_profil_desa', 'tb_profil_desa.id = data_warga.id', 'left');
    
    // Apply access filter if needed
    if (!empty($akses)) {
        $this->db->where_in('data_warga.dusun', $akses);
    }
    
    if(!empty($search)) {
        $this->db->group_start();
        $this->db->like('data_warga.nomor_kk', $search);
        $this->db->or_like('data_warga.kepala_keluarga', $search);
        $this->db->or_like('data_warga.alamat', $search);
        $this->db->or_like('data_warga.rt', $search);
        $this->db->or_like('data_warga.rw', $search);
        $this->db->or_like('data_warga.desa', $search);
        $this->db->or_like('data_warga.kecamatan', $search);
        $this->db->or_like('data_warga.kota', $search);
        $this->db->or_like('data_warga.kode_pos', $search);
        $this->db->or_like('data_warga.propinsi', $search);
        $this->db->group_end();
    }
    
    $this->db->group_by('data_warga.nomor_kk');
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($length, $start);
    
    return $this->db->get()->result();
}

public function count_all_warga($akses) {
    $this->db->from('data_warga');
    
    if (!empty($akses)) {
        $this->db->where_in('dusun', $akses);
    }
    
    return $this->db->count_all_results();
}

public function count_filtered_warga($search, $akses) {
    $this->db->select('data_warga.id');
    $this->db->from('data_warga');
    
    if (!empty($akses)) {
        $this->db->where_in('dusun', $akses);
    }
    
    if(!empty($search)) {
        $this->db->group_start();
        $this->db->like('nomor_kk', $search);
        $this->db->or_like('kepala_keluarga', $search);
        $this->db->or_like('alamat', $search);
        $this->db->or_like('rt', $search);
        $this->db->or_like('rw', $search);
        $this->db->or_like('desa', $search);
        $this->db->or_like('kecamatan', $search);
        $this->db->or_like('kota', $search);
        $this->db->or_like('kode_pos', $search);
        $this->db->or_like('propinsi', $search);
        $this->db->group_end();
    }
    
    return $this->db->count_all_results();
}


    public function get_nama_desa() {
        $this->db->select('nama_desa');
        $this->db->from('tb_profil_desa');
        $this->db->limit(1); // Ambil satu data saja
        $query = $this->db->get();
        return $query->row(); // Ambil satu baris data
    }
    
    public function get_profil_desa() {
    return $this->db->get('tb_profil_desa', 1)->row(); // Mengambil seluruh kolom 1 baris
}


public function cek_nomor_kk($nomor_kk) {
    $this->db->where('nomor_kk', $nomor_kk);
    $query = $this->db->get('data_warga');
    return $query->num_rows() > 0;
}

public function get_by_alias($id_alias) {
    return $this->db->get_where('tb_pengajuan_surat', ['id_alias' => $id_alias]);
}

public function tampil_data_by_alias($id_alias) {
    $this->db->select('
        tb_pengajuan_surat.*, 
        anggota_keluarga.*, 
        data_warga.*,
        tb_profil_desa.*
    ');
    $this->db->from('tb_pengajuan_surat');
    $this->db->join('anggota_keluarga', 'tb_pengajuan_surat.nik = anggota_keluarga.nik', 'left');
    $this->db->join('data_warga', 'anggota_keluarga.nomor_kk = data_warga.nomor_kk', 'left');
    $this->db->join('tb_profil_desa', '1=1', 'left'); // Join ke tb_profil_desa

    $this->db->where('tb_pengajuan_surat.id_alias', $id_alias);
    return $this->db->get();
}


public function tampil_data_untuk_wa($id)
{
    $this->db->select('
        tb_pengajuan_surat.*, 
        anggota_keluarga.nama_lengkap, 
        anggota_keluarga.nomor_kk, 
        data_warga.alamat, 
        data_warga.desa, 
        data_warga.kecamatan, 
        data_warga.kota,
        tb_pengajuan_surat.no_wa, 
        tb_profil_desa.nama_desa, 
        tb_profil_desa.nama_kecamatan, 
        tb_profil_desa.nama_kabupaten
    ');
    $this->db->from('tb_pengajuan_surat');
    $this->db->join('anggota_keluarga', 'tb_pengajuan_surat.nik = anggota_keluarga.nik', 'left');
    $this->db->join('data_warga', 'anggota_keluarga.nomor_kk = data_warga.nomor_kk', 'left');
    $this->db->join('tb_profil_desa', '1=1', 'left'); // Hanya ada satu profil desa

    $this->db->where('tb_pengajuan_surat.id', $id);
    return $this->db->get()->row(); // Mengembalikan satu baris data
}

    // Insert data warga
    public function insert_warga($data) {
        return $this->db->insert('data_warga', $data);
    }
    
    // Get specific warga by ID
    public function get_warga_by_id($id) {
        return $this->db->get_where('data_warga', ['id' => $id])->row();
    }

    // Update data warga
    public function update_warga($id, $data) {
        return $this->db->where('id', $id)->update('data_warga', $data);
    }

    // Delete data warga
    public function delete_warga($id) {
        return $this->db->where('id', $id)->delete('data_warga');
    }

    public function get_anggota_by_kk($nomor_kk)
{
    return $this->db->get_where('data_anggota_keluarga', ['nomor_kk' => $nomor_kk])->result();
}
public function getAnggotaByKK($nomor_kk)
{
    return $this->db->get_where('anggota_keluarga', ['nomor_kk' => $nomor_kk])->result();
}
public function getWargaByKK($nomor_kk)
{
    return $this->db->get_where('data_warga', ['nomor_kk' => $nomor_kk])->row();
}

public function getKepalaKeluargaByKK($nomor_kk)
{
    // Ambil anggota keluarga dengan posisi "Kepala Rumah Tangga"
    return $this->db->get_where('anggota_keluarga', [
        'nomor_kk' => $nomor_kk,
        'posisi' => 'Kepala Rumah Tangga'
    ])->row(); // Mengembalikan hanya satu data kepala keluarga
}


public function tambah_anggota($data)
    {
        return $this->db->insert('anggota_keluarga', $data);
    }

    public function update_anggota($id, $data)
{
    $this->db->where('id', $id);
    return $this->db->update('anggota_keluarga', $data);
}

public function hapus_anggota($id)
{
    $this->db->where('id', $id);
    return $this->db->delete('anggota_keluarga');
}

public function get_skl_by_alias($id_alias) {
    $this->db->select('tb_surat_lahir.*, tb_profil_desa.nama_desa');
    $this->db->from('tb_surat_lahir');
    $this->db->join('tb_profil_desa', 'tb_profil_desa.id = 1', 'left'); // Sesuaikan kondisi join sesuai struktur databasenya
    $this->db->where('tb_surat_lahir.id_alias', $id_alias);
    return $this->db->get();
}

public function get_kepala_desa() {
    $this->db->select('*'); // Ambil semua kolom
    $this->db->from('tb_profil_desa');
    $this->db->limit(1); // Ambil hanya satu data
    return $this->db->get()->row();
}

public function get_skd_by_alias($id_alias) {
    return $this->db->get_where('tb_surat_domisili', ['id_alias' => $id_alias]);
}

public function get_ss_by_alias($id_alias) {
    return $this->db->get_where('tb_surat_status', ['id_alias' => $id_alias]);
}

public function get_skk_by_alias($id_alias) {
    $this->db->select('
        tb_surat_sukem.*, 
        anggota_keluarga.nik, anggota_keluarga.nama_lengkap AS nama, 
        anggota_keluarga.tgl_lahir, anggota_keluarga.gender, anggota_keluarga.pekerjaan, 
        data_warga.alamat, data_warga.rt, data_warga.rw, data_warga.desa, 
        data_warga.kecamatan, data_warga.kota, data_warga.kode_pos, data_warga.propinsi
    ');
    $this->db->from('tb_surat_sukem');
    $this->db->join('anggota_keluarga', 'anggota_keluarga.nik = tb_surat_sukem.nik', 'left');
    $this->db->join('data_warga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left');
    $this->db->where('tb_surat_sukem.id_alias', $id_alias);
    
    return $this->db->get();
}

public function get_sbap_by_alias($id_alias) {
    $this->db->select('
        tb_ba_penguburan.*, 
        anggota_keluarga.nik, anggota_keluarga.nama_lengkap AS nama, 
        anggota_keluarga.tgl_lahir, anggota_keluarga.gender, anggota_keluarga.pekerjaan, 
        data_warga.alamat, data_warga.rt, data_warga.rw, data_warga.desa, 
        data_warga.kecamatan, data_warga.kota, data_warga.kode_pos, data_warga.propinsi
    ');
    $this->db->from('tb_ba_penguburan');
    $this->db->join('anggota_keluarga', 'anggota_keluarga.nik = tb_ba_penguburan.nik', 'left');
    $this->db->join('data_warga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left');
    $this->db->where('tb_ba_penguburan.id_alias', $id_alias);
    
    return $this->db->get();
}



}