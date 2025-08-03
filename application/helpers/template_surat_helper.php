<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Template Surat Helper
 * Helper untuk optimasi dan utility functions untuk sistem template surat
 */

if (!function_exists('render_template_preview')) {
    /**
     * Render template preview dengan header
     * @param object $template Template object
     * @param array $data Data untuk replace placeholder
     * @return string Rendered HTML
     */
    function render_template_preview($template, $data = []) {
        $content = '';
        
        // Add header if enabled
        if (isset($template->use_header) && $template->use_header) {
            $content .= '<div class="header-section" style="border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px;">';
            
            if (!empty($template->header_logo)) {
                $content .= '<div class="text-center mb-3">';
                $content .= '<img src="' . base_url($template->header_logo) . '" alt="Logo" style="max-height: 80px;">';
                $content .= '</div>';
            }
            
            if (!empty($template->header_alamat)) {
                $content .= '<div class="text-center mb-2" style="font-size: 14px; line-height: 1.4;">';
                $content .= '<strong>' . nl2br(htmlspecialchars($template->header_alamat)) . '</strong>';
                $content .= '</div>';
            }
            
            if (!empty($template->header_content)) {
                $content .= '<div class="text-center mb-2" style="font-size: 12px; color: #666;">';
                $content .= nl2br(htmlspecialchars($template->header_content));
                $content .= '</div>';
            }
            
            $content .= '</div>';
        }
        
        // Add main content
        $content .= $template->konten;
        
        // Replace placeholders with data
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $placeholder = '{{' . $key . '}}';
                $content = str_replace($placeholder, htmlspecialchars($value), $content);
            }
        }
        
        return $content;
    }
}

if (!function_exists('validate_template_data')) {
    /**
     * Validasi data template
     * @param array $data Data template
     * @return array Array dengan status dan pesan error
     */
    function validate_template_data($data) {
        $errors = [];
        
        if (empty($data['nama_template'])) {
            $errors[] = 'Nama template wajib diisi';
        }
        
        if (empty($data['tipe_surat'])) {
            $errors[] = 'Tipe surat wajib diisi';
        }
        
        if (empty($data['konten'])) {
            $errors[] = 'Konten surat wajib diisi';
        }
        
        // Validasi header jika diaktifkan
        if (isset($data['use_header']) && $data['use_header']) {
            if (empty($data['header_alamat']) && empty($data['header_content'])) {
                $errors[] = 'Jika header diaktifkan, minimal alamat atau konten header harus diisi';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

if (!function_exists('sanitize_template_content')) {
    /**
     * Sanitize konten template
     * @param string $content Konten template
     * @return string Konten yang sudah disanitize
     */
    function sanitize_template_content($content) {
        // Allowed HTML tags for template content
        $allowed_tags = '<p><br><div><span><strong><b><em><i><u><table><tr><td><th><thead><tbody><h1><h2><h3><h4><h5><h6><ul><ol><li>';
        
        return strip_tags($content, $allowed_tags);
    }
}

if (!function_exists('extract_template_variables')) {
    /**
     * Extract variables dari template content
     * @param string $content Konten template
     * @return array Array variable names
     */
    function extract_template_variables($content) {
        preg_match_all('/\{\{\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\}\}/', $content, $matches);
        return array_unique($matches[1]);
    }
}

if (!function_exists('log_template_activity')) {
    /**
     * Log aktivitas template
     * @param string $action Aksi yang dilakukan
     * @param int $template_id ID template
     * @param int $user_id ID user
     * @param string $details Detail tambahan
     */
    function log_template_activity($action, $template_id, $user_id, $details = '') {
        $CI =& get_instance();
        $CI->load->database();
        
        $log_data = [
            'action' => $action,
            'template_id' => $template_id,
            'user_id' => $user_id,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s'),
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent()
        ];
        
        // Create log table if not exists
        $CI->db->query("CREATE TABLE IF NOT EXISTS template_activity_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            action VARCHAR(50) NOT NULL,
            template_id INT,
            user_id INT,
            details TEXT,
            created_at DATETIME,
            ip_address VARCHAR(45),
            user_agent TEXT
        )");
        
        $CI->db->insert('template_activity_log', $log_data);
    }
}

if (!function_exists('optimize_template_query')) {
    /**
     * Optimasi query untuk menghindari N+1 problem
     * @param object $CI CodeIgniter instance
     * @return array Templates dengan fields
     */
    function optimize_template_query($CI) {
        // Single query dengan JOIN untuk menghindari N+1
        $query = $CI->db->query("
            SELECT 
                ts.id,
                ts.nama_template,
                ts.tipe_surat,
                ts.konten,
                ts.use_header,
                ts.header_content,
                ts.header_logo,
                ts.header_alamat,
                ts.created_at,
                ts.updated_at,
                GROUP_CONCAT(
                    CONCAT(
                        fts.id, '|',
                        fts.label, '|',
                        fts.nama_field, '|',
                        fts.tipe_input, '|',
                        COALESCE(fts.placeholder, ''), '|',
                        fts.is_required, '|',
                        COALESCE(fts.sumber_data, ''), '|',
                        COALESCE(fts.opsi_static, ''), '|',
                        COALESCE(fts.tabel_relasi, ''), '|',
                        COALESCE(fts.kolom_value, ''), '|',
                        COALESCE(fts.kolom_label, '')
                    ) SEPARATOR '||'
                ) as fields_data
            FROM template_surat ts
            LEFT JOIN field_template_surat fts ON ts.id = fts.template_id
            GROUP BY ts.id
            ORDER BY ts.created_at DESC
        ");
        
        $templates = [];
        foreach ($query->result() as $row) {
            $template = [
                'id' => $row->id,
                'nama_template' => $row->nama_template,
                'tipe_surat' => $row->tipe_surat,
                'konten' => $row->konten,
                'use_header' => (bool)$row->use_header,
                'header_content' => $row->header_content,
                'header_logo' => $row->header_logo,
                'header_alamat' => $row->header_alamat,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
                'fields' => []
            ];
            
            if (!empty($row->fields_data)) {
                $fields_array = explode('||', $row->fields_data);
                foreach ($fields_array as $field_str) {
                    $field_parts = explode('|', $field_str);
                    if (count($field_parts) >= 11) {
                        $template['fields'][] = [
                            'id' => $field_parts[0],
                            'label' => $field_parts[1],
                            'nama_field' => $field_parts[2],
                            'tipe_input' => $field_parts[3],
                            'placeholder' => $field_parts[4],
                            'is_required' => (bool)$field_parts[5],
                            'sumber_data' => $field_parts[6],
                            'opsi_static' => $field_parts[7],
                            'tabel_relasi' => $field_parts[8],
                            'kolom_value' => $field_parts[9],
                            'kolom_label' => $field_parts[10]
                        ];
                    }
                }
            }
            
            $templates[] = $template;
        }
        
        return $templates;
    }
}

if (!function_exists('handle_template_error')) {
    /**
     * Handle error dengan logging
     * @param string $error_message Pesan error
     * @param string $context Konteks error
     * @param array $data Data tambahan
     */
    function handle_template_error($error_message, $context = '', $data = []) {
        $CI =& get_instance();
        
        // Log error
        log_message('error', "Template Error [$context]: $error_message " . json_encode($data));
        
        // Set flashdata untuk user
        $CI->session->set_flashdata('error', $error_message);
        
        // Return JSON jika AJAX request
        if ($CI->input->is_ajax_request()) {
            $CI->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => $error_message,
                    'context' => $context
                ]));
        }
    }
}