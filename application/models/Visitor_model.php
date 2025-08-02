<?php
class Visitor_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function countVisitor()
    {
        $ip = $this->input->ip_address();
        $date = date('Y-m-d');

        // Cek apakah IP dan tanggal hari ini sudah tercatat
        $this->db->where('ip_address', $ip);
        $this->db->where('visit_date', $date);
        $query = $this->db->get('visitor_counter');

        if ($query->num_rows() == 0) {
            // Jika belum tercatat, insert baru
            $this->db->insert('visitor_counter', [
                'ip_address' => $ip,
                'visit_date' => $date,
                'hits' => 1
            ]);
        } else {
            // Jika sudah ada, update hits
            $this->db->where('ip_address', $ip);
            $this->db->where('visit_date', $date);
            $this->db->set('hits', 'hits+1', FALSE);
            $this->db->update('visitor_counter');
        }
    }

    public function getTotalVisitors()
    {
        return $this->db->count_all('visitor_counter');
    }

    public function getTodayVisitors()
    {
        $this->db->where('visit_date', date('Y-m-d'));
        return $this->db->count_all_results('visitor_counter');
    }
}
?>
