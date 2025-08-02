<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_auth'); // Load the model
        $this->load->model('M_all');
        $this->load->library('form_validation'); // Load form validation library
        $this->load->library('session'); // Load session library
        $this->load->helper('captcha'); 
        
    }

    // Display login form
    public function index() {
        $data['aplikasi'] = $this->M_all->get_aplikasi();
    // Hapus CAPTCHA lama
    $files = glob('./assets/captcha/*'); // Ambil semua file di folder captcha
    $now = time();

    foreach ($files as $file) {
        if (is_file($file) && $now - filemtime($file) > 300) { // Hapus setelah 5 menit
            unlink($file);
        }
    }

    // Generate CAPTCHA baru
    $captcha_config = array(
        'word'          => rand(1000, 9999),
        'img_path'      => './assets/captcha/',
        'img_url'       => base_url('assets/captcha/'),
        'font_path'     => './system/fonts/texb.ttf',
        'img_width'     => 150,
        'img_height'    => 50,
        'expiration'    => 300,
        'word_length'   => 4,
        'font_size'     => 20,
    );

    $captcha = create_captcha($captcha_config);
    $this->session->set_userdata('captcha_word', $captcha['word']);
    $data['captcha_image'] = $captcha['image'];

    $this->load->view('admin/login', $data);
}
public function login() {
    // Cek apakah user sedang dalam waktu blokir
    $lock_time = $this->session->userdata('lock_time');
    if ($lock_time && time() < $lock_time) {
        $remaining_time = $lock_time - time();
        $this->session->set_flashdata('error', "Terlalu banyak percobaan gagal. Silakan coba lagi dalam {$remaining_time} detik.");
        redirect('admin/login');
    }

    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('captcha', 'CAPTCHA', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->index();
    } else {
        // Cek CAPTCHA
        $input_captcha = $this->input->post('captcha');
        $stored_captcha = $this->session->userdata('captcha_word');

        if ($input_captcha !== $stored_captcha) {
            $this->increment_login_attempt();
            $this->session->set_flashdata('error', 'Username, password atau CAPTCHA salah.');
            redirect('admin/login');
        }

        // Proses login
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->M_auth->check_login($username, $password);

        if ($user) {
            // Reset login attempt saat berhasil login
            $this->session->unset_userdata('login_attempts');
            $this->session->unset_userdata('lock_time');

            $session_data = [
                'user_id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'level' => $user->level,
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_data);
            
            // Simpan log aktivitas login
            $this->load->model('M_log');
            $this->M_log->log_activity($user->id, $user->username, 'login', 'User berhasil login');

            redirect('admin/dashboard');
        } else {
            $this->increment_login_attempt();
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('admin/login');
        }
    }
}

/**
 * Fungsi untuk menambah jumlah percobaan login dan mengatur waktu blokir
 */
private function increment_login_attempt() {
    $attempts = $this->session->userdata('login_attempts') ?? 0;
    $attempts++;

    if ($attempts >= 3) {
        // Blokir login selama 60 detik
        $this->session->set_userdata('lock_time', time() + 60);
        $this->session->set_flashdata('error', "Terlalu banyak percobaan gagal. Silakan coba lagi dalam 60 detik.");
    } else {
        $this->session->set_flashdata('error', "Percobaan gagal ke-{$attempts}. Maksimal 3 kali percobaan.");
    }

    $this->session->set_userdata('login_attempts', $attempts);
}

    
    

   public function logout() {
    // Ambil user_id dan username sebelum sesi dihancurkan
    $user_id = $this->session->userdata('user_id');
    $username = $this->session->userdata('username');

    // Cek apakah user ada dalam sesi sebelum logout
    if ($user_id && $username) {
        // Simpan log aktivitas logout
        $this->load->model('M_log');
        $this->M_log->log_activity($user_id, $username, 'logout', 'User berhasil logout');
    }

    // Hancurkan sesi
    $this->session->sess_destroy();

    // Redirect ke halaman login
    redirect('admin/login');
}

}