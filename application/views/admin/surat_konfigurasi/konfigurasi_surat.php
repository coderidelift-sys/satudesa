<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>

<main id="main" class="main">
	<div class="pagetitle">
		<h1>Daftar Konfigurasi Surat</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
				<li class="breadcrumb-item active">Konfigurasi Surat</li>
			</ol>
		</nav>
	</div>

	<!-- Modal Tambah Template -->
	<div class="modal fade" id="tambahTemplateModal" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<form id="templateForm" action="<?= base_url('admin/buatsurat/konfigurasi_store') ?>" method="post">
				<input type="hidden" name="id" id="templateId">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Template Surat</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label>Nama Template</label>
							<input type="text" name="nama_template" class="form-control" required>
						</div>
						<div class="mb-3">
							<label>Tipe Surat</label>
							<input type="text" name="tipe_surat" class="form-control" required>
						</div>
						<div class="mb-3">
							<label>Konten Surat</label>
							<textarea name="konten" class="form-control ckeditor" rows="10"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Simpan</button>
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
								<div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
							<?php endif; ?>
							<?php if ($this->session->flashdata('error')): ?>
								<div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
							<?php endif; ?>
						</div>

						<!-- Tombol Tambah Template -->
						<button type="button" class="btn btn-primary btn-sm mt-4" data-bs-toggle="modal" data-bs-target="#tambahTemplateModal">
							<i class="bi bi-plus-circle"></i> Tambah Template Surat
						</button>

						<!-- Table Template Surat -->
						<div class="table-responsive mt-3">
							<table class="table datatable">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Template</th>
										<th>Tipe Surat</th>
										<th>Dibuat</th>
										<th>Diupdate</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1;
									foreach ($templates as $tpl): ?>
										<tr>
											<td><?= $no++ ?></td>
											<td><?= htmlspecialchars($tpl->nama_template) ?></td>
											<td><?= htmlspecialchars($tpl->tipe_surat) ?></td>
											<td><?= $tpl->created_at ?></td>
											<td><?= $tpl->updated_at ?></td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
														<i class="bi bi-gear"></i> Aksi
													</button>
													<ul class="dropdown-menu">
														<li>
															<button class="dropdown-item btn-edit-template"
																data-id="<?= $tpl->id ?>">
																<i class="bi bi-pencil"></i> Edit
															</button>
														</li>

														<li>
															<a href="javascript:void(0)" class="dropdown-item text-danger" onclick="konfirmasiDelete('<?= base_url('admin/buatsurat/konfigurasi_delete/' . $tpl->id) ?>')">
																<i class="bi bi-trash"></i> Hapus
															</a>
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

	<script>
		let fieldCount = 0;

		// Fungsi untuk reset modal
		function resetModal() {
			document.getElementById('templateForm').reset();
			CKEDITOR.instances['konten'].setData('');
			document.getElementById('field-wrapper').innerHTML = '';
			document.getElementById('templateId').value = '';

			fieldCount = 0; // Reset jumlah field
			document.getElementById('tambahTemplateModal').querySelector('.modal-title').textContent = 'Tambah Template Surat';
			document.getElementById('templateForm').setAttribute('action', '<?= base_url('admin/buatsurat/konfigurasi_store') ?>');
		}

		function addField(data = {}) {
			const wrapper = document.getElementById('field-wrapper');
			const index = fieldCount++;
			const html = `
      <div class="card p-3 mb-3 position-relative border">
        <button type="button" class="btn-close position-absolute end-0 top-0 mt-2 me-2" onclick="this.parentElement.remove()"></button>
        <div class="row g-2">
          <div class="col-md-6">
            <label>Label</label>
            <input type="text" name="fields[${index}][label]" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Nama Field</label>
            <input type="text" name="fields[${index}][nama_field]" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>Tipe Input</label>
            <select name="fields[${index}][tipe_input]" class="form-control" onchange="handleTipeInputChange(this, ${index})">
              <option value="text">Text</option>
              <option value="textarea">Textarea</option>
              <option value="date">Date</option>
              <option value="select">Select</option>
            </select>
          </div>
          <div class="col-md-4">
            <label>Placeholder</label>
            <input type="text" name="fields[${index}][placeholder]" class="form-control">
          </div>
          <div class="col-md-4">
            <label>Wajib Diisi?</label>
            <select name="fields[${index}][is_required]" class="form-control">
              <option value="1" selected>Ya</option>
              <option value="0">Tidak</option>
            </select>
          </div>
          <div class="col-12 mt-2 select-opsi d-none" id="select-opsi-${index}">
            <label>Sumber Data</label>
            <select name="fields[${index}][sumber_data]" class="form-control" onchange="handleSumberDataChange(this, ${index})">
			  <option value="">Pilih Sumber Data</option>
              <option value="static">Static</option>
              <option value="relasi">Relasi</option>
            </select>
            <div class="mt-2 sumber-static d-none" id="sumber-static-${index}">
              <label>Opsi Static (pisahkan dengan koma)</label>
              <input type="text" name="fields[${index}][opsi_static]" class="form-control">
            </div>
            <div class="mt-2 sumber-relasi d-none" id="sumber-relasi-${index}">
              <div class="row g-2">
                <div class="col-md-4">
                  <label>Nama Tabel</label>
                  <input type="text" name="fields[${index}][tabel_relasi]" class="form-control">
                </div>
                <div class="col-md-4">
                  <label>Kolom Value</label>
                  <input type="text" name="fields[${index}][kolom_value]" class="form-control">
                </div>
                <div class="col-md-4">
                  <label>Kolom Label</label>
                  <input type="text" name="fields[${index}][kolom_label]" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>`;
			wrapper.insertAdjacentHTML('beforeend', html);
		}

		function handleTipeInputChange(select, index) {
			const opsi = document.getElementById(`select-opsi-${index}`);

			opsi.classList.toggle('d-none', select.value !== 'select');
		}

		function handleSumberDataChange(select, index) {
			const val = select.value;
			document.getElementById(`sumber-static-${index}`).classList.toggle('d-none', val !== 'static');
			document.getElementById(`sumber-relasi-${index}`).classList.toggle('d-none', val !== 'relasi');
		}

		document.getElementById('tambahTemplateModal').addEventListener('hidden.bs.modal', resetModal);
		document.getElementById('templateForm').addEventListener('submit', function(e) {
			e.preventDefault();

			// Validasi minimal
			const namaTemplate = this.querySelector('[name="nama_template"]').value.trim();
			const tipeSurat = this.querySelector('[name="tipe_surat"]').value.trim();
			const konten = CKEDITOR.instances['konten'].getData().trim();

			if (!namaTemplate || !tipeSurat || !konten) {
				alert("Semua field harus diisi!");
				return;
			}

			this.submit();
		});

		document.addEventListener('DOMContentLoaded', function() {
			$('.btn-edit-template').on('click', function() {
				const id = $(this).data('id');

				// Reset modal sebelum isi data baru
				resetModal();

				$.ajax({
					url: `<?= base_url('admin/buatsurat/konfigurasi_get/') ?>${id}`,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						// Set form action ke update
						$('#templateForm').attr('action', '<?= base_url('admin/buatsurat/konfigurasi_update') ?>');
						$('#tambahTemplateModal .modal-title').text('Edit Template Surat');

						// Isi input dasar
						$('#templateId').val(data.template.id);
						$('[name="nama_template"]').val(data.template.nama_template);
						$('[name="tipe_surat"]').val(data.template.tipe_surat);
						CKEDITOR.instances['konten'].setData(data.template.konten);

						// Tambah field baru
						if (Array.isArray(data.fields)) {
							data.fields.forEach((field, index) => {
								addField(field); // generate field kosong dulu
							});

							// Set value ke tiap field
							data.fields.forEach((field, index) => {
								const $fieldWrapper = $('#field-wrapper .card').eq(index);

								$fieldWrapper.find('[name^="fields"][name$="[label]"]').val(field.label || '');
								$fieldWrapper.find('[name^="fields"][name$="[nama_field]"]').val(field.nama_field || '');
								$fieldWrapper.find('[name^="fields"][name$="[tipe_input]"]').val(field.tipe_input || 'text');
								$fieldWrapper.find('[name^="fields"][name$="[placeholder]"]').val(field.placeholder || '');
								$fieldWrapper.find('[name^="fields"][name$="[is_required]"]').val(field.is_required ? '1' : '0');

								if (field.tipe_input === 'select') {
									const $sumberData = $fieldWrapper.find('[name^="fields"][name$="[sumber_data]"]');
									$sumberData.val(field.sumber_data || '');

									if (field.sumber_data === 'static') {
										$fieldWrapper.find('[name^="fields"][name$="[opsi_static]"]').val(
											field.opsi_static ? JSON.parse(field.opsi_static).join(', ') : ''
										);
									} else if (field.sumber_data === 'relasi') {
										$fieldWrapper.find('[name^="fields"][name$="[tabel_relasi]"]').val(field.tabel_relasi || '');
										$fieldWrapper.find('[name^="fields"][name$="[kolom_value]"]').val(field.kolom_value || '');
										$fieldWrapper.find('[name^="fields"][name$="[kolom_label]"]').val(field.kolom_label || '');
									}
								}
							});
						}

						// Tampilkan modal
						$('#tambahTemplateModal').modal('show');
					},
					error: function() {
						console.log('Error fetching template data');
					}
				});
			});
		});
	</script>
</main>