<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<style>
.header-section {
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin: 15px 0;
    transition: all 0.3s ease;
}

.header-section.active {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.preview-container {
    max-height: 600px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.template-preview {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
    color: #333;
}

.template-preview .header-content {
    text-align: center;
    border-bottom: 2px solid #333;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.template-preview .header-logo {
    max-width: 80px;
    height: auto;
    margin-bottom: 10px;
}

.field-preview {
    background-color: #fff3cd;
    border: 1px dashed #ffc107;
    padding: 5px 10px;
    border-radius: 4px;
    display: inline-block;
    margin: 2px;
    font-weight: bold;
    color: #856404;
}

.btn-preview {
    position: sticky;
    top: 10px;
    z-index: 10;
}

.error-message {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 5px;
}

.success-message {
    color: #28a745;
    font-size: 0.875em;
    margin-top: 5px;
}

.loading-spinner {
    display: none;
    text-align: center;
    padding: 20px;
}

.field-container {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #f8f9fa;
    position: relative;
}

.field-container .remove-field {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    font-size: 12px;
    cursor: pointer;
}

.field-container .remove-field:hover {
    background: #c82333;
}

@media (max-width: 768px) {
    .preview-container {
        max-height: 400px;
    }
    
    .header-section {
        padding: 15px;
    }
}
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Konfigurasi Template Surat</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Konfigurasi Template Surat</li>
            </ol>
        </nav>
    </div>

    <!-- Modal Tambah/Edit Template -->
    <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="templateForm" enctype="multipart/form-data" method="post">
                <input type="hidden" name="id" id="templateId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="templateModalLabel">Tambah Template Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Form Section -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Template <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_template" class="form-control" required>
                                    <div class="error-message" id="error-nama-template"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tipe Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="tipe_surat" class="form-control" required>
                                    <div class="error-message" id="error-tipe-surat"></div>
                                </div>

                                <!-- Header Configuration -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="use_header" id="useHeaderCheck" value="1">
                                        <label class="form-check-label" for="useHeaderCheck">
                                            <strong>Gunakan Header Surat</strong>
                                        </label>
                                    </div>
                                </div>

                                <div class="header-section" id="headerSection" style="display: none;">
                                    <h6 class="mb-3"><i class="bi bi-file-earmark-text"></i> Konfigurasi Header</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Logo Header</label>
                                        <input type="file" name="header_logo" class="form-control" accept="image/*">
                                        <small class="text-muted">Format: JPG, PNG, SVG. Maksimal 2MB.</small>
                                        <div id="current-logo" style="display: none;">
                                            <img id="current-logo-img" src="" alt="Current Logo" style="max-width: 100px; margin-top: 10px;">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Alamat Surat</label>
                                        <textarea name="header_alamat" class="form-control" rows="3" placeholder="Alamat lengkap desa/instansi"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Konten Header Tambahan</label>
                                        <textarea name="header_content" class="form-control" rows="2" placeholder="Konten tambahan untuk header (opsional)"></textarea>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Konten Surat <span class="text-danger">*</span></label>
                                    <textarea name="konten" id="kontenEditor" class="form-control" rows="8" required></textarea>
                                    <small class="text-muted">Gunakan {{nama_field}} untuk placeholder dinamis</small>
                                    <div class="error-message" id="error-konten"></div>
                                </div>

                                <!-- Dynamic Fields Section -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Field Dinamis</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addField()">
                                            <i class="bi bi-plus"></i> Tambah Field
                                        </button>
                                    </div>
                                    <div id="field-wrapper"></div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="col-md-6">
                                <div class="position-sticky" style="top: 20px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Preview Template</h6>
                                        <button type="button" class="btn btn-sm btn-outline-info btn-preview" onclick="updatePreview()">
                                            <i class="bi bi-eye"></i> Refresh Preview
                                        </button>
                                    </div>
                                    <div class="preview-container">
                                        <div id="template-preview" class="template-preview">
                                            <p class="text-muted text-center">Preview akan muncul di sini...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="loading-spinner">
                                <i class="bi bi-arrow-clockwise spin"></i> Menyimpan...
                            </span>
                            <span class="btn-text">Simpan Template</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-3">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= $this->session->flashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= $this->session->flashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Daftar Template Surat</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templateModal">
                                <i class="bi bi-plus-circle"></i> Tambah Template
                            </button>
                        </div>

                        <!-- Table Template Surat -->
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Nama Template</th>
                                        <th width="20%">Tipe Surat</th>
                                        <th width="10%">Header</th>
                                        <th width="15%">Dibuat</th>
                                        <th width="15%">Diupdate</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($templates as $tpl): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($tpl->nama_template) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= htmlspecialchars($tpl->tipe_surat) ?></span>
                                            </td>
                                            <td>
                                                <?php if (isset($tpl->use_header) && $tpl->use_header): ?>
                                                    <span class="badge bg-success"><i class="bi bi-check"></i> Ya</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><i class="bi bi-x"></i> Tidak</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y H:i', strtotime($tpl->created_at)) ?></small>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y H:i', strtotime($tpl->updated_at)) ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button class="dropdown-item btn-edit-template" data-id="<?= $tpl->id ?>">
                                                                <i class="bi bi-pencil"></i> Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item btn-preview-template" data-id="<?= $tpl->id ?>">
                                                                <i class="bi bi-eye"></i> Preview
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger btn-delete-template" data-id="<?= $tpl->id ?>" data-name="<?= htmlspecialchars($tpl->nama_template) ?>">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let fieldCount = 0;
        let editorInstance = null;

        // Initialize CKEditor
        document.addEventListener('DOMContentLoaded', function() {
            initializeEditor();
            setupEventListeners();
        });

        function initializeEditor() {
            if (editorInstance) {
                editorInstance.destroy();
            }
            
            CKEDITOR.replace('kontenEditor', {
                height: 200,
                toolbar: [
                    { name: 'document', items: ['Source'] },
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                    { name: 'editing', items: ['Find', 'Replace'] },
                    '/',
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Table', 'HorizontalRule'] },
                    '/',
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] }
                ]
            });
            
            editorInstance = CKEDITOR.instances.kontenEditor;
            
            // Auto-update preview when content changes
            editorInstance.on('change', function() {
                updatePreview();
            });
        }

        function setupEventListeners() {
            // Header checkbox toggle
            $('#useHeaderCheck').change(function() {
                const headerSection = $('#headerSection');
                if (this.checked) {
                    headerSection.slideDown().addClass('active');
                } else {
                    headerSection.slideUp().removeClass('active');
                }
                updatePreview();
            });

            // Auto-update preview on input changes
            $('input[name="nama_template"], input[name="tipe_surat"], textarea[name="header_alamat"], textarea[name="header_content"]').on('input', function() {
                updatePreview();
            });

            // Form submission
            $('#templateForm').on('submit', handleFormSubmit);

            // Edit button
            $(document).on('click', '.btn-edit-template', handleEditTemplate);

            // Delete button
            $(document).on('click', '.btn-delete-template', handleDeleteTemplate);

            // Preview button
            $(document).on('click', '.btn-preview-template', handlePreviewTemplate);

            // Modal reset
            $('#templateModal').on('hidden.bs.modal', resetModal);
        }

        function addField(data = {}) {
            const wrapper = document.getElementById('field-wrapper');
            const index = fieldCount++;
            
            const html = `
                <div class="field-container" data-index="${index}">
                    <button type="button" class="remove-field" onclick="removeField(this)">
                        <i class="bi bi-x"></i>
                    </button>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Label <span class="text-danger">*</span></label>
                            <input type="text" name="fields[${index}][label]" class="form-control" required value="${data.label || ''}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Field <span class="text-danger">*</span></label>
                            <input type="text" name="fields[${index}][nama_field]" class="form-control" required value="${data.nama_field || ''}" onchange="updatePreview()">
                            <small class="text-muted">Gunakan format: nama_lengkap, tanggal_lahir, dll</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipe Input</label>
                            <select name="fields[${index}][tipe_input]" class="form-control" onchange="handleTipeInputChange(this, ${index})">
                                <option value="text" ${data.tipe_input === 'text' ? 'selected' : ''}>Text</option>
                                <option value="textarea" ${data.tipe_input === 'textarea' ? 'selected' : ''}>Textarea</option>
                                <option value="date" ${data.tipe_input === 'date' ? 'selected' : ''}>Date</option>
                                <option value="select" ${data.tipe_input === 'select' ? 'selected' : ''}>Select</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Placeholder</label>
                            <input type="text" name="fields[${index}][placeholder]" class="form-control" value="${data.placeholder || ''}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Wajib Diisi?</label>
                            <select name="fields[${index}][is_required]" class="form-control">
                                <option value="1" ${data.is_required !== false ? 'selected' : ''}>Ya</option>
                                <option value="0" ${data.is_required === false ? 'selected' : ''}>Tidak</option>
                            </select>
                        </div>
                        
                        <div class="col-12 mt-2 select-opsi ${data.tipe_input !== 'select' ? 'd-none' : ''}" id="select-opsi-${index}">
                            <label class="form-label">Sumber Data</label>
                            <select name="fields[${index}][sumber_data]" class="form-control" onchange="handleSumberDataChange(this, ${index})">
                                <option value="">Pilih Sumber Data</option>
                                <option value="static" ${data.sumber_data === 'static' ? 'selected' : ''}>Static</option>
                                <option value="relasi" ${data.sumber_data === 'relasi' ? 'selected' : ''}>Relasi</option>
                            </select>
                            
                            <div class="mt-2 sumber-static ${data.sumber_data !== 'static' ? 'd-none' : ''}" id="sumber-static-${index}">
                                <label class="form-label">Opsi Static (pisahkan dengan koma)</label>
                                <input type="text" name="fields[${index}][opsi_static]" class="form-control" value="${data.opsi_static_string || ''}">
                                <small class="text-muted">Contoh: Laki-laki, Perempuan</small>
                            </div>
                            
                            <div class="mt-2 sumber-relasi ${data.sumber_data !== 'relasi' ? 'd-none' : ''}" id="sumber-relasi-${index}">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Nama Tabel</label>
                                        <input type="text" name="fields[${index}][tabel_relasi]" class="form-control" value="${data.tabel_relasi || ''}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kolom Value</label>
                                        <input type="text" name="fields[${index}][kolom_value]" class="form-control" value="${data.kolom_value || ''}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kolom Label</label>
                                        <input type="text" name="fields[${index}][kolom_label]" class="form-control" value="${data.kolom_label || ''}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            
            wrapper.insertAdjacentHTML('beforeend', html);
            updatePreview();
        }

        function removeField(button) {
            button.closest('.field-container').remove();
            updatePreview();
        }

        function handleTipeInputChange(select, index) {
            const opsi = document.getElementById(`select-opsi-${index}`);
            opsi.classList.toggle('d-none', select.value !== 'select');
            updatePreview();
        }

        function handleSumberDataChange(select, index) {
            const val = select.value;
            document.getElementById(`sumber-static-${index}`).classList.toggle('d-none', val !== 'static');
            document.getElementById(`sumber-relasi-${index}`).classList.toggle('d-none', val !== 'relasi');
        }

        function updatePreview() {
            const previewContainer = document.getElementById('template-preview');
            const useHeader = document.getElementById('useHeaderCheck').checked;
            const headerAlamat = document.querySelector('input[name="header_alamat"]')?.value || '';
            const headerContent = document.querySelector('textarea[name="header_content"]')?.value || '';
            const konten = editorInstance ? editorInstance.getData() : '';
            
            let preview = '';
            
            // Header Section
            if (useHeader) {
                preview += '<div class="header-content">';
                preview += '<div class="d-flex align-items-center justify-content-center mb-2">';
                preview += '<div class="header-logo me-3">[LOGO]</div>';
                preview += '<div>';
                preview += '<h5 class="mb-1">PEMERINTAH DESA [NAMA_DESA]</h5>';
                if (headerAlamat) {
                    preview += `<p class="mb-1 small">${headerAlamat}</p>`;
                }
                if (headerContent) {
                    preview += `<p class="mb-0 small">${headerContent}</p>`;
                }
                preview += '</div>';
                preview += '</div>';
                preview += '</div>';
            }
            
            // Content Section
            if (konten) {
                let processedContent = konten;
                
                // Replace field placeholders with preview badges
                const fieldContainers = document.querySelectorAll('.field-container');
                fieldContainers.forEach(container => {
                    const namaField = container.querySelector('input[name*="[nama_field]"]')?.value;
                    const label = container.querySelector('input[name*="[label]"]')?.value;
                    
                    if (namaField && label) {
                        const regex = new RegExp(`{{\\s*${namaField}\\s*}}`, 'g');
                        processedContent = processedContent.replace(regex, `<span class="field-preview">${label}</span>`);
                    }
                });
                
                preview += processedContent;
            } else {
                preview += '<p class="text-muted text-center">Masukkan konten surat untuk melihat preview...</p>';
            }
            
            previewContainer.innerHTML = preview;
        }

        function resetModal() {
            document.getElementById('templateForm').reset();
            document.getElementById('templateId').value = '';
            document.getElementById('field-wrapper').innerHTML = '';
            document.getElementById('headerSection').style.display = 'none';
            document.getElementById('current-logo').style.display = 'none';
            
            fieldCount = 0;
            
            if (editorInstance) {
                editorInstance.setData('');
            }
            
            document.getElementById('templateModalLabel').textContent = 'Tambah Template Surat';
            document.getElementById('templateForm').action = '<?= base_url('admin/buatsurat/konfigurasi_store') ?>';
            
            updatePreview();
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            
            // Validation
            const namaTemplate = this.querySelector('[name="nama_template"]').value.trim();
            const tipeSurat = this.querySelector('[name="tipe_surat"]').value.trim();
            const konten = editorInstance ? editorInstance.getData().trim() : '';
            
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            
            let hasError = false;
            
            if (!namaTemplate) {
                document.getElementById('error-nama-template').textContent = 'Nama template wajib diisi';
                hasError = true;
            }
            
            if (!tipeSurat) {
                document.getElementById('error-tipe-surat').textContent = 'Tipe surat wajib diisi';
                hasError = true;
            }
            
            if (!konten) {
                document.getElementById('error-konten').textContent = 'Konten surat wajib diisi';
                hasError = true;
            }
            
            if (hasError) {
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const loadingSpinner = submitBtn.querySelector('.loading-spinner');
            const btnText = submitBtn.querySelector('.btn-text');
            
            loadingSpinner.style.display = 'inline-block';
            btnText.style.display = 'none';
            submitBtn.disabled = true;
            
            // Submit form
            this.submit();
        }

        function handleEditTemplate() {
            const id = $(this).data('id');
            resetModal();
            
            $.ajax({
                url: `<?= base_url('admin/buatsurat/konfigurasi_get/') ?>${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Set form action and title
                    $('#templateForm').attr('action', '<?= base_url('admin/buatsurat/konfigurasi_update') ?>');
                    $('#templateModalLabel').text('Edit Template Surat');
                    
                    // Fill basic data
                    $('#templateId').val(data.template.id);
                    $('[name="nama_template"]').val(data.template.nama_template);
                    $('[name="tipe_surat"]').val(data.template.tipe_surat);
                    
                    // Handle header
                    if (data.template.use_header) {
                        $('#useHeaderCheck').prop('checked', true);
                        $('#headerSection').show().addClass('active');
                        $('[name="header_alamat"]').val(data.template.header_alamat || '');
                        $('[name="header_content"]').val(data.template.header_content || '');
                        
                        if (data.template.header_logo) {
                            $('#current-logo').show();
                            $('#current-logo-img').attr('src', '<?= base_url() ?>' + data.template.header_logo);
                        }
                    }
                    
                    // Set content
                    if (editorInstance) {
                        editorInstance.setData(data.template.konten);
                    }
                    
                    // Add fields
                    if (Array.isArray(data.fields)) {
                        data.fields.forEach((field) => {
                            // Prepare opsi_static_string for display
                            if (field.opsi_static) {
                                try {
                                    const options = JSON.parse(field.opsi_static);
                                    field.opsi_static_string = Array.isArray(options) ? options.join(', ') : '';
                                } catch (e) {
                                    field.opsi_static_string = '';
                                }
                            }
                            
                            addField(field);
                        });
                    }
                    
                    // Show modal
                    $('#templateModal').modal('show');
                    
                    // Update preview after a short delay
                    setTimeout(updatePreview, 500);
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memuat data template', 'error');
                }
            });
        }

        function handleDeleteTemplate() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus template "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= base_url('admin/buatsurat/konfigurasi_delete/') ?>${id}`;
                }
            });
        }

        function handlePreviewTemplate() {
            const id = $(this).data('id');
            
            $.ajax({
                url: `<?= base_url('admin/buatsurat/konfigurasi_get/') ?>${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let previewContent = '';
                    
                    // Header
                    if (data.template.use_header) {
                        previewContent += '<div class="header-content">';
                        previewContent += '<div class="text-center">';
                        if (data.template.header_logo) {
                            previewContent += `<img src="<?= base_url() ?>${data.template.header_logo}" class="header-logo mb-2">`;
                        }
                        previewContent += '<h5>PEMERINTAH DESA [NAMA_DESA]</h5>';
                        if (data.template.header_alamat) {
                            previewContent += `<p class="small">${data.template.header_alamat}</p>`;
                        }
                        if (data.template.header_content) {
                            previewContent += `<p class="small">${data.template.header_content}</p>`;
                        }
                        previewContent += '</div>';
                        previewContent += '</div>';
                    }
                    
                    // Content
                    previewContent += data.template.konten;
                    
                    // Show in modal
                    Swal.fire({
                        title: `Preview: ${data.template.nama_template}`,
                        html: `<div class="template-preview text-start">${previewContent}</div>`,
                        width: '80%',
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memuat preview template', 'error');
                }
            });
        }
    </script>
</main>