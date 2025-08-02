<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $global_data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_all');

        // Ambil notifikasi di sini sekali saja
        $this->global_data['pending_aduan'] = $this->M_all->get_aduan_pending();
        $this->global_data['pending_notifications'] = $this->M_all->get_all_pending_notifications();
    }

    // Fungsi bantu untuk merge data global
    protected function load_view($view, $data = [], $return = FALSE)
    {
        $merged = array_merge($this->global_data, $data);

        if ($return) {
            $content = $this->load->view($view, $merged, TRUE);
            return $content;
        } else {
            $this->load->view($view, $merged);
        }
    }
}
