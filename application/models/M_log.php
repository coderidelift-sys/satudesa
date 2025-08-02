<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_log extends CI_Model {
    public function log_activity($user_id, $username, $action, $description) {
        $data = [
            'user_id' => $user_id,
            'username' => $username,
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('log_activity', $data);
    }
}
