<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.1/jspdf.umd.min.js"></script>

<main id="main" class="main">
	<div class="pagetitle">
		<h1>Pengajuan Buat Surat</h1>
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
				<?php if ($this->session->flashdata('success')): ?>
					<div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
				<?php endif; ?>
				<?php if ($this->session->flashdata('error')): ?>
					<div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
				<?php endif; ?>

				<div class="row g-4">
					<!-- Form -->
					<div class="col-md-6">
						<div class="card shadow-sm h-100">
							<div class="card-body">
								<h5 class="card-title">Pilih Template dan Isi Data</h5>

								<div class="row mb-3">
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="warga-select" class="form-label">Pilih Data Warga:</label>
											<select id="warga-select" class="form-select">
												<option value="">-- Pilih Data Warga --</option>
											</select>
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group">
											<label for="no_wa" class="mb-2">No Whatsapp</label>
											<input type="text" class="form-control" id="no_wa" placeholder="Masukkan No Whatsapp" />
										</div>
									</div>
								</div>

								<div class="mb-3">
									<label for="template-select" class="form-label">Pilih Template:</label>
									<select id="template-select" class="form-select">
										<option value="">-- Pilih Template --</option>
									</select>
								</div>

								<div id="dynamic-form-fields"></div>

								<div class="d-flex gap-2 mt-4">
									<button id="confirm-letter" class="btn btn-outline-success">Buat Pengajuan</button>
									<button id="download-pdf" class="btn btn-outline-danger">Unduh PDF</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Preview -->
					<div class="col-md-6">
						<div class="position-sticky" style="top: 1rem;">
							<div class="card shadow-sm">
								<div class="card-body">
									<h5 class="card-title">Preview Surat</h5>
									<div id="letter-preview" class="border rounded p-3 text-muted small" style="min-height: 200px;">
										<p class="text-center">Pilih template dan isi data untuk melihat preview.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
	$(function() {
		const $templateSelect = $('#template-select'),
			$formFields = $('#dynamic-form-fields'),
			$preview = $('#letter-preview'),
			$downloadBtn = $('#download-pdf'),
			$draftBtn = $('#simpan-draft-surat'),
			$confirmBtn = $('#confirm-letter');

		let templates = [];
		let wargaList = [];
		let selectedWarga = null;

		async function downloadLetterAsPdf() {
			const {
				jsPDF
			} = window.jspdf;
			const doc = new jsPDF();

			// Get the content from the letter preview div
			const content = document.getElementById("letter-preview");
			html2canvas(content, {
					scale: 2, // Increase scale for better quality PDF
				})
				.then((canvas) => {
					const imgData = canvas.toDataURL("image/png");
					const imgWidth = 210; // A4 width in mm
					const pageHeight = 297; // A4 height in mm
					const imgHeight =
						(canvas.height * imgWidth) / canvas.width;
					let heightLeft = imgHeight;

					let position = 0;

					doc.addImage(
						imgData,
						"PNG",
						0,
						position,
						imgWidth,
						imgHeight
					);
					heightLeft -= pageHeight;

					while (heightLeft >= 0) {
						position = heightLeft - imgHeight;
						doc.addPage();
						doc.addImage(
							imgData,
							"PNG",
							0,
							position,
							imgWidth,
							imgHeight
						);
						heightLeft -= pageHeight;
					}

					// Save the PDF
					const rawTitle = $templateSelect
						.find("option:selected")
						.text()
						.trim();
					const titleCase = rawTitle
						.toLowerCase()
						.split(/\s+/)
						.map(
							(word) =>
							word.charAt(0).toUpperCase() +
							word.slice(1)
						)
						.join(" ");

					const dateStr = new Date()
						.toISOString()
						.slice(0, 10);
					const filename = `${titleCase} ${dateStr}.pdf`;

					doc.save(filename);
				})
				.catch((error) => {
					console.error("Error generating PDF:", error);
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: 'Terjadi kesalahan saat membuat PDF. Silakan coba lagi.',
					});
				});
		}

		function fetchTemplates() {
			$.ajax({
				url: '<?= base_url('admin/buatsurat/get_all_template_surat') ?>',
				method: 'GET',
				dataType: 'json',
				success: function(response) {
					if (!response || !response.templates) {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Tidak ada template surat yang ditemukan.',
						});
						return;
					}

					templates = response.templates;
					templates.forEach(t => {
						$templateSelect.append(
							$('<option>', {
								value: t.id,
								text: t.name
							})
						);
					});
				},
				error: function(err) {
					console.error('Gagal memuat template surat.', err);
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: 'Gagal memuat template surat. Silakan cek koneksi atau hubungi admin.',
					});
				}
			});
		}

		function fetchDataWarga() {
			$.ajax({
				url: '<?= base_url('admin/buatsurat/get_data_warga') ?>',
				method: 'GET',
				dataType: 'json',
				success: function(response) {
					if (!response || !response.warga) {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Tidak ada data warga yang ditemukan.',
						});
						return;
					}

					const $wargaSelect = $('#warga-select');
					response.warga.forEach(w => {
						$wargaSelect.append(
							$('<option>', {
								value: w.anggota_id,
								text: `${w.nama_lengkap} (${w.nik})`
							})
						);
					});

					wargaList = response.warga;
				},
				error: function(err) {
					console.error('Gagal memuat data warga.', err);
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: 'Gagal memuat data warga. Silakan cek koneksi atau hubungi admin.',
					});
				}
			});
		}

		function formatTanggalIndo(tgl) {
			const bulan = [
				'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
				'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
			];
			const parts = tgl.split('-');
			return `${parseInt(parts[2])} ${bulan[parseInt(parts[1]) - 1]} ${parts[0]}`;
		}

		function prefillFieldsFromWarga(warga) {
			if (!warga) return;

			const fullAlamat = `RT ${warga.rt}/RW ${warga.rw}, ${warga.desa}, ${warga.kecamatan}, ${warga.kota}, ${warga.propinsi}, ${warga.kode_pos}`;
			const ttl = `${warga.kota}, ${formatTanggalIndo(warga.tgl_lahir)}`;

			const fieldAlias = {
				nama: 'nama_lengkap',
				nama_almarhum: 'nama_lengkap',
				tempat_lahir: 'kota',
				tanggal_lahir: 'tgl_lahir',
				ttl: ttl,
				jenis_kelamin: 'gender',
				alamat: fullAlamat,
				pekerjaan: 'pekerjaan',
				nik: 'nik',
				kepala_keluarga: 'kepala_keluarga',
				rt: 'rt',
				rw: 'rw'
			};

			$formFields.find('input, textarea').each(function() {
				const fieldId = $(this).attr('id');
				let value = '';

				if (fieldAlias[fieldId]) {
					value = fieldAlias[fieldId];
					if (warga.hasOwnProperty(value)) {
						value = warga[value];
					}
				} else if (warga.hasOwnProperty(fieldId)) {
					value = warga[fieldId];
				}

				if (fieldId === 'ttl') {
					value = ttl;
				}

				if (fieldId === 'alamat') {
					value = fullAlamat;
				}

				$(this).val(value).trigger('input');
			});
		}

		function updatePreview() {
			const selId = $templateSelect.val();
			const tmpl = templates.find(t => t.id == selId);
			if (!tmpl) return;

			let content = tmpl.content_template;
			const variables = [...new Set(content.match(/{{\s*([\w\s_]+)\s*}}/g))];

			if (variables) {
				variables.forEach(match => {
					const fieldId = match.replace(/{{\s*|\s*}}/g, '');
					const $field = $('#' + fieldId);

					// jika field ada class error, maka hapus
					if ($field.hasClass('is-invalid')) {
						$field.removeClass('is-invalid');
					}

					if ($field.length === 0) return;

					let value = $field.val();

					if (fieldId === 'signature' || fieldId === 'tanda_tangan') {
						const signatureImg = window.signatureImage || '';
						const replacement = (signatureImg && signatureImg.length > 1000) ?
							`<img src="${signatureImg}" style="max-height:100px;" />` :
							'‏‏‎ ‎';

						const regex = new RegExp(`{{\\s*${fieldId}\\s*}}`, 'g');
						content = content.replace(regex, replacement);
						return; // skip lanjut ke bawah
					}

					if (!value || value.trim() === '') {
						value = '\u00A0';
					}

					const escaped = $('<div>').text(value).html();
					const regex = new RegExp(`{{\\s*${fieldId}\\s*}}`, 'g');
					content = content.replace(regex, escaped);
				});
			}

			$preview.html(`<div style="font-family:'Courier New', monospace; line-height: 1.5; font-weight: bold;">${content}</div>`);
		}

		$templateSelect.change(function() {
			const selId = $(this).val();
			const tmpl = templates.find(t => t.id == selId);

			$formFields.empty();
			$preview.html('<p class="text-center">Silakan isi form di bawah untuk melihat preview surat.</p>');

			if (!tmpl) {
				$preview.html('<p class="text-center">Template tidak ditemukan.</p>');
				return;
			}

			const variables = [...new Set(tmpl.content_template.match(/{{\s*([\w_]+)\s*}}/g))].map(v =>
				v.replace(/{{\s*|\s*}}/g, '')
			);

			variables.forEach(fieldId => {
				const labelText = fieldId.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
				const $wrapper = $('<div class="mb-3"></div>');
				const $label = $('<label>', {
					for: fieldId,
					class: 'form-label',
					text: labelText,
					required: true,
				});

				let $input;

				if (['signature', 'tanda_tangan'].includes(fieldId)) {
					$input = $('<input>', {
						type: 'file',
						id: fieldId,
						class: 'form-control',
						accept: 'image/*'
					});

					const $previewImg = $('<img>', {
						id: 'signature-preview',
						style: 'max-height:80px; display:none; margin-top:10px;'
					});

					const $removeBtn = $('<button>', {
						type: 'button',
						class: 'btn btn-sm btn-danger mt-2',
						text: 'Hapus Tanda Tangan',
						style: 'display:none;'
					}).on('click', function() {
						window.signatureImage = '';
						$input.val('');
						$previewImg.hide();
						$removeBtn.hide();
						updatePreview();
					});

					$input.on('change', function(e) {
						const file = this.files[0];
						if (file) {
							const reader = new FileReader();
							reader.onload = function(evt) {
								window.signatureImage = evt.target.result;
								$removeBtn.show();
								updatePreview();
							};
							reader.readAsDataURL(file);
						}
					});

					$wrapper.append($label, $input, $previewImg, $removeBtn);
				} else {
					$input = $('<input>', {
						type: 'text',
						id: fieldId,
						class: 'form-control',
						placeholder: 'Masukkan ' + labelText
					}).on('input', updatePreview);

					$wrapper.append($label, $input);
				}

				$formFields.append($wrapper);
			});

			// Prefill jika sudah pilih warga
			if (selectedWarga) {
				prefillFieldsFromWarga(selectedWarga);
			}

			updatePreview();
		});

		$('#warga-select').change(function() {
			const selectedId = $(this).val();
			selectedWarga = wargaList.find(w => w.anggota_id == selectedId);

			if (selectedWarga) {
				prefillFieldsFromWarga(selectedWarga);
				updatePreview();
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Data warga tidak ditemukan.',
				});
			}
		});

		$downloadBtn.click(function() {
			if (!$templateSelect.val()) {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Silakan pilih template terlebih dahulu.',
				});
				return;
			}
			downloadLetterAsPdf();
		});

		$confirmBtn.click(async function() {
			if (!selectedWarga) {
				return Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Silakan pilih data warga terlebih dahulu.',
				});
			}

			const $noWaInput = $('#no_wa');
			const noWa = $noWaInput.val()?.trim();

			if (!noWa) {
				$noWaInput.focus();
				return Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'No Whatsapp tidak boleh kosong.',
				});
			}

			const selId = $templateSelect.val();
			if (!selId) {
				return Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Silakan pilih template surat terlebih dahulu.',
				});
			}

			const tmpl = templates.find(t => t.id == selId);
			if (!tmpl) {
				return Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Template surat tidak ditemukan.',
				});
			}

			// Cek field kosong kecuali file/image
			const emptyFields = $formFields.find('input:not([type="file"]):not([type="image"]), textarea').filter(function() {
				return !$(this).val()?.trim();
			});

			if (emptyFields.length > 0) {
				emptyFields.first().focus().addClass('is-invalid');
				return;
			}

			// Ambil variabel dari template dan isi datanya
			const variables = [...new Set(tmpl.content_template.match(/{{\s*([\w_]+)\s*}}/g))].map(v =>
				v.replace(/{{\s*|\s*}}/g, '')
			);

			const dataObj = {};
			variables.forEach(id => {
				const $el = $('#' + id);
				dataObj[id] = $el.val()?.trim() || '';
			});

			const isiSurat = JSON.stringify(dataObj);

			// Generate PDF
			const {
				jsPDF
			} = window.jspdf;
			const doc = new jsPDF();
			const content = document.getElementById("letter-preview");

			try {
				const canvas = await html2canvas(content, {
					scale: 2
				});
				const imgData = canvas.toDataURL("image/png");
				const imgWidth = 210; // A4 in mm
				const pageHeight = 297;
				const imgHeight = (canvas.height * imgWidth) / canvas.width;

				let heightLeft = imgHeight;
				let position = 0;

				doc.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
				heightLeft -= pageHeight;

				while (heightLeft > 0) {
					position = heightLeft - imgHeight;
					doc.addPage();
					doc.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
					heightLeft -= pageHeight;
				}

				// Konversi PDF ke Blob dan buat File
				const pdfBlob = doc.output("blob");
				const pdfFile = new File([pdfBlob], "surat_digital.pdf", {
					type: "application/pdf"
				});

				// Siapkan FormData untuk AJAX
				const formData = new FormData();
				formData.append("nik", selectedWarga.nik);
				formData.append("template_id", selId);
				formData.append("no_wa", noWa);
				formData.append("isi_surat", isiSurat);
				formData.append("file", pdfFile);

				// Lanjutkan proses AJAX jika diperlukan
				$.ajax({
					url: '<?= base_url('admin/buatsurat/simpan_surat') ?>',
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					success: function(res) {
						if (res.status === 'success') {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: 'Surat berhasil dibuat dan disimpan.',
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = '<?= base_url('admin/buatsurat/list') ?>';
								}
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: res.message || 'Terjadi kesalahan saat menyimpan surat.',
							});
						}
					},
					error: function(xhr, status, err) {
						console.error("Upload gagal:", err);
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
						});
					},
				});

			} catch (err) {
				console.error("Gagal menghasilkan PDF:", err);
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Terjadi kesalahan saat membuat PDF. Silakan coba lagi.',
				});
			}
		});

		fetchDataWarga();
		fetchTemplates();
	});
</script>