<?php
defined('BASEPATH') or exit('No direct script access allowed');

function check_access(...$allowed_levels)
{
    $CI = &get_instance();
    $user_level = $CI->session->userdata('level');

    // Jika user bukan salah satu dari yang diizinkan, redirect ke file HTML
    if (!in_array($user_level, $allowed_levels)) {
        redirect(base_url('assets/html/blocked.html')); // arahkan ke file HTML statis
    }
}

