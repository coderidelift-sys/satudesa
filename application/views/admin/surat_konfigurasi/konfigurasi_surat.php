<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	/* Preview Styles */
	.preview-container {
		height: calc(100vh - 200px);
		overflow-y: auto;
		border: 1px solid #dee2e6;
		border-radius: 0.375rem;
		background: #fff;
	}

	.preview-paper {
		background: white;
		min-height: 100%;
		padding: 2rem;
		margin: 0 auto;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		font-family: 'Times New Roman', serif;
		line-height: 1.6;
		color: #333;
		transition: all 0.3s ease;
	}

	/* Responsive Preview Modes */
	.preview-paper.desktop {
		max-width: 210mm;
		/* A4 width */
		font-size: 12pt;
	}

	.preview-paper.tablet {
		max-width: 600px;
		font-size: 11pt;
		padding: 1.5rem;
	}

	.preview-paper.mobile {
		max-width: 400px;
		font-size: 10pt;
		padding: 1rem;
	}

	/* Preview Content Styles */
	.preview-header {
		text-align: center;
		border-bottom: 2px solid #333;
		padding-bottom: 1rem;
		margin-bottom: 2rem;
	}

	.preview-header.horizontal {
		text-align: left;
	}

	.preview-header img {
		max-height: 80px;
		margin-bottom: 0.5rem;
	}

	.preview-header .header-content {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 1rem;
	}

	.preview-header .logo-left,
	.preview-header .logo-right {
		max-height: 80px;
	}

	.preview-header .logo-top,
	.preview-header .logo-bottom {
		display: block;
		margin: 0 auto 0.5rem;
	}

	.preview-header .logo-bottom {
		margin-top: 1rem;
		margin-bottom: 0;
	}

	.preview-header h3 {
		margin: 0;
		font-weight: bold;
		text-transform: uppercase;
	}

	.preview-header p {
		margin: 0.25rem 0;
		font-size: 0.9em;
	}

	.preview-content {
		text-align: justify;
	}

	.preview-content .center {
		text-align: center;
	}

	.preview-content .bold {
		font-weight: bold;
	}

	.preview-content .underline {
		text-decoration: underline;
	}

	.preview-content .tab {
		display: inline-block;
		width: 2rem;
	}

	/* Form Enhancements */
	.form-label {
		font-weight: 600;
		color: #495057;
	}

	.form-label i {
		color: #6c757d;
		margin-right: 0.5rem;
	}

	/* SOP Guide Styles */
	#sopGuide code {
		background-color: #f8f9fa;
		padding: 0.2rem 0.4rem;
		border-radius: 0.25rem;
		font-size: 0.875em;
		color: #e83e8c;
	}

	#sopGuide .bg-white code {
		display: block;
		white-space: pre-line;
		background-color: #f8f9fa;
		padding: 1rem;
		border-radius: 0.375rem;
		color: #333;
		font-family: 'Courier New', monospace;
		font-size: 0.8rem;
		line-height: 1.4;
	}

	/* Modal Enhancements */
	.modal-fullscreen .modal-body {
		height: calc(100vh - 120px);
	}

	/* Active Preview Mode */
	.btn-group .btn.active {
		background-color: #0d6efd;
		color: white;
		border-color: #0d6efd;
	}

	/* Loading State */
	.preview-loading {
		opacity: 0.6;
	}

	/* Field Wrapper Styles */
	#field-wrapper .card {
		transition: all 0.3s ease;
		border-left: 4px solid #0d6efd;
	}

	#field-wrapper .card:hover {
		box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
	}

	/* Responsive Adjustments */
	@media (max-width: 991.98px) {
		.modal-fullscreen .row {
			flex-direction: column;
		}

		.preview-container {
			height: 400px;
		}

		.col-lg-6 {
			max-width: 100%;
		}
	}

	/* Copy-item Interactive Elements */
	.copy-item {
		cursor: pointer;
		transition: all 0.2s ease;
		user-select: none;
		position: relative;
		display: inline-block;
		margin: 2px;
	}

	.copy-item:hover {
		transform: translateY(-1px);
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
		filter: brightness(1.1);
	}

	.copy-item:active {
		transform: translateY(0);
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
	}

	/* Copy feedback animation */
	.copy-item.copied {
		animation: copySuccess 0.6s ease;
	}

	@keyframes copySuccess {
		0% {
			transform: scale(1);
		}

		50% {
			transform: scale(1.05);
			background-color: #28a745;
		}

		100% {
			transform: scale(1);
		}
	}

	/* Copy tooltip enhancement */
	.copy-item[title]:hover::after {
		content: attr(title);
		position: absolute;
		bottom: 100%;
		left: 50%;
		transform: translateX(-50%);
		background: rgba(0, 0, 0, 0.8);
		color: white;
		padding: 4px 8px;
		border-radius: 4px;
		font-size: 0.75rem;
		white-space: nowrap;
		z-index: 1000;
		margin-bottom: 5px;
	}

	/* Copy success notification */
	.copy-notification {
		position: fixed;
		top: 20px;
		right: 20px;
		background: #28a745;
		color: white;
		padding: 10px 15px;
		border-radius: 5px;
		z-index: 9999;
		opacity: 0;
		transform: translateX(100%);
		transition: all 0.3s ease;
	}

	.copy-notification.show {
		opacity: 1;
		transform: translateX(0);
	}

	/* Enhanced cursor styles */
	.cursor-pointer {
		cursor: pointer;
	}

	/* Badge enhancements for better visibility */
	.badge.copy-item {
		font-size: 0.75em;
		padding: 0.35em 0.65em;
		line-height: 1.2;
		border: 1px solid transparent;
	}

	.badge.bg-secondary.copy-item:hover {
		background-color: #495057 !important;
		border-color: #343a40;
	}

	.badge.bg-success.copy-item:hover {
		background-color: #1e7e34 !important;
		border-color: #155724;
	}

	.badge.bg-warning.copy-item:hover {
		background-color: #d39e00 !important;
		border-color: #b8860b;
	}

	.badge.bg-danger.copy-item:hover {
		background-color: #bd2130 !important;
		border-color: #a71e2a;
	}

	.badge.bg-info.copy-item:hover {
		background-color: #138496 !important;
		border-color: #117a8b;
	}
</style>

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
		<div class="modal-dialog modal-fullscreen">
			<form id="templateForm" action="<?= base_url('admin/buatsurat/konfigurasi_store') ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="id" id="templateId">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title">
							<i class="bi bi-file-earmark-text"></i> Tambah Template Surat
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body p-0">
						<div class="row g-0 h-100">
							<!-- Form Section -->
							<div class="col-lg-6 border-end">
								<div class="p-4 h-100 overflow-auto">
									<div class="mb-3">
										<label class="form-label fw-bold">
											<i class="bi bi-tag"></i> Nama Template
										</label>
										<input type="text" name="nama_template" class="form-control" required
											placeholder="Contoh: Surat Keterangan Domisili">
									</div>

									<div class="mb-3">
										<label class="form-label fw-bold">
											<i class="bi bi-folder"></i> Tipe Surat
										</label>
										<input type="text" name="tipe_surat" class="form-control" required
											placeholder="Contoh: Keterangan, Permohonan, Undangan">
									</div>

									<!-- Header Configuration Section -->
									<div class="mb-3">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" name="use_header" id="useHeaderCheck">
											<label class="form-check-label fw-bold" for="useHeaderCheck">
												<i class="bi bi-layout-text-window-reverse"></i> Gunakan Header Surat
											</label>
										</div>
									</div>

									<!-- Header Content Section -->
									<div id="headerSection" class="border rounded p-3 mb-3 bg-light" style="display: none;">
										<h6 class="text-primary mb-3">
											<i class="bi bi-gear"></i> Konfigurasi Header
										</h6>

										<div class="mb-3">
											<label for="headerLogoInput" class="form-label">
												<i class="bi bi-image"></i> Logo Header
											</label>
											<input type="file" id="headerLogoInput" name="header_logo" class="form-control" accept="image/*">
											<small class="text-muted">Format: JPG, PNG, SVG. Maksimal 2MB.</small>
										</div>

										<div class="mb-3">
											<label class="form-label d-block mb-2 fw-semibold">
												<i class="bi bi-layout-three-columns"></i> Posisi Logo
											</label>
											<div class="d-flex flex-wrap gap-3">
												<div class="form-check">
												<input class="form-check-input" type="radio" name="logo_position" id="logoTop" value="top" checked>
												<label class="form-check-label" for="logoTop">
													<i class="bi bi-arrow-down"></i> Atas (Default)
												</label>
												</div>
												<div class="form-check">
												<input class="form-check-input" type="radio" name="logo_position" id="logoLeft" value="left">
												<label class="form-check-label" for="logoLeft">
													<i class="bi bi-arrow-right"></i> Kiri
												</label>
												</div>
												<div class="form-check">
												<input class="form-check-input" type="radio" name="logo_position" id="logoRight" value="right">
												<label class="form-check-label" for="logoRight">
													<i class="bi bi-arrow-left"></i> Kanan
												</label>
												</div>
												<div class="form-check">
												<input class="form-check-input" type="radio" name="logo_position" id="logoBottom" value="bottom">
												<label class="form-check-label" for="logoBottom">
													<i class="bi bi-arrow-up"></i> Bawah
												</label>
												</div>
											</div>
										</div>


										<div class="mb-3">
											<label class="form-label">
												<i class="bi bi-geo-alt"></i> Alamat/Kontak
											</label>
											<textarea name="header_alamat" class="form-control" rows="2"
												placeholder="Jl. Raya Desa No. 123, Telp: (021) 123456"></textarea>
										</div>

										<div class="mb-3">
											<label class="form-label">
												<i class="bi bi-text-paragraph"></i> Konten Header Tambahan
											</label>
											<textarea name="header_content" class="form-control" rows="2"
												placeholder="Konten tambahan untuk header (opsional)"></textarea>
										</div>
									</div>

									<!-- Content Section with SOP Guide -->
									<div class="mb-3">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<label class="form-label fw-bold mb-0">
												<i class="bi bi-file-text"></i> Konten Surat
											</label>
										</div>

										<textarea name="konten" id="konten" class="form-control" rows="10"
											placeholder="Ketik konten surat di sini... Gunakan format codes untuk formatting yang rapi."></textarea>
									</div>

									<!-- Dynamic Fields Section -->
									<div class="mb-3">
										<div class="d-flex justify-content-between align-items-center mb-3">
											<h6 class="mb-0 fw-bold">
												<i class="bi bi-list-task"></i> Field Dinamis
											</h6>
											<button type="button" class="btn btn-sm btn-primary" onclick="addField()">
												<i class="bi bi-plus"></i> Tambah Field
											</button>
										</div>
										<div id="field-wrapper"></div>
									</div>
								</div>
							</div>

							<!-- Preview Section -->
							<div class="col-lg-6">
								<div class="p-4 h-100 bg-light">
									<div class="d-flex justify-content-between align-items-center mb-3">
										<h6 class="mb-0 fw-bold text-primary">
											<i class="bi bi-eye"></i> Preview Real-time
										</h6>
										<div class="btn-group btn-group-sm" role="group">
											<button type="button" class="btn btn-outline-secondary" onclick="togglePreviewMode('desktop')" id="previewDesktop">
												<i class="bi bi-display"></i>
											</button>
											<button type="button" class="btn btn-outline-secondary" onclick="togglePreviewMode('tablet')" id="previewTablet">
												<i class="bi bi-tablet"></i>
											</button>
											<button type="button" class="btn btn-outline-secondary" onclick="togglePreviewMode('mobile')" id="previewMobile">
												<i class="bi bi-phone"></i>
											</button>
										</div>
									</div>

									<div class="preview-container">
										<div id="previewContent" class="preview-paper">
											<div class="preview-loading text-center text-muted py-5">
												<i class="bi bi-file-earmark-text fs-1"></i>
												<p class="mt-2">Preview akan muncul saat Anda mengetik konten...</p>
											</div>
										</div>
									</div>

									<!-- Enhanced SOP Guide Collapsible -->
									<div class="collapse mt-3" id="sopGuide">
										<div class="card card-body bg-info bg-opacity-10 border-info">
											<h6 class="text-info mb-3">
												<i class="bi bi-book"></i> Panduan Lengkap Template Surat
											</h6>

											<!-- Format Codes Section -->
											<div class="mb-4">
												<h6 class="fw-bold text-primary mb-2">
													<i class="bi bi-code-slash"></i> Format Codes (Klik untuk Copy):
												</h6>
												<div class="row g-2">
													<div class="col-md-6">
														<div class="d-flex flex-wrap gap-1">
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[br]" title="Baris baru">[br]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[tab]" title="Tab/Indentasi">[tab]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[center]...[/center]" title="Teks tengah">[center]...[/center]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[bold]...[/bold]" title="Teks tebal">[bold]...[/bold]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[underline]...[/underline]" title="Garis bawah">[underline]...[/underline]</span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="d-flex flex-wrap gap-1">
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[italic]...[/italic]" title="Teks miring">[italic]...[/italic]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[right]...[/right]" title="Rata kanan">[right]...[/right]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[left]...[/left]" title="Rata kiri">[left]...[/left]</span>
															<span class="badge bg-secondary copy-item cursor-pointer" data-copy="[justify]...[/justify]" title="Rata kanan-kiri">[justify]...[/justify]</span>
														</div>
													</div>
												</div>
											</div>

											<!-- Placeholder Dinamis Section -->
											<div class="mb-4">
												<h6 class="fw-bold text-success mb-2">
													<i class="bi bi-braces"></i> Placeholder Dinamis (Klik untuk Copy):
												</h6>
												<div class="row g-2">
													<div class="col-md-4">
														<h6 class="small fw-bold">Data Pribadi:</h6>
														<div class="d-flex flex-wrap gap-1 mb-2">
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{nama_lengkap}}" title="Nama lengkap">{{nama_lengkap}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{nik}}" title="NIK">{{nik}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{alamat}}" title="Alamat">{{alamat}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{tempat_lahir}}" title="Tempat lahir">{{tempat_lahir}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{tanggal_lahir}}" title="Tanggal lahir">{{tanggal_lahir}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{jenis_kelamin}}" title="Jenis kelamin">{{jenis_kelamin}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{agama}}" title="Agama">{{agama}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{pekerjaan}}" title="Pekerjaan">{{pekerjaan}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{status_kawin}}" title="Status perkawinan">{{status_kawin}}</span>
														</div>
													</div>
													<div class="col-md-4">
														<h6 class="small fw-bold">Data Surat:</h6>
														<div class="d-flex flex-wrap gap-1 mb-2">
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{nomor_surat}}" title="Nomor surat">{{nomor_surat}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{tanggal}}" title="Tanggal surat">{{tanggal}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{tempat}}" title="Tempat">{{tempat}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{keperluan}}" title="Keperluan">{{keperluan}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{berlaku_sampai}}" title="Berlaku sampai">{{berlaku_sampai}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{keterangan}}" title="Keterangan">{{keterangan}}</span>
														</div>
													</div>
													<div class="col-md-4">
														<h6 class="small fw-bold">Data Pejabat & Desa:</h6>
														<div class="d-flex flex-wrap gap-1 mb-2">
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{nama_pejabat}}" title="Nama pejabat">{{nama_pejabat}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{jabatan}}" title="Jabatan">{{jabatan}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{ttd_nama}}" title="Nama penandatangan">{{ttd_nama}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{ttd_jabatan}}" title="Jabatan penandatangan">{{ttd_jabatan}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{ttd_nip}}" title="NIP penandatangan">{{ttd_nip}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{nama_desa}}" title="Nama desa">{{nama_desa}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{kecamatan}}" title="Kecamatan">{{kecamatan}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{kabupaten}}" title="Kabupaten">{{kabupaten}}</span>
															<span class="badge bg-success copy-item cursor-pointer" data-copy="{{provinsi}}" title="Provinsi">{{provinsi}}</span>
														</div>
													</div>
												</div>
											</div>

											<!-- Template Struktur Section -->
											<div class="mb-4">
												<h6 class="fw-bold text-warning mb-2">
													<i class="bi bi-file-text"></i> Template Struktur Umum (Klik untuk Copy):
												</h6>
												<div class="row g-2">
													<div class="col-md-6">
														<div class="d-flex flex-wrap gap-1 mb-2">
															<span class="badge bg-warning copy-item cursor-pointer" data-copy="[center][bold]SURAT KETERANGAN[/bold][/center][br][br]Nomor: {{nomor_surat}}[br][br]" title="Header surat">Header Surat</span>
															<span class="badge bg-warning copy-item cursor-pointer" data-copy="Yang bertanda tangan di bawah ini:[br][tab]Nama[tab]: {{nama_pejabat}}[br][tab]Jabatan[tab]: {{jabatan}}[br][br]" title="Identitas pejabat">Identitas Pejabat</span>
															<span class="badge bg-warning copy-item cursor-pointer" data-copy="Menerangkan bahwa:[br][tab]Nama[tab]: {{nama_lengkap}}[br][tab]NIK[tab]: {{nik}}[br][tab]Alamat[tab]: {{alamat}}[br][br]" title="Data subjek">Data Subjek</span>
														</div>
													</div>
													<div class="col-md-6">
														<div class="d-flex flex-wrap gap-1 mb-2">
															<span class="badge bg-warning copy-item cursor-pointer" data-copy="Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.[br][br]" title="Penutup resmi">Penutup Resmi</span>
															<span class="badge bg-warning copy-item cursor-pointer" data-copy="[center]{{tempat}}, {{tanggal}}[br]{{jabatan}}[br][br][br]{{nama_pejabat}}[/center]" title="Tanda tangan">Tanda Tangan</span>
															<span class="badge bg-warning copy-item cursor-pointer" data-copy="[center][bold]PEMERINTAH DESA {{nama_desa}}[/bold][br]KECAMATAN {{kecamatan}}[br]KABUPATEN {{kabupaten}}[/center][br][br]" title="Kop surat">Kop Surat</span>
														</div>
													</div>
												</div>
											</div>

											<!-- Shortcut dan Tips -->
											<div class="mb-3">
												<h6 class="fw-bold text-danger mb-2">
													<i class="bi bi-lightning"></i> Shortcut & Tips:
												</h6>
												<div class="row g-2">
													<div class="col-md-12">
														<div class="d-flex flex-wrap gap-1">
															<span class="badge bg-danger copy-item cursor-pointer" data-copy="[tab]Nama[tab]: {{nama_lengkap}}[br][tab]NIK[tab]: {{nik}}[br][tab]Alamat[tab]: {{alamat}}[br]" title="Data lengkap warga">Data Lengkap Warga</span>
															<span class="badge bg-danger copy-item cursor-pointer" data-copy="Surat ini berlaku sampai dengan {{berlaku_sampai}} dan dapat digunakan untuk {{keperluan}}." title="Keterangan berlaku">Keterangan Berlaku</span>
															<span class="badge bg-danger copy-item cursor-pointer" data-copy="[center][bold]SURAT PENGANTAR[/bold][/center][br][br]" title="Header pengantar">Header Pengantar</span>
															<span class="badge bg-danger copy-item cursor-pointer" data-copy="[center][bold]SURAT KETERANGAN DOMISILI[/bold][/center][br][br]" title="Header domisili">Header Domisili</span>
															<span class="badge bg-danger copy-item cursor-pointer" data-copy="[center][bold]SURAT KETERANGAN USAHA[/bold][/center][br][br]" title="Header usaha">Header Usaha</span>
														</div>
													</div>
												</div>
											</div>

											<!-- Contoh Template Lengkap -->
											<div class="mt-3">
												<h6 class="fw-bold text-info mb-2">
													<i class="bi bi-file-earmark-code"></i> Contoh Template Lengkap:
												</h6>
												<div class="bg-white p-3 rounded border">
													<div class="copy-item cursor-pointer" data-copy="[center][bold]PEMERINTAH DESA {{nama_desa}}[/bold][br]KECAMATAN {{kecamatan}}[br]KABUPATEN {{kabupaten}}[/center][br][br][center][bold]SURAT KETERANGAN[/bold][/center][br][br]Nomor: {{nomor_surat}}[br][br]Yang bertanda tangan di bawah ini:[br][table][tr][td]Nama[/td][td]: {{nama_pejabat}}[/td][/tr][tr][td]Jabatan[/td][td]: {{jabatan}}[/td][/tr][/table][br]Menerangkan bahwa:[br][table][tr][td]Nama[/td][td]: {{nama_lengkap}}[/td][/tr][tr][td]NIK[/td][td]: {{nik}}[/td][/tr][tr][td]Alamat[/td][td]: {{alamat}}[/td][/tr][tr][td]Pekerjaan[/td][td]: {{pekerjaan}}[/td][/tr][/table][br]Adalah benar-benar warga Desa {{nama_desa}} dan berdomisili di alamat tersebut di atas.[br][br]Surat keterangan ini dibuat untuk {{keperluan}} dan berlaku sampai dengan {{berlaku_sampai}}.[br][br]Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.[br][br][right]{{tempat}}, {{tanggal}}[br]{{jabatan}}[br][br][br]{{nama_pejabat}}[/right]">
														<code class="small d-block text-wrap" id="templateSurat" style="white-space: pre-line; font-size: 0.75rem;">
															[center][bold]PEMERINTAH DESA {{nama_desa}}[/bold][br]KECAMATAN {{kecamatan}}[br]KABUPATEN {{kabupaten}}[/center][br][br]
															[center][bold]SURAT KETERANGAN[/bold][/center][br][br]
															Nomor: {{nomor_surat}}[br][br]
															Yang bertanda tangan di bawah ini:[br]
															[table]
															[tr][td]Nama[/td][td]: {{nama_pejabat}}[/td][/tr]
															[tr][td]Jabatan[/td][td]: {{jabatan}}[/td][/tr]
															[/table][br]
															Menerangkan bahwa:[br]
															[table]
															[tr][td]Nama[/td][td]: {{nama_lengkap}}[/td][/tr]
															[tr][td]NIK[/td][td]: {{nik}}[/td][/tr]
															[tr][td]Alamat[/td][td]: {{alamat}}[/td][/tr]
															[tr][td]Pekerjaan[/td][td]: {{pekerjaan}}[/td][/tr]
															[/table][br]
															Adalah benar-benar warga desa {{nama_desa}} dan berdomisili di alamat tersebut di atas.[br][br]
															Surat keterangan ini dibuat untuk {{keperluan}} dan berlaku sampai dengan {{berlaku_sampai}}.[br][br]
															Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.[br][br]
															[right]{{tempat}}, {{tanggal}}[br]
															{{jabatan}}[br][br][br]
															{{nama_pejabat}}[/right]
														</code>
														<script>
															const template = document.querySelector('#templateSurat').textContent;

															function parseSuratTemplate(template) {
																return template
																	.replace(/\[center\]/g, '<div style="text-align: center;">')
																	.replace(/\[\/center\]/g, '</div>')
																	.replace(/\[bold\]/g, '<strong>')
																	.replace(/\[\/bold\]/g, '</strong>')
																	.replace(/\[tab\]/g, '&nbsp;&nbsp;&nbsp;&nbsp;') // 4 spasi
																	.replace(/\[br\]/g, '<br>')
																	.replace(/\[right\]/g, '<div style="text-align: right;">')
																	.replace(/\[\/right\]/g, '</div>')
																	.replace(/\[left\]/g, '<div style="text-align: left;">')
																	.replace(/\[\/left\]/g, '</div>')
																	.replace(/\[table\]/g, '<table>')
																	.replace(/\[\/table\]/g, '</table>')
																	.replace(/\[tr\]/g, '<tr>')
																	.replace(/\[\/tr\]/g, '</tr>')
																	.replace(/\[td\]/g, '<td style="padding:2px 4px; vertical-align:top; font-weight:normal; font-family: Times New Roman, serif;">')
																	.replace(/\[\/td\]/g, '</td>')
																	.replace(/\{\{(\w+)\}\}/g, (_, key) => `{{${key}}}`);
															}

															document.addEventListener('DOMContentLoaded', function() {
																const template = parseSuratTemplate(document.querySelector('#templateSurat').textContent);
																document.querySelector('#templateSurat').innerHTML = template;
															});
														</script>
														<small class="text-info"><i class="bi bi-info-circle"></i> Klik untuk copy template lengkap</small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer bg-light">
						<button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#sopGuide">
							<i class="bi bi-info-circle"></i> Panduan SOP
						</button>
						<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
							<i class="bi bi-x-circle"></i> Batal
						</button>
						<button type="submit" class="btn btn-primary">
							<i class="bi bi-check-circle"></i> Simpan Template
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
		// Initialize variables
		let fieldCount = 0;
		let currentPreviewMode = 'desktop';
		let previewUpdateTimeout;

		// Initialize on document ready
		document.addEventListener('DOMContentLoaded', function() {
			initializePreview();
			setupEventListeners();
			// setupCopyFunctionality();

			// Create notification element
			const notification = document.createElement('div');
			notification.className = 'copy-notification';
			notification.innerHTML = '<i class="bi bi-check-circle"></i> Berhasil disalin ke clipboard!';
			document.body.appendChild(notification);

			// Add click event listeners to all copy-item elements
			$(document).on('click', '.copy-item', function(e) {
				e.preventDefault();
				const textToCopy = $(this).data('copy');
				if (textToCopy) {
					copyToClipboard(textToCopy, this);
				}
			});
		});

		// Initialize preview with enhanced CKEditor configuration
		function initializePreview() {
			// Set default preview mode
			togglePreviewMode('desktop');

			// Initialize CKEditor with comprehensive config for formal letters
			if (typeof CKEDITOR !== 'undefined') {
				CKEDITOR.replace('konten', {
					height: 350,
					// Enhanced toolbar with all essential features
					toolbar: [{
							name: 'document',
							items: ['Source', '-', 'NewPage', 'Preview', 'Print', '-', 'Templates']
						},
						{
							name: 'clipboard',
							items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
						},
						{
							name: 'editing',
							items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
						},
						'/',
						{
							name: 'basicstyles',
							items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
						},
						{
							name: 'paragraph',
							items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
						},
						{
							name: 'links',
							items: ['Link', 'Unlink', 'Anchor']
						},
						{
							name: 'insert',
							items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
						},
						'/',
						{
							name: 'styles',
							items: ['Styles', 'Format', 'Font', 'FontSize']
						},
						{
							name: 'colors',
							items: ['TextColor', 'BGColor']
						},
						{
							name: 'tools',
							items: ['Maximize', 'ShowBlocks']
						}
					],
					// Enhanced configuration for formal letters
					format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
					fontSize_sizes: '8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',
					font_names: 'Arial/Arial, Helvetica, sans-serif;Times New Roman/Times New Roman, Times, serif;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Verdana/Verdana, Geneva, sans-serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Impact/Impact, Charcoal, sans-serif;Comic Sans MS/Comic Sans MS, cursive',
					// Enhanced styles dropdown
					stylesSet: [{
							name: 'Paragraph Normal',
							element: 'p'
						},
						{
							name: 'Heading 1',
							element: 'h1'
						},
						{
							name: 'Heading 2',
							element: 'h2'
						},
						{
							name: 'Heading 3',
							element: 'h3'
						},
						{
							name: 'Heading 4',
							element: 'h4'
						},
						{
							name: 'Heading 5',
							element: 'h5'
						},
						{
							name: 'Heading 6',
							element: 'h6'
						},
						{
							name: 'Preformatted Text',
							element: 'pre'
						},
						{
							name: 'Address',
							element: 'address'
						},
						{
							name: 'Marker: Yellow',
							element: 'span',
							styles: {
								'background-color': 'Yellow'
							}
						},
						{
							name: 'Marker: Green',
							element: 'span',
							styles: {
								'background-color': 'Lime'
							}
						},
						{
							name: 'Big',
							element: 'big'
						},
						{
							name: 'Small',
							element: 'small'
						},
						{
							name: 'Computer Code',
							element: 'code'
						},
						{
							name: 'Keyboard Phrase',
							element: 'kbd'
						},
						{
							name: 'Sample Text',
							element: 'samp'
						},
						{
							name: 'Variable',
							element: 'var'
						},
						{
							name: 'Deleted Text',
							element: 'del'
						},
						{
							name: 'Inserted Text',
							element: 'ins'
						},
						{
							name: 'Cited Work',
							element: 'cite'
						},
						{
							name: 'Inline Quotation',
							element: 'q'
						}
					],
					// Table configuration
					table_fillEmptyTables: false,
					table_responsive: true,
					// Additional features
					allowedContent: true,
					extraAllowedContent: 'div(*);table(*);tr(*);td(*);th(*);tbody(*);thead(*);tfoot(*);span(*);p(*);h1(*);h2(*);h3(*);h4(*);h5(*);h6(*);ul(*);ol(*);li(*);strong(*);em(*);u(*);s(*);sub(*);sup(*);blockquote(*);pre(*);code(*);kbd(*);samp(*);var(*);del(*);ins(*);cite(*);q(*);small(*);big(*);address(*)',
					// Enhanced paste configuration
					pasteFromWordRemoveFontStyles: false,
					pasteFromWordRemoveStyles: false,
					// Auto-grow configuration
					autoGrow_onStartup: true,
					autoGrow_minHeight: 350,
					autoGrow_maxHeight: 600,
					// Event handlers with enhanced preview
					on: {
						change: function() {
							updatePreviewWithFullHTML();
						},
						key: function() {
							clearTimeout(previewUpdateTimeout);
							previewUpdateTimeout = setTimeout(updatePreviewWithFullHTML, 300);
						},
						paste: function() {
							setTimeout(updatePreviewWithFullHTML, 100);
						},
						afterInsertHtml: function() {
							updatePreviewWithFullHTML();
						}
					}
				});
			}
		}

		// Setup event listeners
		function setupEventListeners() {
			// Form input listeners for real-time preview
			const formInputs = ['nama_template', 'tipe_surat', 'header_alamat', 'header_content'];
			formInputs.forEach(function(inputName) {
				const input = document.querySelector(`[name="${inputName}"]`);
				if (input) {
					input.addEventListener('input', function() {
						clearTimeout(previewUpdateTimeout);
						previewUpdateTimeout = setTimeout(updatePreview, 300);
					});
				}
			});

			// Header checkbox listener
			$('#useHeaderCheck').on('change', function() {
				const isChecked = $(this).is(':checked');
				const $headerSection = $('#headerSection');
				const $headerContent = $('[name="header_content"]');

				$headerSection.toggle(isChecked);

				if (!isChecked) {
					$headerContent.val('');
				}

				updatePreview();
			});

			// File upload listener for logo
			const logoInput = document.querySelector('[name="header_logo"]');
			if (logoInput) {
				logoInput.addEventListener('change', function() {
					handleLogoPreview(this);
				});
			}
		}

		// Toggle preview mode (desktop/tablet/mobile)
		function togglePreviewMode(mode) {
			currentPreviewMode = mode;

			// Update button states
			document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
			document.getElementById(`preview${mode.charAt(0).toUpperCase() + mode.slice(1)}`).classList.add('active');

			// Update preview container class
			const previewPaper = document.getElementById('previewContent');
			previewPaper.className = `preview-paper ${mode}`;

			// Update preview content
			updatePreview();
		}

		// Handle logo preview
		function handleLogoPreview(input) {
			if (input.files && input.files[0]) {
				const reader = new FileReader();
				reader.onload = function(e) {
					// Store logo data for preview
					window.previewLogoData = e.target.result;
					updatePreview();
				};
				reader.readAsDataURL(input.files[0]);
			}
		}

		// Enhanced update preview function with full HTML rendering
		function updatePreview() {
			const previewContent = document.getElementById('previewContent');

			// Ambil data input form
			const formValues = {
				namaTemplate: document.querySelector('[name="nama_template"]')?.value || 'Template Surat',
				tipeTemplate: document.querySelector('[name="tipe_surat"]')?.value || 'Tipe Surat',
				useHeader: document.getElementById('useHeaderCheck')?.checked,
				headerAlamat: document.querySelector('[name="header_alamat"]')?.value || '',
				headerContent: document.querySelector('[name="header_content"]')?.value || '',
				logoPosition: document.querySelector('input[name="logo_position"]:checked')?.value || 'top',
			};

			// Ambil konten dari CKEditor (jika ada)
			let content = '';
			if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['konten']) {
				content = CKEDITOR.instances['konten'].getData();
			} else {
				content = document.querySelector('[name="konten"]')?.value || '';
			}

			// Ganti format tag khusus ke HTML
			const formatReplacements = [
				[/\[br\]/g, '<br>'],
				[/\[tab\]/g, '<span class="tab"></span>'],
				[/\[center\](.*?)\[\/center\]/gs, '<div class="center">$1</div>'],
				[/\[bold\](.*?)\[\/bold\]/gs, '<strong>$1</strong>'],
				[/\[underline\](.*?)\[\/underline\]/gs, '<u>$1</u>'],
				[/\[italic\](.*?)\[\/italic\]/gs, '<em>$1</em>'],
				[/\[right\](.*?)\[\/right\]/gs, '<div class="text-end">$1</div>'],
				[/\[left\](.*?)\[\/left\]/gs, '<div class="text-start">$1</div>'],
				[/\[justify\](.*?)\[\/justify\]/gs, '<div class="text-justify">$1</div>'],
				[/\[table\]/g, '<table>'],
				[/\[\/table\]/g, '</table>'],
				[/\[tr\]/g, '<tr>'],
				[/\[\/tr\]/g, '</tr>'],
				[/\[td\]/g, '<td style="padding:2px 4px;">'],
				[/\[\/td\]/g, '</td>']
			];

			let processedContent = content;
			for (const [pattern, replacement] of formatReplacements) {
				processedContent = processedContent.replace(pattern, replacement);
			}

			// Ganti placeholder dengan data contoh
			const placeholders = {
				'{{nama_lengkap}}': 'John Doe',
				'{{nik}}': '1234567890123456',
				'{{alamat}}': 'Jl. Contoh No. 123, RT 01/RW 02, Kelurahan Contoh',
				'{{tanggal}}': new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }),
				'{{nomor_surat}}': '001/KEL/2024',
				'{{nama_pejabat}}': 'Budi Santoso',
				'{{jabatan}}': 'Kepala Desa',
				'{{tempat}}': 'Jakarta',
				'{{nama_desa}}': 'Desa Contoh',
				'{{kecamatan}}': 'Kecamatan Contoh',
				'{{kabupaten}}': 'Kabupaten Contoh',
				'{{provinsi}}': 'Provinsi Contoh',
				'{{kode_pos}}': '12345',
				'{{telepon}}': '(021) 1234567',
				'{{email}}': 'desa@contoh.go.id',
				'{{website}}': 'www.desacontoh.go.id',
				'{{ttd_nama}}': 'Budi Santoso',
				'{{ttd_jabatan}}': 'Kepala Desa',
				'{{ttd_nip}}': '196501011990031001'
			};

			for (const [key, value] of Object.entries(placeholders)) {
				processedContent = processedContent.replace(new RegExp(key.replace(/[{}]/g, '\\$&'), 'g'), value);
			}

			// Bangun struktur preview
			let previewHTML = '';

			// HEADER
			if (formValues.useHeader) {
				const { logoPosition, namaTemplate, headerAlamat, headerContent } = formValues;
				const logoHTML = window.previewLogoData ? `<img src="${window.previewLogoData}" alt="Logo" class="logo-${logoPosition}">` : '';

				const isHorizontal = ['left', 'right'].includes(logoPosition);
				const headerClass = `preview-header ${isHorizontal ? 'horizontal' : ''}`;

				let headerInner = '';

				if (logoPosition === 'top') headerInner += `${logoHTML}`;
				headerInner += `<div class="header-content">`;

				if (logoPosition === 'left') headerInner += `${logoHTML}`;
				headerInner += `<div><h3>${namaTemplate}</h3>`;
				if (headerAlamat) headerInner += `<p>${headerAlamat}</p>`;
				if (headerContent) headerInner += `<p>${headerContent}</p>`;
				headerInner += `</div>`;
				if (logoPosition === 'right') headerInner += `${logoHTML}`;

				headerInner += `</div>`;
				if (logoPosition === 'bottom') headerInner += `${logoHTML}`;

				previewHTML += `<div class="${headerClass}">${headerInner}</div>`;
			}

			// ISI UTAMA
			previewHTML += `<div class="preview-content">`;
			previewHTML += processedContent || `<p class="text-muted text-center">Mulai mengetik untuk melihat preview...</p>`;
			previewHTML += `</div>`;

			previewContent.classList.add('preview-loading');
			setTimeout(() => {
				previewContent.innerHTML = previewHTML;
				previewContent.classList.remove('preview-loading');
			}, 100);
		}

		// Alias for backward compatibility
		function updatePreviewWithFullHTML() {
			updatePreview();
		}

		// Process format codes in content
		function processFormatCodes(content) {
			// Remove HTML tags first (from CKEditor)
			content = content.replace(/<[^>]*>/g, '');

			// Process format codes
			content = content
				.replace(/\[br\]/g, '<br>')
				.replace(/\[tab\]/g, '<span class="tab"></span>')
				.replace(/\[center\](.*?)\[\/center\]/gs, '<div class="center">$1</div>')
				.replace(/\[bold\](.*?)\[\/bold\]/gs, '<span class="bold">$1</span>')
				.replace(/\[underline\](.*?)\[\/underline\]/gs, '<span class="underline">$1</span>')
				.replace(/\n/g, '<br>');

			// Process placeholders with styling
			content = content.replace(/\{\{([^}]+)\}\}/g, '<span class="text-primary fw-bold">{{$1}}</span>');

			return content;
		}

		// Fungsi untuk reset modal
		function resetModal() {
			document.getElementById('templateForm').reset();

			// Reset CKEditor dengan pengecekan instance
			if (CKEDITOR.instances['konten']) {
				CKEDITOR.instances['konten'].setData('');
			}

			document.getElementById('field-wrapper').innerHTML = '';
			document.getElementById('templateId').value = '';
			document.getElementById('headerSection').style.display = 'none';
			document.getElementById('useHeaderCheck').checked = false;

			// Reset preview
			window.previewLogoData = null;
			updatePreview();

			fieldCount = 0; // Reset jumlah field
			document.getElementById('tambahTemplateModal').querySelector('.modal-title').innerHTML = '<i class="bi bi-file-earmark-text"></i> Tambah Template Surat';
			document.getElementById('templateForm').setAttribute('action', '<?= base_url('admin/buatsurat/konfigurasi_store') ?>');
		}

		function addField(data = {}) {
			const wrapper = document.getElementById('field-wrapper');
			const index = fieldCount++;
			const html = `
				<div class="card mb-3 position-relative border">
					<div class="card-body">
						<button type="button" class="btn-close position-absolute end-0 top-0 mt-2 me-2" onclick="this.closest('.card').remove()"></button>
						<div class="row g-2">
							<div class="col-md-6">
								<label class="form-label fw-bold">
									<i class="bi bi-tag"></i> Label Field
								</label>
								<input type="text" name="fields[${index}][label]" class="form-control" required value="${data.label || ''}" 
									placeholder="Contoh: Nomor Surat">
							</div>
							<div class="col-md-6">
								<label class="form-label fw-bold">
									<i class="bi bi-code"></i> Nama Field (untuk placeholder)
								</label>
								<input type="text" name="fields[${index}][nama_field]" class="form-control" required value="${data.nama_field || ''}" 
									placeholder="Contoh: nomor_surat">
							</div>
							<div class="col-md-4">
								<label class="form-label fw-bold">
									<i class="bi bi-ui-radios"></i> Tipe Input
								</label>
								<select name="fields[${index}][tipe_input]" class="form-control" onchange="handleTipeInputChange(this, ${index})">
									<option value="text" ${data.tipe_input === 'text' ? 'selected' : ''}>Text</option>
									<option value="textarea" ${data.tipe_input === 'textarea' ? 'selected' : ''}>Textarea</option>
									<option value="date" ${data.tipe_input === 'date' ? 'selected' : ''}>Date</option>
									<option value="select" ${data.tipe_input === 'select' ? 'selected' : ''}>Select</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label fw-bold">
									<i class="bi bi-chat-quote"></i> Placeholder
								</label>
								<input type="text" name="fields[${index}][placeholder]" class="form-control" value="${data.placeholder || ''}" 
									placeholder="Teks bantuan untuk user">
							</div>
							<div class="col-md-4">
								<label class="form-label fw-bold">
									<i class="bi bi-exclamation-triangle"></i> Wajib Diisi?
								</label>
								<select name="fields[${index}][is_required]" class="form-control">
									<option value="1" ${data.is_required ? 'selected' : ''}>Ya</option>
									<option value="0" ${!data.is_required ? 'selected' : ''}>Tidak</option>
								</select>
							</div>
							<div class="col-12 mt-2 select-opsi ${data.tipe_input === 'select' ? '' : 'd-none'}" id="select-opsi-${index}">
								<label class="form-label fw-bold">
									<i class="bi bi-list"></i> Sumber Data
								</label>
								<select name="fields[${index}][sumber_data]" class="form-control" onchange="handleSumberDataChange(this, ${index})">
									<option value="">Pilih Sumber Data</option>
									<option value="static" ${data.sumber_data === 'static' ? 'selected' : ''}>Static (Manual)</option>
									<option value="relasi" ${data.sumber_data === 'relasi' ? 'selected' : ''}>Database Relasi</option>
								</select>
								<div class="mt-2 sumber-static ${data.sumber_data === 'static' ? '' : 'd-none'}" id="sumber-static-${index}">
									<label class="form-label">
										<i class="bi bi-list-ul"></i> Opsi Static (pisahkan dengan koma)
									</label>
									<input type="text" name="fields[${index}][opsi_static]" class="form-control" 
										value="${data.opsi_static ? (Array.isArray(data.opsi_static) ? data.opsi_static.join(', ') : data.opsi_static) : ''}"
										placeholder="Contoh: Laki-laki, Perempuan">
								</div>
								<div class="mt-2 sumber-relasi ${data.sumber_data === 'relasi' ? '' : 'd-none'}" id="sumber-relasi-${index}">
									<div class="row g-2">
										<div class="col-md-4">
											<label class="form-label">
												<i class="bi bi-table"></i> Nama Tabel
											</label>
											<input type="text" name="fields[${index}][tabel_relasi]" class="form-control" value="${data.tabel_relasi || ''}"
												placeholder="Contoh: tb_jabatan">
										</div>
										<div class="col-md-4">
											<label class="form-label">
												<i class="bi bi-key"></i> Kolom Value
											</label>
											<input type="text" name="fields[${index}][kolom_value]" class="form-control" value="${data.kolom_value || ''}"
												placeholder="Contoh: id">
										</div>
										<div class="col-md-4">
											<label class="form-label">
												<i class="bi bi-card-text"></i> Kolom Label
											</label>
											<input type="text" name="fields[${index}][kolom_label]" class="form-control" value="${data.kolom_label || ''}"
												placeholder="Contoh: nama_jabatan">
										</div>
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

		function showAlert(type = 'danger', message = 'Terjadi kesalahan.') {
			const alertDiv = document.createElement('div');
			alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
			alertDiv.innerHTML = `
				<i class="bi bi-exclamation-triangle"></i> ${message}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			`;
			document.querySelector('.section .card-body')?.prepend(alertDiv);
		}

		document.getElementById('tambahTemplateModal').addEventListener('hidden.bs.modal', resetModal);
		document.getElementById('templateForm').addEventListener('submit', function(e) {
			e.preventDefault();

			// Validasi minimal
			const namaTemplate = this.querySelector('[name="nama_template"]').value.trim();
			const tipeSurat = this.querySelector('[name="tipe_surat"]').value.trim();
			let konten = '';

			// Pastikan CKEDITOR sudah terinisialisasi
			if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances['konten']) {
				konten = CKEDITOR.instances['konten'].getData().trim();
			} else {
				console.warn('CKEditor tidak ditemukan atau belum diinisialisasi untuk textarea dengan id="konten"');
			}

			// Show loading state
			const submitBtn = this.querySelector('button[type="submit"]');
			const originalText = submitBtn.innerHTML;
			submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
			submitBtn.disabled = true;

			// Submit form dengan FormData untuk handle file upload
			const formData = new FormData(this);
			// console log content from formdata
			formData.append('konten', konten); // Uncomment if you want to send konten separately

			fetch(this.action, {
					method: 'POST',
					body: formData
				})
				.then(res => res.json())
				.then(response => {
					if (response.status) {
						const modalEl = document.getElementById('tambahTemplateModal');
						if (modalEl) {
							const modal = bootstrap.Modal.getInstance(modalEl);
							modal?.hide();
						}

						const alertDiv = document.createElement('div');
						alertDiv.className = 'alert alert-success alert-dismissible fade show';
						alertDiv.innerHTML = `
						<i class="bi bi-check-circle"></i> ${response.message || 'Template berhasil disimpan!'}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					`;

						document.querySelector('.section .card-body')?.prepend(alertDiv);

						setTimeout(() => location.reload(), 1500);
					} else {
						showAlert('danger', response.message || 'Gagal menyimpan template.');
					}
				})
				.catch(error => {
					console.error('Fetch error:', error);
					showAlert('danger', 'Terjadi kesalahan koneksi. Silakan coba lagi.');
				})
				.finally(() => {
					submitBtn.innerHTML = originalText;
					submitBtn.disabled = false;
				});
		});

		// Edit template functionality
		document.addEventListener('DOMContentLoaded', function() {
			$('.btn-edit-template').on('click', function() {
				const id = $(this).data('id');

				// Reset modal sebelum isi data baru
				resetModal();

				// Show loading in modal
				const modalTitle = document.querySelector('#tambahTemplateModal .modal-title');
				modalTitle.innerHTML = '<i class="bi bi-hourglass-split"></i> Memuat data...';

				$.ajax({
					url: `<?= base_url('admin/buatsurat/konfigurasi_get/') ?>${id}`,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						// Set form action ke update
						$('#templateForm').attr('action', '<?= base_url('admin/buatsurat/konfigurasi_update') ?>');
						modalTitle.innerHTML = '<i class="bi bi-pencil"></i> Edit Template Surat';

						// Isi input dasar
						$('#templateId').val(data.template.id);
						$('[name="nama_template"]').val(data.template.nama_template);
						$('[name="tipe_surat"]').val(data.template.tipe_surat);
						$('[name="konten"]').val(data.template.konten || '');

						// Set konten dengan pengecekan CKEditor
						if (CKEDITOR.instances['konten']) {
							CKEDITOR.instances['konten'].setData(data.template.konten || '');
						}

						// Set header configuration
						if (data.template.use_header) {
							$('#useHeaderCheck').prop('checked', true);
							$('#headerSection').show();
							$('[name="header_content"]').val(data.template.header_content || '');
							$('[name="header_alamat"]').val(data.template.header_alamat || '');
						}

						// Tambah field baru
						if (Array.isArray(data.fields)) {
							data.fields.forEach((field, index) => {
								addField(field);
							});
						}

						// Update preview
						setTimeout(updatePreview, 500);

						// Tampilkan modal
						$('#tambahTemplateModal').modal('show');
					},
					error: function(xhr, status, error) {
						console.error('Error fetching template data:', error);
						alert('Gagal memuat data template. Silakan coba lagi.');
						modalTitle.innerHTML = '<i class="bi bi-file-earmark-text"></i> Tambah Template Surat';
					}
				});
			});

			// ketika ubah posisi logo
			$('input[name="logo_position"]').on('change', function() {
				updatePreview();
			});
		});

		// Copy text to clipboard with enhanced feedback
		function copyToClipboard(text, element) {
			// Use modern clipboard API if available
			if (navigator.clipboard && window.isSecureContext) {
				text = text.replace(/<[^>]*>/g, ''); // Remove HTML tags for plain text copy
				text = text.replace(/\[br\]/g, '\n'); // Convert custom line breaks to new lines
				text = text
					.replace(/\[table\]/g, '\n[table]') // buka table
					.replace(/\[\/table\]/g, '\n[/table]') // tutup table
					.replace(/\[tr\]/g, '  [tr]') // indentasi baris
					.replace(/\[\/tr\]/g, '  [/tr]') // tetap satu baris
					.replace(/\[td\]/g, '    [td]') // indentasi kolom
					.replace(/\[\/td\]/g, '[/td]') // akhiran tetap
					.replace(/\n{2,}/g, '\n'); // Ganti beberapa baris kosong dengan satu baris kosong

				navigator.clipboard.writeText(text).then(function() {
					showCopySuccess(element);
				}).catch(function(err) {
					fallbackCopyTextToClipboard(text, element);
				});
			} else {
				fallbackCopyTextToClipboard(text, element);
			}
		}

		// Fallback copy method for older browsers
		function fallbackCopyTextToClipboard(text, element) {
			const textArea = document.createElement("textarea");
			textArea.value = text;

			// Avoid scrolling to bottom
			textArea.style.top = "0";
			textArea.style.left = "0";
			textArea.style.position = "fixed";
			textArea.style.opacity = "0";

			document.body.appendChild(textArea);
			textArea.focus();
			textArea.select();

			try {
				const successful = document.execCommand('copy');
				if (successful) {
					showCopySuccess(element);
				} else {
					showCopyError();
				}
			} catch (err) {
				console.error('Fallback: Oops, unable to copy', err);
				showCopyError();
			}

			document.body.removeChild(textArea);
		}

		// Show copy success feedback
		function showCopySuccess(element) {
			// Add copied class for animation
			element.classList.add('copied');
			setTimeout(() => {
				element.classList.remove('copied');
			}, 600);

			// Show notification
			const notification = document.querySelector('.copy-notification');
			notification.classList.add('show');
			setTimeout(() => {
				notification.classList.remove('show');
			}, 2000);
		}

		// Show copy error feedback
		function showCopyError() {
			const notification = document.querySelector('.copy-notification');
			notification.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Gagal menyalin ke clipboard!';
			notification.style.background = '#dc3545';
			notification.classList.add('show');
			setTimeout(() => {
				notification.classList.remove('show');
				// Reset to success style
				setTimeout(() => {
					notification.innerHTML = '<i class="bi bi-check-circle"></i> Berhasil disalin ke clipboard!';
					notification.style.background = '#28a745';
				}, 300);
			}, 2000);
		}

		// Konfirmasi delete dengan SweetAlert2
		function konfirmasiDelete(url) {
			Swal.fire({
				title: 'Konfirmasi Hapus',
				text: 'Apakah Anda yakin ingin menghapus template surat ini? Tindakan ini tidak dapat dibatalkan.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus!',
				cancelButtonText: '<i class="bi bi-x-circle"></i> Batal',
				customClass: {
					confirmButton: 'btn btn-danger',
					cancelButton: 'btn btn-secondary'
				},
				buttonsStyling: false
			}).then((result) => {
				if (result.isConfirmed) {
					// Show loading
					Swal.fire({
						title: 'Menghapus...',
						text: 'Sedang menghapus template surat',
						icon: 'info',
						allowOutsideClick: false,
						showConfirmButton: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});

					// Redirect to delete URL
					window.location.href = url;
				}
			});
		}
	</script>
</main>
