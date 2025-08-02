<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_all extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_profil_desa() {
        $query = $this->db->get('tb_profil_desa');
        return $query->row();
    }
    
        public function update_profil_desa($data) {
            return $this->db->update('tb_profil_desa', $data);
        }

    public function get_potensi() {
        $query = $this->db->get('tb_potensi');
        return $query->result(); 
    }
    
public function get_visi_misi() {
    return $this->db->get('tb_visi_misi')->row();
}

public function update_visi_misi($data) {
    return $this->db->update('tb_visi_misi', $data);
}

   public function get_aplikasi() {
    $query = $this->db->get('tb_aplikasi');
    return $query->row(); 
    }

    public function get_struktur() {
        return $this->db->get('tb_struktur')->row();
    }
    public function update_struktur($data) {
        return $this->db->update('tb_struktur', $data);
    }

    public function get_wisata() {
        $query = $this->db->get('tb_wisata');
        return $query->result(); 
    }
    
    public function get_umkm() {
        $query = $this->db->get('tb_umkm');
        return $query->result(); 
    }

     public function get_pengumuman() {
        $this->db->order_by('created_at', 'DESC'); 
        $query = $this->db->get('tb_pengumuman');
        return $query->result(); 
    }

    public function get_kegiatan() {
        $this->db->order_by('created_at', 'DESC'); 
        $query = $this->db->get('tb_kegiatan');
        return $query->result(); 
    }

    public function get_bansos() {
        $this->db->order_by('created_at', 'DESC'); 
        $query = $this->db->get('tb_bansos');
        return $query->result(); 
    }

    public function get_faq() {
        $this->db->order_by('created_at', 'DESC'); 
        $query = $this->db->get('tb_faq');
        return $query->result();
    }

    public function get_kontak_staff() {
        $this->db->order_by('created_at', 'DESC'); 
        $query = $this->db->get('tb_kontak_staff');
        return $query->result();
    }

    // MODEL UNTUK ADMIN
    public function get_aplikasi_admin() {
        return $this->db->get('tb_aplikasi')->row();
    }
    public function update_aplikasi($data) {
        $this->db->update('tb_aplikasi', $data);
        return $this->db->affected_rows();
    }

    // WISATA ADMIN
    public function get_all_wisata() {
        return $this->db->get('tb_wisata')->result();
    }
    
    public function get_wisata_by_id($id) {
        return $this->db->get_where('tb_wisata', ['id' => $id])->row();
    }

    public function insert_wisata($data) {
        return $this->db->insert('tb_wisata', $data);
    }

    public function update_wisata($id, $data) {
        return $this->db->where('id', $id)->update('tb_wisata', $data);
    }

    public function delete_wisata($id) {
        return $this->db->where('id', $id)->delete('tb_wisata');
    }

    // UMKM ADMIN
    public function get_all_umkm() {
        return $this->db->get('tb_umkm')->result();
    }

    public function get_umkm_by_id($id) {
        return $this->db->get_where('tb_umkm', ['id' => $id])->row();
    }

    public function insert_umkm($data) {
        return $this->db->insert('tb_umkm', $data);
    }

    public function update_umkm($id, $data) {
        return $this->db->where('id', $id)->update('tb_umkm', $data);
    }

    public function delete_umkm($id) {
        return $this->db->where('id', $id)->delete('tb_umkm');
    }

    // PENGUMUMAN ADMIN
    public function get_all_pengumuman() {
        return $this->db->get('tb_pengumuman')->result();
    }

    public function get_pengumuman_by_id($id) {
        return $this->db->get_where('tb_pengumuman', ['id' => $id])->row();
    }

    public function insert_pengumuman($data) {
        return $this->db->insert('tb_pengumuman', $data);
    }

    public function update_pengumuman($id, $data) {
        return $this->db->where('id', $id)->update('tb_pengumuman', $data);
    }

    public function delete_pengumuman($id) {
        return $this->db->where('id', $id)->delete('tb_pengumuman');
    }


// =====================
// JENIS HUKUM
// =====================

// Ambil semua jenis hukum
public function get_all_jenis_hukum() {
    return $this->db->get('tb_jenis_hukum')->result();
}

// Insert jenis hukum baru
public function insert_jenis_hukum($data) {
    return $this->db->insert('tb_jenis_hukum', $data);
}

// Update jenis hukum
public function update_jenis_hukum($id, $data) {
    return $this->db->where('id', $id)->update('tb_jenis_hukum', $data);
}

// Hapus jenis hukum
public function delete_jenis_hukum($id) {
    return $this->db->where('id', $id)->delete('tb_jenis_hukum');
}

// Mendapatkan satu data jenis hukum berdasarkan ID
public function get_jenis_hukum_by_id($id) {
    return $this->db->get_where('tb_jenis_hukum', ['id' => $id])->row();
}

// Mendapatkan semua daftar hukum berdasarkan ID jenis hukum
public function get_daftar_hukum_by_jenis($jenis_id) {
    // PERBAIKAN: kolom yang benar adalah 'id_jenis'
    return $this->db->get_where('tb_daftar_hukum', ['id_jenis' => $jenis_id])->result();
}

// Tambah data daftar hukum
public function insert_daftar_hukum($data) {
    return $this->db->insert('tb_daftar_hukum', $data);
}


// Ambil satu data daftar hukum
public function get_daftar_hukum_by_id($id) {
    return $this->db->get_where('tb_daftar_hukum', ['id' => $id])->row();
}

// Update daftar hukum
public function update_daftar_hukum($id, $data) {
    return $this->db->where('id', $id)->update('tb_daftar_hukum', $data);
}

// Hapus daftar hukum
public function delete_daftar_hukum($id) {
    return $this->db->delete('tb_daftar_hukum', ['id' => $id]);
}


    // KEGIATAN ADMIN
     public function get_all_kegiatan() {
        return $this->db->get('tb_kegiatan')->result();
    }

    public function get_kegiatan_by_id($id) {
        return $this->db->get_where('tb_kegiatan', ['id' => $id])->row();
    }

    public function insert_kegiatan($data) {
        return $this->db->insert('tb_kegiatan', $data);
    }

    public function update_kegiatan($id, $data) {
        return $this->db->where('id', $id)->update('tb_kegiatan', $data);
    }

    public function delete_kegiatan($id) {
        return $this->db->where('id', $id)->delete('tb_kegiatan');
    }

    // ADMIN BANSOS
    public function get_all_bansos() {
        return $this->db->get('tb_bansos')->result();
    }

    public function get_bansos_by_id($id) {
        return $this->db->get_where('tb_bansos', ['id' => $id])->row();
    }

    public function insert_bansos($data) {
        return $this->db->insert('tb_bansos', $data);
    }

    public function update_bansos($id, $data) {
        return $this->db->where('id', $id)->update('tb_bansos', $data);
    }

    public function delete_bansos($id) {
        return $this->db->where('id', $id)->delete('tb_bansos');
    }

    // ADMIN FAQ (TANYA JAWAB)
     // Get all FAQ data
     public function get_all_faq() {
        return $this->db->get('tb_faq')->result();
    }

    public function get_faq_by_id($id) {
        return $this->db->get_where('tb_faq', ['id' => $id])->row();
    }

    public function insert_faq($data) {
        return $this->db->insert('tb_faq', $data);
    }

    public function update_faq($id, $data) {
        return $this->db->where('id', $id)->update('tb_faq', $data);
    }

    public function delete_faq($id) {
        return $this->db->where('id', $id)->delete('tb_faq');
    }

    // ADMIN KONTAK
        public function get_all_kontak_staff() {
            return $this->db->get('tb_kontak_staff')->result();
        }
    
        public function get_kontak_staff_by_id($id) {
            return $this->db->get_where('tb_kontak_staff', ['id' => $id])->row();
        }
    
        public function insert_kontak_staff($data) {
            return $this->db->insert('tb_kontak_staff', $data);
        }
    
        public function update_kontak_staff($id, $data) {
            return $this->db->where('id', $id)->update('tb_kontak_staff', $data);
        }
    
        public function delete_kontak_staff($id) {
            return $this->db->where('id', $id)->delete('tb_kontak_staff');
        }
        
            // ADMIN DUSUN
        public function get_all_dusun() {
            return $this->db->get('tb_dusun')->result();
        }
    
        public function get_dusun_by_id($id) {
            return $this->db->get_where('tb_dusun', ['id' => $id])->row();
        }
    
        public function insert_dusun($data) {
            return $this->db->insert('tb_dusun', $data);
        }
    
        public function update_dusun($id, $data) {
            return $this->db->where('id', $id)->update('tb_dusun', $data);
        }
    
        public function delete_dusun($id) {
            return $this->db->where('id', $id)->delete('tb_dusun');
        }
        
        
         // ADMIN REGULASI
        public function get_all_regulasi() {
            return $this->db->get('tb_regulasi')->result();
        }
    
        public function get_regulasi_by_id($id) {
            return $this->db->get_where('tb_regulasi', ['id' => $id])->row();
        }
    
        public function insert_regulasi($data) {
            return $this->db->insert('tb_regulasi', $data);
        }
    
        public function update_regulasi($id, $data) {
            return $this->db->where('id', $id)->update('tb_regulasi', $data);
        }
    
        public function delete_regulasi($id) {
            return $this->db->where('id', $id)->delete('tb_regulasi');
        }
        
        
    // ADMIN UPDATE PEMBARUAN FITUR
    public function get_all_pembaruan() {
        return $this->db->get('tb_pembaruan')->result();
    }

    // Ambil data pembaruan berdasarkan ID
    public function get_pembaruan_by_id($id) {
        return $this->db->get_where('tb_pembaruan', ['id' => $id])->row();
    }

    // Tambah data pembaruan
    public function insert_pembaruan($data) {
        return $this->db->insert('tb_pembaruan', $data);
    }

    // Update data pembaruan
    public function update_pembaruan($id, $data) {
        return $this->db->where('id', $id)->update('tb_pembaruan', $data);
    }

    // Hapus data pembaruan
    public function delete_pembaruan($id) {
        return $this->db->where('id', $id)->delete('tb_pembaruan');
    }
    
     // ADMIN SHOPPING
    public function get_all_shopping() {
        return $this->db->get('tb_shopping')->result();
    }

    // Ambil data pembaruan berdasarkan ID
    public function get_shopping_by_id($id) {
        return $this->db->get_where('tb_shopping', ['id' => $id])->row();
    }

    // Tambah data pembaruan
    public function insert_shopping($data) {
        return $this->db->insert('tb_shopping', $data);
    }

    // Update data pembaruan
    public function update_shopping($id, $data) {
        return $this->db->where('id', $id)->update('tb_shopping', $data);
    }

    // Hapus data pembaruan
    public function delete_shopping($id) {
        return $this->db->where('id', $id)->delete('tb_shopping');
    }
    

        // ADMIN PENGATURAN
    public function get_pengaturan() {
        return $this->db->get('tb_pengaturan')->row();
    }

    public function update_pengaturan($data) {
        return $this->db->update('tb_pengaturan', $data);
    }

    // admin user management
   // Get all users
   public function get_all_users() {
    return $this->db->get('users')->result();
}

// Get user by ID
public function get_user_by_id($id) {
    return $this->db->get_where('users', ['id' => $id])->row();
}

// Insert new user
public function insert_user($data) {
    return $this->db->insert('users', $data);
}

// Update user
public function update_user($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('users', $data);
}

// Delete user
public function delete_user($id) {
    return $this->db->delete('users', ['id' => $id]);
}

    // ADMIN ADUAN
    public function get_all_aduan() {
        return $this->db->get('tb_aduan')->result();
    }

    public function get_aduan_by_id($id) {
        return $this->db->get_where('tb_aduan', ['id' => $id])->row();
    }

    public function insert_aduan($data) {
        return $this->db->insert('tb_aduan', $data);
    }

    public function update_aduan($id, $data) {
        return $this->db->where('id', $id)->update('tb_aduan', $data);
    }

    public function delete_aduan($id) {
        return $this->db->where('id', $id)->delete('tb_aduan');
    }

    public function update_status($id, $status, $jawaban, $tgl_update)
    {
        $data = [
            'status' => $status,
            'jawaban' => $jawaban,
            'tgl_update' => $tgl_update
        ];
        
        $this->db->where('id', $id);
        return $this->db->update('tb_aduan', $data);
    }
    public function count_aduan_hari_ini()
    {
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        return $this->db->count_all_results('tb_aduan');
    }

    public function count_aduan_minggu_ini()
    {
        $this->db->where('YEARWEEK(created_at, 1) =', date('YW')); 
        return $this->db->count_all_results('tb_aduan');
    }
    

    public function count_aduan_bulan_ini()
    {
        $this->db->where('YEAR(created_at)', date('Y'));
        $this->db->where('MONTH(created_at)', date('m'));
        return $this->db->count_all_results('tb_aduan');
    }

    public function count_aduan_tahun_ini()
    {
        $this->db->where('YEAR(created_at)', date('Y'));
        return $this->db->count_all_results('tb_aduan');
    }
     // Menghitung jumlah aduan dengan status Pending
     public function aduan_pending()
     {
         $this->db->where('status', 'Pending');
         return $this->db->count_all_results('tb_aduan');
     }
 
     // Menghitung jumlah aduan dengan status Diproses
     public function aduan_diproses()
     {
         $this->db->where('status', 'Diproses');
         return $this->db->count_all_results('tb_aduan');
     }
 
     // Menghitung jumlah aduan dengan status Selesai
     public function aduan_selesai()
     {
         $this->db->where('status', 'Selesai');
         return $this->db->count_all_results('tb_aduan');
     }
     public function total_aduan()
     {
         return $this->db->count_all('tb_aduan');
     }
     public function get_aduan_pending() {
        $this->db->where('status', 'pending');
        $query = $this->db->get('tb_aduan');
        return $query->result();
    }
    
        // ADMIN SURAT
        public function get_all_surat() {
            return $this->db->get('tb_pengajuan_surat')->result();
        }
        public function get_surat_by_id($id) {
            return $this->db->get_where('tb_pengajuan_surat', ['id' => $id])->row();
        }
        
        public function insert_surat($data) {
            $this->db->trans_start(); // Memulai transaksi
            
            $this->db->insert('tb_pengajuan_surat', $data);
            
            $this->db->trans_complete(); // Menyelesaikan transaksi
            
            return $this->db->trans_status(); // Mengembalikan status transaksi (TRUE jika berhasil)
        }
        
        
        public function update_surat($id, $data) {
            return $this->db->where('id', $id)->update('tb_pengajuan_surat', $data);
        }
        
        public function delete_surat($id) {
            return $this->db->where('id', $id)->delete('tb_pengajuan_surat');
        }
        
        public function update_status_surat($id, $no_surat ,$status, $keterangan, $no_wa, $tgl_update) {
            $data = [
                'status' => $status,
                'no_surat' => $no_surat,
                'keterangan' => $keterangan,
                'no_wa' => $no_wa,
                'tgl_update' => $tgl_update
            ];
        
            $this->db->where('id', $id);
            return $this->db->update('tb_pengajuan_surat', $data);
        }

        public function count_surat_by_status($status)
        {
            $query = $this->db->query("
                SELECT COUNT(*) AS total FROM tb_pengajuan_surat WHERE status = ?
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_domisili WHERE status = ?
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_lahir WHERE status = ?
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_sukem WHERE status = ?
                UNION ALL
                SELECT COUNT(*) FROM tb_ba_penguburan WHERE status = ?
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_status WHERE status = ?
            ", array($status, $status, $status, $status, $status, $status));
        
            $total = 0;
            foreach ($query->result() as $row) {
                $total += $row->total;
            }
            return $total;
        }
        

        public function total_pengajuan_surat()
        {
            $query = $this->db->query("
                SELECT COUNT(*) AS total FROM tb_pengajuan_surat
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_domisili
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_lahir
                UNION ALL
                SELECT COUNT(*) FROM tb_surat_sukem
                UNION ALL
                SELECT COUNT(*) FROM tb_ba_penguburan
                 UNION ALL
                SELECT COUNT(*) FROM tb_surat_status
            ");
        
            $total = 0;
            foreach ($query->result() as $row) {
                $total += $row->total;
            }
            return $total;
        }
        

        public function get_all_pending_notifications() {
            $query = $this->db->query("
                SELECT 'Pengajuan Surat' AS kategori, 
                       CAST(jenis_surat AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci AS jenis_surat, 
                       CAST(no_pengajuan AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci AS no_pengajuan, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci AS status, 
                       tgl_pengajuan, 
                       NULL AS no_tracking, 
                       NULL AS created_at
                FROM tb_pengajuan_surat WHERE status = 'pending'
                
                UNION ALL
                
                SELECT 'Surat Domisili' AS kategori, 
                       CAST(jenis_surat AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(no_pengajuan AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       tgl_pengajuan, 
                       NULL, 
                       NULL
                FROM tb_surat_domisili WHERE status = 'pending'
                
                UNION ALL
                
                SELECT 'Surat Lahir' AS kategori, 
                       CAST(jenis_surat AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(no_pengajuan AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       tgl_pengajuan, 
                       NULL, 
                       NULL
                FROM tb_surat_lahir WHERE status = 'pending'
                
                UNION ALL
                
                SELECT 'Surat Status' AS kategori, 
                       CAST(jenis_surat AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(no_pengajuan AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       tgl_pengajuan, 
                       NULL, 
                       NULL
                FROM tb_surat_status WHERE status = 'pending'
                
                UNION ALL
                
                SELECT 'Surat Sukem' AS kategori, 
                       CAST(jenis_surat AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(no_pengajuan AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       tgl_pengajuan, 
                       NULL, 
                       NULL
                FROM tb_surat_sukem WHERE status = 'pending'
                
                UNION ALL
                
                SELECT 'BA Penguburan' AS kategori, 
                       CAST(jenis_surat AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(no_pengajuan AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       tgl_pengajuan, 
                       NULL, 
                       NULL
                FROM tb_ba_penguburan WHERE status = 'pending'
                
                UNION ALL
                
                SELECT 'Aduan' AS kategori, 
                       NULL AS jenis_surat, 
                       NULL AS no_pengajuan, 
                       CAST(status AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci, 
                       NULL AS tgl_pengajuan,
                       CAST(no_tracking AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci,
                       created_at
                FROM tb_aduan WHERE status = 'pending'
            ");
            return $query->result();
        }
        
        
        public function get_surat_count() {
            $this->db->select('jenis_surat, COUNT(*) as total');
            $this->db->from('tb_pengajuan_surat');
            $this->db->group_by('jenis_surat');
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_metode_count()
        {
            $query = $this->db->query("
                SELECT metode, COUNT(*) AS total
                FROM (
                    SELECT metode FROM tb_pengajuan_surat
                    UNION ALL
                    SELECT metode FROM tb_surat_domisili
                    UNION ALL
                    SELECT metode FROM tb_surat_lahir
                    UNION ALL
                    SELECT metode FROM tb_surat_sukem
                    UNION ALL
                    SELECT metode FROM tb_ba_penguburan
                     UNION ALL
                    SELECT metode FROM tb_surat_status
                ) AS combined
                GROUP BY metode
            ");
            
            return $query->result_array();
        }
        

        // ADMIN POTENSI DESA
        public function get_potensi_desa() {
            $this->db->select('
                tb_potensi.*, 
                anggota_keluarga.nama_lengkap, 
                data_warga.alamat, 
                data_warga.rt, 
                data_warga.rw, 
                data_warga.desa, 
                data_warga.kecamatan, 
                data_warga.kota, 
                data_warga.kode_pos, 
                data_warga.propinsi
            ');
            $this->db->from('tb_potensi');
            $this->db->join('anggota_keluarga', 'anggota_keluarga.nik = tb_potensi.nik', 'left');
            $this->db->join('data_warga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left'); // Join ke data_warga
            $query = $this->db->get();
            return $query->result();
        }
        public function get_potensi_chart_data() {
            $this->db->select('
                data_warga.alamat,
                SUM(tb_potensi.nominal) AS total_nominal
            ');
            $this->db->from('tb_potensi');
            $this->db->join('anggota_keluarga', 'anggota_keluarga.nik = tb_potensi.nik', 'left');
            $this->db->join('data_warga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left');
            $this->db->group_by('data_warga.alamat'); // Kelompokkan berdasarkan alamat
            $this->db->order_by('total_nominal', 'DESC'); // Urutkan dari nominal tertinggi
            $query = $this->db->get();
            return $query->result();
        }
        
        public function get_detail_potensi_chart_data($alamat) {
            $this->db->select('
                tb_potensi.bidang,
                tb_potensi.objek,
                tb_potensi.penghasilan_tahunan,
                tb_potensi.satuan,
                SUM(tb_potensi.nominal) AS total_nominal
            ');
            $this->db->from('tb_potensi');
            $this->db->join('anggota_keluarga', 'anggota_keluarga.nik = tb_potensi.nik', 'left');
            $this->db->join('data_warga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left');
            $this->db->where('data_warga.alamat', $alamat); // Filter berdasarkan alamat
            $this->db->group_by('tb_potensi.bidang, tb_potensi.objek, tb_potensi.penghasilan_tahunan, tb_potensi.satuan');
            $query = $this->db->get();
            return $query->result();
        }

        public function insert_potensi($data) {
            return $this->db->insert('tb_potensi', $data);
        }
    
        public function update_potensi($id, $data) {
            return $this->db->where('id', $id)->update('tb_potensi', $data);
        }
    
        public function delete_potensi($id) {
            return $this->db->where('id', $id)->delete('tb_potensi');
        }

        public function get_nik_list() {
            $this->db->select('nik, nama_lengkap');
            $this->db->from('anggota_keluarga'); 
            $this->db->order_by('nama_lengkap','ASC');
            $query = $this->db->get();
            return $query->result();
        }
    
        // ADMIN BANNER
     public function get_all_banners() {
        return $this->db->get('tb_banner')->result();
    }

    public function get_banner_by_id($id) {
        return $this->db->get_where('tb_banner', ['id' => $id])->row();
    }

    public function insert_banner($data) {
        return $this->db->insert('tb_banner', $data);
    }

    public function update_banner($id, $data) {
        return $this->db->where('id', $id)->update('tb_banner', $data);
    }

    public function delete_banner($id) {
        return $this->db->where('id', $id)->delete('tb_banner');
    }
        
     // ADMIN SURAT
     public function get_all_suratlahir() {
        return $this->db->get('tb_surat_lahir')->result();
    }
    public function get_suratlahir_by_id($id) {
        return $this->db->get_where('tb_surat_lahir', ['id' => $id])->row();
    }
    
    public function insert_suratlahir($data) {
        $this->db->trans_start(); // Memulai transaksi
        
        $this->db->insert('tb_surat_lahir', $data);
        
        $this->db->trans_complete(); // Menyelesaikan transaksi
        
        return $this->db->trans_status(); // Mengembalikan status transaksi (TRUE jika berhasil)
    }
    
    public function update_suratlahir($id, $data) {
        return $this->db->where('id', $id)->update('tb_surat_lahir', $data);
    }
    
    public function delete_suratlahir($id) {
        return $this->db->where('id', $id)->delete('tb_surat_lahir');
    }
      
        public function get_total_warga() {
            return $this->db->count_all('anggota_keluarga'); 
        }

        public function get_total_kk() {
            return $this->db->count_all('data_warga'); 
        }

        public function get_total_laki() {
            $this->db->where('gender', 'Laki-laki'); // Filter data gender laki-laki (L)
            return $this->db->count_all_results('anggota_keluarga'); // Hitung jumlah baris
        }

        public function get_total_perempuan() {
            $this->db->where('gender', 'Perempuan'); // Filter data gender laki-laki (L)
            return $this->db->count_all_results('anggota_keluarga'); // Hitung jumlah baris
        }

        public function get_total_pekerjaan() {
            $this->db->select('pekerjaan, COUNT(*) as total');
            $this->db->group_by('pekerjaan');
            $query = $this->db->get('anggota_keluarga'); 
            return $query->result_array();
        }

        public function get_grafik_data_warga() {
            $this->db->select('data_warga.alamat, COUNT(DISTINCT data_warga.nomor_kk) as total_kk, COUNT(anggota_keluarga.id) as total_warga, SUM(CASE WHEN anggota_keluarga.gender = "Laki-laki" THEN 1 ELSE 0 END) as total_laki, SUM(CASE WHEN anggota_keluarga.gender = "Perempuan" THEN 1 ELSE 0 END) as total_perempuan');
            $this->db->from('data_warga');
            $this->db->join('anggota_keluarga', 'data_warga.nomor_kk = anggota_keluarga.nomor_kk', 'left');
            $this->db->group_by('data_warga.alamat');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_grafik_usia_warga()
        {
            $this->db->select('tgl_lahir');
            $query = $this->db->get('anggota_keluarga');
            $result = $query->result();
        
            $usia_warga = [
                'balita' => 0,
                'anak' => 0,
                'remaja' => 0,
                'pemuda' => 0,
                'dewasa' => 0,
                'lansia' => 0
            ];
        
            foreach ($result as $row) {
                $tgl_lahir = $row->tgl_lahir;
                $usia = $this->hitung_usia($tgl_lahir);
        
                if ($usia >= 1 && $usia <= 3) {
                    $usia_warga['balita']++;
                } elseif ($usia >= 4 && $usia <= 12) {
                    $usia_warga['anak']++;
                } elseif ($usia >= 13 && $usia <= 17) {
                    $usia_warga['remaja']++;
                } elseif ($usia >= 18 && $usia <= 35) {
                    $usia_warga['pemuda']++;
                } elseif ($usia >= 36 && $usia <= 50) {
                    $usia_warga['dewasa']++;
                } elseif ($usia >= 51 && $usia <= 100) {
                    $usia_warga['lansia']++;
                }
            }
        
            return $usia_warga;
        }
        
        public function hitung_usia($tgl_lahir)
        {
            $tgl_lahir = new DateTime($tgl_lahir);
            $sekarang = new DateTime();
            $usia = $sekarang->diff($tgl_lahir)->y;
            return $usia;
        }

        // ADMIN WILAYAH MAP
        public function get_all_wilayah() {
            return $this->db->get('tb_wilayah')->result();
        }
    
        public function save_wilayah($data) {
            return $this->db->insert('tb_wilayah', $data);
        }

        public function get_potensi_by_wilayah($nama_wilayah) {
            return $this->db->select('bidang, objek, penghasilan_tahunan, satuan, nominal')
                            ->from('tb_potensi')
                            ->where('nama_wilayah', $nama_wilayah)
                            ->get()
                            ->result_array();
        }

        public function get_wilayah_by_id($id) {
            return $this->db->get_where('tb_wilayah', ['id' => $id])->row();
        }
    
        public function update_wilayah($id, $data) {
            $this->db->where('id', $id);
            return $this->db->update('tb_wilayah', $data);
        }
    
        public function delete_wilayah($id) {
            $this->db->where('id', $id);
            return $this->db->delete('tb_wilayah');
        }

         // ADMIN DOMISILI
         public function get_all_domisili() {
            return $this->db->get('tb_surat_domisili')->result();
        }
        public function get_domisili_by_id($id) {
            return $this->db->get_where('tb_surat_domisili', ['id' => $id])->row();
        }
        
        public function insert_suratdomisili($data) {
            $this->db->trans_start(); // Memulai transaksi
            
            $this->db->insert('tb_surat_domisili', $data);
            
            $this->db->trans_complete(); // Menyelesaikan transaksi
            
            return $this->db->trans_status(); // Mengembalikan status transaksi (TRUE jika berhasil)
        }
        
        
        public function update_domisili($id, $data) {
            return $this->db->where('id', $id)->update('tb_surat_domisili', $data);
        }
        
        public function delete_domisili($id) {
            return $this->db->where('id', $id)->delete('tb_surat_domisili');
        }
        
        public function update_status_domisili($id, $no_surat ,$status, $keterangan, $no_wa, $tgl_update) {
            $data = [
                'status' => $status,
                'no_surat' => $no_surat,
                'no_wa' => $no_wa,
                'tgl_update' => $tgl_update
            ];
        
            $this->db->where('id', $id);
            return $this->db->update('tb_surat_domisili', $data);
        }

        // ADMIN SURAT STATUS
        public function get_all_suratstatus() {
            return $this->db->get('tb_surat_status')->result();
        }
        public function get_status_by_id($id) {
            return $this->db->get_where('tb_surat_status', ['id' => $id])->row();
        }
        
        public function insert_suratstatus($data) {
            $this->db->trans_start(); // Memulai transaksi
            
            $this->db->insert('tb_surat_status', $data);
            
            $this->db->trans_complete(); // Menyelesaikan transaksi
            
            return $this->db->trans_status(); // Mengembalikan status transaksi (TRUE jika berhasil)
        }
        
        
        public function update_surat_status($id, $data) {
            return $this->db->where('id', $id)->update('tb_surat_status', $data);
        }
        
        public function delete_status($id) {
            return $this->db->where('id', $id)->delete('tb_surat_status');
        }
        
        public function update_status_status($id, $no_surat ,$status, $keterangan, $no_wa, $tgl_update) {
            $data = [
                'status' => $status,
                'no_surat' => $no_surat,
                'no_wa' => $no_wa,
                'tgl_update' => $tgl_update
            ];
        
            $this->db->where('id', $id);
            return $this->db->update('tb_surat_status', $data);
        }
        

         // ADMIN sukem
         public function get_all_sukem() {
            $this->db->select('
                ts.id, 
                ts.id_alias, 
                ts.no_pengajuan, 
                ts.no_surat, 
                ts.nik, 
                ts.lokasi_kedukaan, 
                ts.tgl_pengajuan, 
                ts.nama_pelapor, 
                 ts.keterangan, 
                ts.hubungan, 
                ts.tgl_kedukaan, 
                ak.nama_lengkap AS nama, 
                ak.gender, 
                TIMESTAMPDIFF(YEAR, ak.tgl_lahir, CURDATE()) AS umur, 
                ak.pekerjaan, 
                dw.alamat, 
                dw.rt,
                dw.rw,
                dw.desa,
                dw.kecamatan,
                dw.kota,
                dw.propinsi,
                dw.kode_pos,
                ts.tgl_pengajuan, 
                ts.status, 
                ts.tgl_update, 
                ts.expired_at
            ');
            $this->db->from('tb_surat_sukem ts');
            $this->db->join('anggota_keluarga ak', 'ts.nik = ak.nik', 'left');
            $this->db->join('data_warga dw', 'ak.nomor_kk = dw.nomor_kk', 'left');
            $this->db->order_by('ts.tgl_pengajuan', 'DESC');
        
            return $this->db->get()->result();
        }

        
        public function get_sukem_by_id($id) {
            return $this->db->get_where('tb_surat_sukem', ['id' => $id])->row();
        }
        
        public function insert_suratsukem($data) {
            $this->db->trans_start(); // Memulai transaksi
            
            $this->db->insert('tb_surat_sukem', $data);
            
            $this->db->trans_complete(); // Menyelesaikan transaksi
            
            return $this->db->trans_status(); // Mengembalikan status transaksi (TRUE jika berhasil)
        }
        
        
        public function update_sukem($id, $data) {
            return $this->db->where('id', $id)->update('tb_surat_sukem', $data);
        }
        
        public function delete_sukem($id) {
            return $this->db->where('id', $id)->delete('tb_surat_sukem');
        }
        
        public function update_status_sukem($id, $no_surat ,$status, $keterangan, $no_wa, $tgl_update) {
            $data = [
                'status' => $status,
                'no_surat' => $no_surat,
                'no_wa' => $no_wa,
                'tgl_update' => $tgl_update
            ];
        
            $this->db->where('id', $id);
            return $this->db->update('tb_surat_sukem', $data);
        }

     // ADMIN PENGUBURAN (BAPE)
public function get_all_bape() {
    $this->db->select('
        tb.id, 
        tb.id_alias, 
        tb.no_pengajuan, 
        tb.no_surat, 
        tb.nik, 
        tb.keterangan, 
         tb.jam, 
          tb.lokasi_pemakaman, 
            tb.no_wa, 
        tb.daftar_saksi, 
        tb.tgl_pengajuan, 
        tb.status, 
        tb.tgl_update, 
        tb.expired_at, 
        ak.nama_lengkap AS nama, 
        ak.gender, 
        ak.pekerjaan, 
        dw.alamat, 
        dw.rt,
        dw.rw,
        dw.desa,
        dw.kecamatan,
        dw.kota,
        dw.propinsi,
        dw.kode_pos
    ');
    $this->db->from('tb_ba_penguburan tb');
    $this->db->join('anggota_keluarga ak', 'tb.nik = ak.nik', 'left');
    $this->db->join('data_warga dw', 'ak.nomor_kk = dw.nomor_kk', 'left');
    $this->db->order_by('tb.tgl_pengajuan', 'DESC');

    return $this->db->get()->result();
}

        public function get_bape_by_id($id) {
            return $this->db->get_where('tb_ba_penguburan', ['id' => $id])->row();
        }

        public function insert_bape($data) {
            $this->db->trans_start(); // Memulai transaksi

            $this->db->insert('tb_ba_penguburan', $data);

            $this->db->trans_complete(); // Menyelesaikan transaksi

            return $this->db->trans_status(); // Mengembalikan status transaksi (TRUE jika berhasil)
        }

        public function update_bape($id, $data) {
            return $this->db->where('id', $id)->update('tb_ba_penguburan', $data);
        }

        public function delete_bape($id) {
            return $this->db->where('id', $id)->delete('tb_ba_penguburan');
        }

        public function update_status_bape($id, $no_surat, $status, $keterangan, $no_wa, $tgl_update) {
            $data = [
                'status' => $status,
                'no_surat' => $no_surat,
                'no_wa' => $no_wa,
                'tgl_update' => $tgl_update
            ];

            $this->db->where('id', $id);
            return $this->db->update('tb_ba_penguburan', $data);
        }
        
        public function getJenisSuratCount() {
            $query = "
                SELECT jenis_surat,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                    SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) AS diproses,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS selesai,
                    COUNT(*) AS total
                FROM (
                    SELECT jenis_surat, status FROM tb_pengajuan_surat
                    UNION ALL
                    SELECT jenis_surat, status FROM tb_surat_domisili
                       UNION ALL
                      SELECT jenis_surat, status FROM tb_surat_lahir
                       UNION ALL
                      SELECT jenis_surat, status FROM tb_surat_sukem
                      UNION ALL
                      SELECT jenis_surat, status FROM tb_ba_penguburan
                       UNION ALL
                      SELECT jenis_surat, status FROM tb_surat_status
                     
                      
                ) AS combined
                GROUP BY jenis_surat
            ";
            return $this->db->query($query)->result_array();
        }
        public function getGrafikSurat()
{
    $query = $this->db->query("
        SELECT jenis_surat, 
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending, 
            SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) AS diproses, 
            SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS selesai, 
            COUNT(*) AS total
        FROM (
            SELECT jenis_surat, status FROM tb_pengajuan_surat
            UNION ALL
            SELECT jenis_surat, status FROM tb_surat_domisili
            UNION ALL
            SELECT jenis_surat, status FROM tb_surat_lahir
            UNION ALL
            SELECT jenis_surat, status FROM tb_surat_sukem
            UNION ALL
            SELECT jenis_surat, status FROM tb_ba_penguburan
             UNION ALL
            SELECT jenis_surat, status FROM tb_surat_status
        ) AS combined
        GROUP BY jenis_surat
    ");
    
    return $query->result_array();
}

// ADMIN LOG data_warga  
public function get_logdata_server_side($start, $length, $search, $columnName, $columnSortOrder) {
    $this->db->select('*');
    $this->db->from('log_activity');
    
    if(!empty($search)) {
        $this->db->group_start();
        $this->db->like('username', $search);
        $this->db->or_like('action', $search);
        $this->db->or_like('description', $search);
        $this->db->or_like('ip_address', $search);
        $this->db->or_like('user_agent', $search);
        $this->db->or_like('created_at', $search);
        $this->db->group_end();
    }
    
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($length, $start);
    
    return $this->db->get()->result();
}

public function count_all_logdata() {
    return $this->db->count_all('log_activity');
}

public function count_filtered_logdata($search) {
    $this->db->select('*');
    $this->db->from('log_activity');
    
    if(!empty($search)) {
        $this->db->group_start();
        $this->db->like('username', $search);
        $this->db->or_like('action', $search);
        $this->db->or_like('description', $search);
        $this->db->or_like('ip_address', $search);
        $this->db->or_like('user_agent', $search);
        $this->db->or_like('created_at', $search);
        $this->db->group_end();
    }
    
    return $this->db->count_all_results();
}

    
    // ADMIN ALL data_wargapublic function get_all_anggota_keluarga()
public function get_all_anggota_keluarga()
{
    $this->db->select('a.*, k.kepala_keluarga, k.alamat, k.rt, k.rw, k.desa, k.kecamatan, k.kota, k.kode_pos, k.propinsi');
    $this->db->from('anggota_keluarga a');
    $this->db->join('data_warga k', 'a.nomor_kk = k.nomor_kk', 'left');
    return $this->db->get()->result();
}

public function get_warga_server_side($start, $length, $search, $columnName, $columnSortOrder) {
    $this->db->select('a.*, k.kepala_keluarga, k.alamat, k.rt, k.rw, k.desa, k.kecamatan, k.kota, k.kode_pos, k.propinsi');
    $this->db->from('anggota_keluarga a');
    $this->db->join('data_warga k', 'a.nomor_kk = k.nomor_kk', 'left');
    
    if(!empty($search)) {
        $this->db->group_start();
        $this->db->like('a.nomor_kk', $search);
        $this->db->or_like('a.nik', $search);
        $this->db->or_like('a.nama_lengkap', $search);
        $this->db->or_like('a.tgl_lahir', $search);
        $this->db->or_like('a.gender', $search);
        $this->db->or_like('a.agama', $search);
        $this->db->or_like('a.status_kawin', $search);
        $this->db->or_like('a.posisi', $search);
        $this->db->or_like('a.pekerjaan', $search);
        $this->db->or_like('k.alamat', $search);
        $this->db->or_like('k.rt', $search);
        $this->db->or_like('k.rw', $search);
        $this->db->group_end();
    }
    
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($length, $start);
    
    return $this->db->get()->result();
}

public function count_all_warga() {
    $this->db->from('anggota_keluarga');
    return $this->db->count_all_results();
}

public function count_filtered_warga($search) {
    $this->db->select('a.*');
    $this->db->from('anggota_keluarga a');
    $this->db->join('data_warga k', 'a.nomor_kk = k.nomor_kk', 'left');
    
    if(!empty($search)) {
        $this->db->group_start();
        $this->db->like('a.nomor_kk', $search);
        $this->db->or_like('a.nik', $search);
        $this->db->or_like('a.nama_lengkap', $search);
        $this->db->or_like('a.tgl_lahir', $search);
        $this->db->or_like('a.gender', $search);
        $this->db->or_like('a.agama', $search);
        $this->db->or_like('a.status_kawin', $search);
        $this->db->or_like('a.posisi', $search);
        $this->db->or_like('a.pekerjaan', $search);
        $this->db->or_like('k.alamat', $search);
        $this->db->or_like('k.rt', $search);
        $this->db->or_like('k.rw', $search);
        $this->db->group_end();
    }
    
    return $this->db->count_all_results();
}

 // Fungsi utama bikin query datatables (reuse)
    public function get_datatables_data($config) {
        $table          = $config['table'];
        $column_order   = $config['column_order'];
        $column_search  = $config['column_search'];
        $order          = $config['order'];
        $select         = $config['select'] ?? '*';
        $join           = $config['join'] ?? [];
        $group_by       = $config['group_by'] ?? null;
        $where          = $config['where'] ?? [];

        $this->db->select($select);
        $this->db->from($table);

        // Join opsional: dukung array numerik atau associative
        foreach ($join as $j) {
            if (is_array($j)) {
                if (isset($j[0], $j[1], $j[2])) {
                    // format: [table, on, type]
                    $this->db->join($j[0], $j[1], $j[2]);
                } elseif (isset($j['table'], $j['on'], $j['type'])) {
                    // format: ['table'=>..., 'on'=>..., 'type'=>...]
                    $this->db->join($j['table'], $j['on'], $j['type']);
                }
            }
        }

        // Where opsional
        if (!empty($where)) {
            $this->db->where($where);
        }

        // Search
        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            foreach ($column_search as $i => $item) {
                if ($i == 0) {
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $this->db->group_end();
        }

        // Group By opsional
        if ($group_by) {
            $this->db->group_by($group_by);
        }

        // Order
        if (isset($_POST['order'])) {
            $colIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            if (isset($column_order[$colIndex])) {
                $this->db->order_by($column_order[$colIndex], $dir);
            }
        } else {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // Ambil hasil query datatables dengan limit & offset
    public function get_datatables_result($config) {
        $this->get_datatables_data($config);

        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    // Hitung jumlah data filter (search, where, join dll)
    public function count_filtered_datatables($config) {
        $this->get_datatables_data($config);
        return $this->db->get()->num_rows();
    }

    // Hitung semua data tanpa filter (opsional with where)
    public function count_all_datatables($table, $where = []) {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($table);
    }



        
        
    
}
