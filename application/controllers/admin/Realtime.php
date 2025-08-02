<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realtime extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_all');
    }

    public function get_realtime_notifications()
    {
        $pending_aduan = $this->M_all->get_aduan_pending();
        $pending_notifications = $this->M_all->get_all_pending_notifications();

        $result = [];

        foreach ($pending_notifications as $notif) {
            $result[] = [
                'kategori'      => $notif->kategori,
                'jenis_surat'   => $notif->jenis_surat,
                'no_tracking'   => $notif->no_tracking,
                'no_pengajuan'  => $notif->no_pengajuan,
                'status'        => $notif->status,
                'created_at'    => $notif->created_at ?? $notif->tgl_pengajuan
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'aduan_count'  => count($pending_aduan),
            'notifikasi'   => $result
        ]);
    }
}
