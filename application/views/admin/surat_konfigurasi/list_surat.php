<main id="main" class="main">
	<div class="pagetitle">
		<h1>Data Pengajuan Surat</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Surat</a></li>
				<li class="breadcrumb-item active">Data Surat</li>
			</ol>
		</nav>
	</div><!-- End Page Title -->

	<section class="section">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Daftar Pengajuan Surat</h5>
						<!-- Menampilkan pesan sukses -->
						<?php if ($this->session->flashdata('success')): ?>
							<div class="alert alert-success">
								<?php echo $this->session->flashdata('success'); ?>
								<br>
							</div>
						<?php endif; ?>
						<?php if ($this->session->flashdata('error')): ?>
							<div class="alert alert-danger">
								<?php echo $this->session->flashdata('error'); ?>
								<br>
							</div>
						<?php endif; ?>
						<ul class="list-unstyled">
							<li>
								<ul class="list-unstyled">
									<li>
										<div class="alert alert-primary alert-dismissible fade show" role="alert"
											style="padding: 8px 12px; margin-bottom: 5px; font-size: 14px;">
											<h5 class="alert-heading" style="margin-bottom: 4px; font-size: 16px;">
												Informasi Penting!</h5>
											<ul style="margin-left: 15px; padding-left: 20px;">
												<li>Untuk menampilkan tombol Whatsapp, pastikan status surat sudah
													<strong>SELESAI</strong>.
												</li>
											</ul>
											<button type="button" class="btn-close" data-bs-dismiss="alert"
												aria-label="Close"></button>
										</div>
									</li>
								</ul>

							</li>
						</ul>

						<a class="btn btn-primary btn-sm" href="<?= base_url('admin/buatsurat') ?>">
							<i class="bi bi-plus-circle"></i> Tambah Pengajuan Surat
						</a>

						<!-- Table to Display Pengajuan Data -->
						<div class="table-responsive mt-3">
							<table class="table datatable">
								<thead>
									<tr>
										<th>No</th>
										<th>No Pengajuan</th>
										<th>NIK</th>
										<th>Jenis Surat</th>
										<th>No Whatsapp</th>
										<th>Tgl Pengajuan</th>
										<th>Status</th>
										<th>Tgl Update</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1; ?>
									<?php foreach ($surat as $item) : ?>
										<tr>
											<td><?= $no++ ?></td>
											<td><?= $item['no_pengajuan'] ?></td>
											<td><?= $item['nik'] ?></td>
											<td><?= $item['jenis_surat'] ?></td>
											<td><?= $item['no_wa'] ?? '<span class="text-muted">-</span>' ?></td>

											<td><?= date('d-m-Y', strtotime($item['tgl_pengajuan'])) ?></td>

											<!-- Status -->
											<td>
												<?php if ($item['status'] === 'Pending') : ?>
													<span class="badge bg-warning text-dark">Pending</span>
												<?php elseif ($item['status'] === 'Diproses') : ?>
													<span class="badge bg-primary">Diproses</span>
												<?php elseif ($item['status'] === 'Selesai') : ?>
													<span class="badge bg-success">Selesai</span>
												<?php endif; ?>
											</td>

											<td><?= date('d-m-Y H:i:s', strtotime($item['tgl_update'])) ?></td>

											<!-- Aksi -->
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="bi bi-gear"></i> Aksi
													</button>
													<ul class="dropdown-menu">
														<?php if ($item['status'] === 'Pending') : ?>
															<li>
																<a class="dropdown-item" href="<?= base_url('admin/buatsurat/edit/' . urlencode($item['id_alias'])) ?>">
																	<i class="bi bi-pencil"></i> Edit Pengajuan
																</a>
															</li>
														<?php endif; ?>

														<?php if ($item['status'] != 'Selesai') : ?>
															<li>
																<a class="dropdown-item ubah-status" href="javascript:void(0)" data-id="<?= $item['id_alias'] ?>" data-status="<?= $item['status'] ?>">
																	<i class="bi bi-pencil"></i> Ubah Status Pengajuan
																</a>
															</li>
														<?php endif; ?>

														<li>
															<a href="javascript:void(0)" class="dropdown-item delete-surat" data-id="<?= $item['id_alias'] ?>">
																<i class="bi bi-trash"></i> Hapus
															</a>
														</li>

														<?php if ($item['status'] === 'Selesai') : ?>
															<li>
																<a href="javascript:void(0)" class="dropdown-item" onclick="konfirmasiKirimWa('<?= base_url('admin/buatsurat/kirim_surat/' . urlencode($item['id_alias'])) ?>')">
																	<i class="bi bi-whatsapp"></i> Kirim WA
																</a>
															</li>
														<?php endif; ?>

														<!-- File -->
														<li>
															<?php if ($item['file']) : ?>
																<a href="<?= base_url($item['file']) ?>" target="_blank" class="dropdown-item">
																	<i class="bi bi-download"></i> Unduh File
																</a>
															<?php else : ?>
																<a href="javascript:void(0)" class="dropdown-item disabled">
																	<i class="bi bi-download"></i> Tidak Ada File
																</a>
															<?php endif; ?>
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

	<div class="modal fade" id="editSuratModal" tabindex="-1"
		aria-labelledby="editSuratLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editSuratLabel">Edit Status Pengajuan
						Surat</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"
						aria-label="Close"></button>
				</div>
				<form action="<?= base_url('admin/buatsurat/update_status') ?>"
					method="post">
					<div class="modal-body">
						<input type="hidden" name="id" value="">

						<div class="mb-3">
							<label for="status" class="form-label">Status Pengajuan
								Surat</label>
							<select name="status" class="form-control" required>
								<option value="">Pilih Status</option>
								<option value="Pending">Pending</option>
								<option value="Diproses">Diproses</option>
								<option value="Selesai">Selesai</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary"
							data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Simpan
							Perubahan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main><!-- End #main -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
	$(function() {
		$btnDeleteSurat = $('.delete-surat');
		$btnDeleteSurat.on('click', function(e) {
			e.preventDefault();
			const id = $(this).data('id');
			const url = `<?= base_url('admin/buatsurat/delete_surat/') ?>${id}`;

			Swal.fire({
				title: 'Apakah Anda yakin?',
				text: "Data yang dihapus tidak bisa dikembalikan!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url,
						type: 'POST',
						dataType: 'json',
						success: function(response) {
							if (response.success) {
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: response.message || 'Data berhasil dihapus.',
									confirmButtonText: 'OK'
								}).then((result) => {
									if (result.isConfirmed) {
										location.reload(); // fallback biasa jika bukan DataTable
									}
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Gagal',
									text: response.message || 'Terjadi kesalahan saat menghapus data.'
								});
							}
						},
						error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: 'Terjadi kesalahan saat menghapus data.'
							});
						}
					});
				}
			})
		});

		$btnUbahStatus = $('.ubah-status');
		$editSuratModal = $('#editSuratModal');
		$btnUbahStatus.on('click', function(e) {
			e.preventDefault();
			const id = $(this).data('id'),
				status = $(this).data('status');
			
			$editSuratModal.find('input[name="id"]').val(id);
			$editSuratModal.find('select[name="status"]').val(status);
			$editSuratModal.find('select[name="status"]').trigger('change');

			// Show the modal
			$editSuratModal.modal('show');
		});
	})
</script>