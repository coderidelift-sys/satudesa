<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    // Check user credentials
    public function check_login($username, $password) {
        // Get user by username
        $this->db->where('username', $username);
        $this->db->where('status', 'aktif'); // Only allow active users
        $user = $this->db->get('users')->row();

        // Verify password
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }
    public function get_user_by_id($user_id)
{
    return $this->db->get_where('users', ['id' => $user_id])->row();
}

}