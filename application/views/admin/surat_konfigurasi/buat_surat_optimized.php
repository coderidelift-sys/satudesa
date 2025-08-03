<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.form-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preview-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 20px;
}

.letter-preview {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 30px;
    background: white;
    min-height: 500px;
    font-family: 'Times New Roman', serif;
    line-height: 1.8;
    color: #333;
    box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
}

.letter-preview .header-section {
    text-align: center;
    border-bottom: 3px double #333;
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.letter-preview .header-logo {
    max-width: 80px;
    height: auto;
    margin-bottom: 15px;
}

.letter-preview h1, .letter-preview h2, .letter-preview h3 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.dynamic-field {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
    margin: 2px;
    font-size: 0.9em;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-success {
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-danger {
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.field-group {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.field-group:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.error-message {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 8px;
    padding: 8px 12px;
    background: #f8d7da;
    border-radius: 4px;
    border: 1px solid #f5c6cb;
}

.success-message {
    color: #155724;
    font-size: 0.875em;
    margin-top: 8px;
    padding: 8px 12px;
    background: #d4edda;
    border-radius: 4px;
    border: 1px solid #c3e6cb;
}

@media (max-width: 768px) {
    .form-section, .preview-section {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .letter-preview {
        padding: 20px;
        min-height: 300px;
    }
    
    .preview-section {
        position: static;
    }
}

.template-selector {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.template-selector:hover {
    border-color: #667eea;
}

.warga-info {
    background: #e8f4fd;
    border: 1px solid #bee5eb;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.preview-actions {
    position: sticky;
    bottom: 20px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
    margin-top: 20px;
}
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Buat Surat Baru</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Buat Surat</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Alert Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Form Section -->
                    <div class="col-lg-6">
                        <div class="form-section">
                            <h5 class="mb-4">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                Konfigurasi Surat
                            </h5>

                            <!-- Template Selection -->
                            <div class="template-selector">
                                <label for="template-select" class="form-label fw-bold">
                                    <i class="bi bi-layout-text-window me-2"></i>Pilih Template Surat
                                </label>
                                <select id="template-select" class="form-select form-select-lg">
                                    <option value="">-- Pilih Template Surat --</option>
                                </select>
                                <div class="error-message" id="error-template" style="display: none;"></div>
                            </div>

                            <!-- Warga Selection -->
                            <div class="field-group">
                                <label for="warga-select" class="form-label fw-bold">
                                    <i class="bi bi-person me-2"></i>Data Warga
                                </label>
                                <select id="warga-select" class="form-select">
                                    <option value="">-- Pilih Data Warga --</option>
                                </select>
                                <div class="error-message" id="error-warga" style="display: none;"></div>
                                
                                <!-- Warga Info Display -->
                                <div id="warga-info" class="warga-info" style="display: none;">
                                    <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Informasi Warga</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small><strong>NIK:</strong> <span id="info-nik">-</span></small><br>
                                            <small><strong>Nama:</strong> <span id="info-nama">-</span></small><br>
                                            <small><strong>Gender:</strong> <span id="info-gender">-</span></small>
                                        </div>
                                        <div class="col-md-6">
                                            <small><strong>Alamat:</strong> <span id="info-alamat">-</span></small><br>
                                            <small><strong>RT/RW:</strong> <span id="info-rt-rw">-</span></small><br>
                                            <small><strong>No. KK:</strong> <span id="info-kk">-</span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp Number -->
                            <div class="field-group">
                                <label for="no_wa" class="form-label fw-bold">
                                    <i class="bi bi-whatsapp text-success me-2"></i>Nomor WhatsApp
                                </label>
                                <input type="text" class="form-control" id="no_wa" placeholder="Contoh: 08123456789">
                                <small class="text-muted">Nomor WhatsApp untuk pengiriman notifikasi surat</small>
                                <div class="error-message" id="error-wa" style="display: none;"></div>
                            </div>

                            <!-- Dynamic Form Fields -->
                            <div id="dynamic-form-fields"></div>

                            <!-- File Upload (Optional) -->
                            <div class="field-group">
                                <label for="file-upload" class="form-label fw-bold">
                                    <i class="bi bi-paperclip me-2"></i>Lampiran (Opsional)
                                </label>
                                <input type="file" class="form-control" id="file-upload" accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, PNG. Maksimal 5MB</small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="preview-actions">
                                <div class="d-flex gap-3 justify-content-center">
                                    <button id="preview-btn" class="btn btn-outline-info" disabled>
                                        <i class="bi bi-eye me-2"></i>Preview
                                    </button>
                                    <button id="confirm-letter" class="btn btn-outline-success" disabled>
                                        <i class="bi bi-check-circle me-2"></i>Simpan Surat
                                    </button>
                                    <button id="download-pdf" class="btn btn-outline-danger" disabled>
                                        <i class="bi bi-file-pdf me-2"></i>Unduh PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="col-lg-6">
                        <div class="preview-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-eye text-primary me-2"></i>Preview Surat
                                </h5>
                                <button class="btn btn-sm btn-outline-secondary" onclick="refreshPreview()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                                </button>
                            </div>
                            
                            <div id="letter-preview" class="letter-preview">
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">Pilih template dan isi data untuk melihat preview surat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h6 class="mb-2">Memproses Surat...</h6>
            <p class="text-muted mb-0">Mohon tunggu sebentar</p>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function() {
    // Global variables
    const $templateSelect = $('#template-select');
    const $wargaSelect = $('#warga-select');
    const $formFields = $('#dynamic-form-fields');
    const $preview = $('#letter-preview');
    const $noWa = $('#no_wa');
    const $confirmBtn = $('#confirm-letter');
    const $downloadBtn = $('#download-pdf');
    const $previewBtn = $('#preview-btn');
    const $loadingOverlay = $('#loadingOverlay');

    let templates = [];
    let wargaList = [];
    let selectedWarga = null;
    let selectedTemplate = null;

    // Initialize
    init();

    function init() {
        fetchTemplates();
        fetchWargaData();
        setupEventListeners();
    }

    function setupEventListeners() {
        $templateSelect.on('change', handleTemplateChange);
        $wargaSelect.on('change', handleWargaChange);
        $noWa.on('input', validateForm);
        $confirmBtn.on('click', handleConfirmLetter);
        $downloadBtn.on('click', handleDownloadPDF);
        $previewBtn.on('click', refreshPreview);
        
        // Auto-update preview on field changes
        $(document).on('input change', '#dynamic-form-fields input, #dynamic-form-fields select, #dynamic-form-fields textarea', 
            debounce(updatePreview, 500));
    }

    function fetchTemplates() {
        showLoading('Memuat template surat...');
        
        $.ajax({
            url: '<?= base_url('admin/buatsurat/get_all_template_surat') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (!response || !response.templates) {
                    showError('Tidak ada template surat yang ditemukan.');
                    return;
                }

                templates = response.templates;
                populateTemplateSelect();
            },
            error: function(xhr, status, error) {
                hideLoading();
                console.error('Error fetching templates:', error);
                showError('Gagal memuat template surat. Silakan refresh halaman.');
            }
        });
    }

    function fetchWargaData() {
        $.ajax({
            url: '<?= base_url('admin/buatsurat/get_data_warga') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.warga) {
                    wargaList = response.warga;
                    populateWargaSelect();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching warga data:', error);
                showError('Gagal memuat data warga.');
            }
        });
    }

    function populateTemplateSelect() {
        $templateSelect.empty().append('<option value="">-- Pilih Template Surat --</option>');
        
        templates.forEach(template => {
            $templateSelect.append(
                `<option value="${template.id}" data-type="${template.type || ''}">${template.name}</option>`
            );
        });
    }

    function populateWargaSelect() {
        $wargaSelect.empty().append('<option value="">-- Pilih Data Warga --</option>');
        
        wargaList.forEach(warga => {
            const displayName = `${warga.nama_lengkap} (${warga.nik})`;
            $wargaSelect.append(
                `<option value="${warga.nik}" data-warga='${JSON.stringify(warga)}'>${displayName}</option>`
            );
        });
    }

    function handleTemplateChange() {
        const templateId = $templateSelect.val();
        clearErrors();
        
        if (!templateId) {
            selectedTemplate = null;
            $formFields.empty();
            updatePreview();
            validateForm();
            return;
        }

        selectedTemplate = templates.find(t => t.id == templateId);
        if (!selectedTemplate) {
            showError('Template tidak ditemukan.', 'error-template');
            return;
        }

        generateFormFields();
        updatePreview();
        validateForm();
    }

    function handleWargaChange() {
        const nik = $wargaSelect.val();
        clearErrors();
        
        if (!nik) {
            selectedWarga = null;
            $('#warga-info').hide();
            updatePreview();
            validateForm();
            return;
        }

        const wargaData = $wargaSelect.find(':selected').data('warga');
        if (wargaData) {
            selectedWarga = wargaData;
            displayWargaInfo(wargaData);
            
            // Auto-fill WhatsApp if available
            if (wargaData.no_wa) {
                $noWa.val(wargaData.no_wa);
            }
            
            updatePreview();
            validateForm();
        }
    }

    function displayWargaInfo(warga) {
        $('#info-nik').text(warga.nik || '-');
        $('#info-nama').text(warga.nama_lengkap || '-');
        $('#info-gender').text(warga.gender || '-');
        $('#info-alamat').text(warga.alamat || '-');
        $('#info-rt-rw').text(`${warga.rt || '-'}/${warga.rw || '-'}`);
        $('#info-kk').text(warga.nomor_kk || '-');
        $('#warga-info').slideDown();
    }

    function generateFormFields() {
        if (!selectedTemplate || !selectedTemplate.fields) {
            $formFields.empty();
            return;
        }

        let fieldsHtml = '<div class="field-group"><h6 class="mb-3"><i class="bi bi-list-ul me-2"></i>Data Surat</h6>';
        
        selectedTemplate.fields.forEach((field, index) => {
            fieldsHtml += generateFieldHtml(field, index);
        });
        
        fieldsHtml += '</div>';
        $formFields.html(fieldsHtml);
    }

    function generateFieldHtml(field, index) {
        const isRequired = field.is_required ? 'required' : '';
        const requiredMark = field.is_required ? '<span class="text-danger">*</span>' : '';
        
        let fieldHtml = `
            <div class="mb-3">
                <label class="form-label fw-semibold">${field.label} ${requiredMark}</label>`;

        switch (field.type) {
            case 'textarea':
                fieldHtml += `<textarea name="field_${field.id}" class="form-control" rows="3" placeholder="${field.placeholder || ''}" ${isRequired}></textarea>`;
                break;
            case 'date':
                fieldHtml += `<input type="date" name="field_${field.id}" class="form-control" ${isRequired}>`;
                break;
            case 'select':
                fieldHtml += `<select name="field_${field.id}" class="form-select" ${isRequired}>
                    <option value="">-- Pilih ${field.label} --</option>`;
                
                if (field.sumber_data === 'static' && field.opsi_static) {
                    try {
                        const options = JSON.parse(field.opsi_static);
                        options.forEach(option => {
                            fieldHtml += `<option value="${option}">${option}</option>`;
                        });
                    } catch (e) {
                        console.error('Error parsing static options:', e);
                    }
                }
                fieldHtml += `</select>`;
                break;
            default:
                fieldHtml += `<input type="text" name="field_${field.id}" class="form-control" placeholder="${field.placeholder || ''}" ${isRequired}>`;
        }

        fieldHtml += `</div>`;
        return fieldHtml;
    }

    function updatePreview() {
        if (!selectedTemplate) {
            $preview.html(`
                <div class="text-center text-muted py-5">
                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="mt-3 mb-0">Pilih template untuk melihat preview</p>
                </div>
            `);
            return;
        }

        let content = selectedTemplate.content_template || '';
        
        // Add header if enabled
        if (selectedTemplate.use_header) {
            let headerContent = '<div class="header-section">';
            
            if (selectedTemplate.header_logo) {
                headerContent += `<img src="<?= base_url() ?>${selectedTemplate.header_logo}" class="header-logo" alt="Logo">`;
            }
            
            headerContent += '<h4 class="mb-2">PEMERINTAH DESA [NAMA_DESA]</h4>';
            
            if (selectedTemplate.header_alamat) {
                headerContent += `<p class="mb-1">${selectedTemplate.header_alamat}</p>`;
            }
            
            if (selectedTemplate.header_content) {
                headerContent += `<p class="mb-0">${selectedTemplate.header_content}</p>`;
            }
            
            headerContent += '</div>';
            content = headerContent + content;
        }

        // Replace placeholders with form data or warga data
        content = replacePlaceholders(content);
        
        $preview.html(content);
    }

    function replacePlaceholders(content) {
        if (!content) return '';
        
        // Replace with warga data
        if (selectedWarga) {
            Object.keys(selectedWarga).forEach(key => {
                const regex = new RegExp(`{{\\s*${key}\\s*}}`, 'g');
                content = content.replace(regex, selectedWarga[key] || '');
            });
        }
        
        // Replace with form field data
        $('#dynamic-form-fields input, #dynamic-form-fields select, #dynamic-form-fields textarea').each(function() {
            const fieldName = $(this).attr('name');
            if (fieldName && fieldName.startsWith('field_')) {
                const fieldId = fieldName.replace('field_', '');
                const value = $(this).val() || '';
                const regex = new RegExp(`{{\\s*${fieldId}\\s*}}`, 'g');
                content = content.replace(regex, value);
            }
        });
        
        // Replace remaining placeholders with dynamic field indicators
        content = content.replace(/{{(\s*\w+\s*)}}/g, '<span class="dynamic-field">$1</span>');
        
        return content;
    }

    function validateForm() {
        clearErrors();
        
        let isValid = true;
        
        if (!$templateSelect.val()) {
            showError('Silakan pilih template surat', 'error-template');
            isValid = false;
        }
        
        if (!$wargaSelect.val()) {
            showError('Silakan pilih data warga', 'error-warga');
            isValid = false;
        }
        
        if (!$noWa.val().trim()) {
            showError('Nomor WhatsApp wajib diisi', 'error-wa');
            isValid = false;
        }
        
        // Validate required fields
        $('#dynamic-form-fields input[required], #dynamic-form-fields select[required], #dynamic-form-fields textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
            }
        });
        
        // Enable/disable buttons
        $confirmBtn.prop('disabled', !isValid);
        $downloadBtn.prop('disabled', !isValid);
        $previewBtn.prop('disabled', !selectedTemplate);
        
        return isValid;
    }

    function handleConfirmLetter() {
        if (!validateForm()) {
            showError('Mohon lengkapi semua data yang diperlukan.');
            return;
        }

        showLoading('Menyimpan surat...');
        
        const formData = new FormData();
        formData.append('template_id', $templateSelect.val());
        formData.append('nik', $wargaSelect.val());
        formData.append('no_wa', $noWa.val());
        formData.append('isi_surat', $preview.html());
        
        // Add dynamic fields
        $('#dynamic-form-fields input, #dynamic-form-fields select, #dynamic-form-fields textarea').each(function() {
            const fieldName = $(this).attr('name');
            if (fieldName) {
                formData.append(fieldName, $(this).val());
            }
        });
        
        // Add file if selected
        const fileInput = document.getElementById('file-upload');
        if (fileInput.files.length > 0) {
            formData.append('file', fileInput.files[0]);
        }
        
        $.ajax({
            url: '<?= base_url('admin/buatsurat/simpan_surat') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoading();
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reset form or redirect
                        window.location.href = '<?= base_url('admin/buatsurat/list') ?>';
                    });
                } else {
                    showError(response.message || 'Gagal menyimpan surat.');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                console.error('Error saving letter:', error);
                showError('Terjadi kesalahan saat menyimpan surat.');
            }
        });
    }

    function handleDownloadPDF() {
        if (!validateForm()) {
            showError('Mohon lengkapi semua data yang diperlukan.');
            return;
        }

        showLoading('Membuat PDF...');
        
        const element = document.getElementById('letter-preview');
        const opt = {
            margin: 1,
            filename: `surat_${selectedTemplate.name}_${Date.now()}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2canvas(element, {
            scale: 2,
            useCORS: true,
            allowTaint: true
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            
            const imgWidth = 210;
            const pageHeight = 295;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            let heightLeft = imgHeight;
            
            let position = 0;
            
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
            
            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }
            
            pdf.save(opt.filename);
            hideLoading();
            
            Swal.fire({
                icon: 'success',
                title: 'PDF Berhasil Dibuat!',
                text: 'File PDF telah diunduh ke perangkat Anda.',
                timer: 3000,
                showConfirmButton: false
            });
        }).catch(error => {
            hideLoading();
            console.error('Error generating PDF:', error);
            showError('Gagal membuat PDF. Silakan coba lagi.');
        });
    }

    function refreshPreview() {
        updatePreview();
        
        Swal.fire({
            icon: 'info',
            title: 'Preview Diperbarui',
            text: 'Preview surat telah diperbarui dengan data terbaru.',
            timer: 2000,
            showConfirmButton: false
        });
    }

    function showLoading(message = 'Memproses...') {
        $loadingOverlay.find('h6').text(message);
        $loadingOverlay.show();
    }

    function hideLoading() {
        $loadingOverlay.hide();
    }

    function showError(message, elementId = null) {
        if (elementId) {
            $(`#${elementId}`).text(message).show();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
        }
    }

    function clearErrors() {
        $('.error-message').hide().text('');
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
</script>