<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aksesdata extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_all');
        $this->load->database();
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }

        $data['aplikasi'] = $this->M_all->get_aplikasi();
        $data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
        $data['status_akses'] = $this->db->get_where('tb_pengaturan', ['id' => 1])->row()->status_akses ?? 0;

        $this->load->view('admin/layouts/header', $data);
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/aksesdata', $data);
        $this->load->view('admin/layouts/footer');
    }

    // Method untuk Buka Akses
    public function buka_akses() {
        $key_access = trim($this->input->post('kunci_akses', true)); // Hapus spasi di awal/akhir
        $valid_key = "123456"; // Ganti dengan key yang valid

        // Validasi input
        if (!$this->input->post()) { 
            $this->session->set_flashdata('error', 'Form tidak dikirim dengan benar!');
        } elseif (empty($key_access)) {
            $this->session->set_flashdata('error', 'Key akses tidak boleh kosong!');
        } elseif ($key_access === $valid_key) {
            // Update status akses di database
            $this->db->update('tb_pengaturan', ['status_akses' => 1], ['id' => 1]);

            // Dekripsi data
            $this->decrypt_data($key_access);
            $this->session->set_flashdata('success', 'Akses dibuka & data didekripsi!');
        } else {
            $this->session->set_flashdata('error', 'Key akses salah!');
        }

        redirect('admin/aksesdata');
    }

    // Method untuk Kunci Akses
    public function kunci_akses() {
        $key_access = trim($this->input->post('kunci_akses', true)); // Hapus spasi di awal/akhir
        $valid_key = "123456"; // Ganti dengan key yang valid

        // Validasi input
        if (!$this->input->post()) { 
            $this->session->set_flashdata('error', 'Form tidak dikirim dengan benar!');
        } elseif (empty($key_access)) {
            $this->session->set_flashdata('error', 'Key akses tidak boleh kosong!');
        } elseif ($key_access === $valid_key) {
            // Update status akses di database
            $this->db->update('tb_pengaturan', ['status_akses' => 0], ['id' => 1]);

            // Enkripsi data
            $this->encrypt_data($key_access);
            $this->session->set_flashdata('success', 'Akses dikunci & data dienkripsi!');
        } else {
            $this->session->set_flashdata('error', 'Key akses salah!');
        }

        redirect('admin/aksesdata');
    }

    private function get_tables_with_columns($columns = ['nik', 'nomor_kk']) {
        $tables = [];
        $query = $this->db->query("SHOW TABLES");
    
        foreach ($query->result_array() as $row) {
            $table_name = array_values($row)[0]; // Ambil nama tabel
            $columns_query = $this->db->query("SHOW COLUMNS FROM $table_name");
            $table_columns = array_column($columns_query->result_array(), 'Field');
    
            // Cari kolom yang cocok dalam tabel
            $matching_columns = array_intersect($columns, $table_columns);
    
            // Jika ada kolom yang cocok, tambahkan ke hasil
            if (!empty($matching_columns)) {
                $tables[$table_name] = array_values($matching_columns);
            }
        }
        
        return $tables;
    }

    private function encrypt_data($key_access) {
        $tables = $this->get_tables_with_columns();
        
        foreach ($tables as $table => $columns) {
            $setClause = [];
            $params = [];
    
            foreach ($columns as $column) {
                $setClause[] = "`$column` = AES_ENCRYPT(`$column`, ?)";
                $params[] = $key_access;
            }
    
            if (!empty($setClause)) {
                $query = "UPDATE `$table` SET " . implode(", ", $setClause);
                $this->db->query($query, $params);
            }
        }
    }
    
    private function get_tables_with_values($columns = ['nik', 'nomor_kk']) {
        $tables = [];
        $query = $this->db->query("SHOW TABLES");
    
        foreach ($query->result_array() as $row) {
            $table_name = array_values($row)[0]; // Ambil nama tabel
            $columns_query = $this->db->query("SHOW COLUMNS FROM `$table_name`");
            $table_columns = array_column($columns_query->result_array(), 'Field');
    
            // Cari kolom yang cocok dalam tabel
            $matching_columns = array_intersect($columns, $table_columns);
    
            if (!empty($matching_columns)) {
                // Ambil data dari kolom yang cocok
                $data_query = $this->db->query("SELECT " . implode(',', $matching_columns) . " FROM `$table_name`");
                $table_data = $data_query->result_array();
    
                $tables[$table_name] = [
                    'columns' => array_values($matching_columns),
                    'data' => $table_data
                ];
            }
        }
    
        return $tables;
    }


    
    private function decrypt_data($key_access) {
        $tables = $this->get_tables_with_values();
    
        foreach ($tables as $table => $table_info) {
            $columns = $table_info['columns'];
            $data = $table_info['data'];
    
            foreach ($data as $row) {
                $setClause = [];
                $params = [];
                $whereClause = [];
    
                foreach ($columns as $column) {
                    if (!empty($row[$column])) {
                        // Dekripsi langsung pada nilai di dalam kolom
                        $setClause[] = "`$column` = CAST(AES_DECRYPT(`$column`, ?) AS CHAR)";
                        $params[] = $key_access;
    
                        // WHERE untuk memastikan hanya data terenkripsi yang didekripsi
                        $whereClause[] = "`$column` IS NOT NULL";
                    }
                }
    
                if (!empty($setClause) && !empty($whereClause)) {
                    $query = "UPDATE `$table` SET " . implode(", ", $setClause) . " WHERE " . implode(" AND ", $whereClause);
                    
                    // Debug query sebelum dieksekusi (hapus setelah pengecekan)
                    var_dump($query, $params);
                    die();
    
                    $this->db->query($query, $params);
                }
            }
        }
    }








}