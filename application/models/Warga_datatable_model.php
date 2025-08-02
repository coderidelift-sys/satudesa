<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warga_datatable_model extends CI_Model {

    // Nama tabel dan kolom untuk order/search
    var $table = 'data_warga';

    // Prefix semua kolom dengan nama tabel untuk menghindari konflik
    var $column_order = [
        'data_warga.nomor_kk',
        'data_warga.kepala_keluarga',
        'data_warga.alamat',
        'data_warga.rt',
        'data_warga.rw',
        'data_warga.desa',
        'data_warga.kecamatan',
        'data_warga.kota',
        'data_warga.kode_pos',
        'data_warga.propinsi'
    ];

    var $column_search = [
        'data_warga.nomor_kk',
        'data_warga.kepala_keluarga',
        'data_warga.alamat',
        'data_warga.desa',
        'data_warga.kecamatan',
        'data_warga.kota'
    ];

    var $order = ['data_warga.id' => 'desc']; // default order

    private function _get_datatables_query() {
        $this->db->select('data_warga.*, COUNT(anggota_keluarga.id) AS jumlah_anggota');
        $this->db->from($this->table);
        $this->db->join('anggota_keluarga', 'anggota_keluarga.nomor_kk = data_warga.nomor_kk', 'left');
        $this->db->group_by('data_warga.nomor_kk');

        // Search
        if (isset($_POST['search']['value']) && $_POST['search']['value'] !== '') {
            $this->db->group_start();
            foreach ($this->column_search as $i => $item) {
                if ($i === 0) {
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $this->db->group_end();
        }

        // Order
        if (isset($_POST['order'])) {
            $colIndex = intval($_POST['order'][0]['column']);
            $colDir   = $_POST['order'][0]['dir'];
            $mappedIndex = $colIndex - 1; // kolom pertama (No) tidak dihitung di column_order

            if (isset($this->column_order[$mappedIndex])) {
                $this->db->order_by($this->column_order[$mappedIndex], $colDir);
            } else {
                $this->db->order_by(key($this->order), $this->order[key($this->order)]);
            }
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables() {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}
