 <main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengaturan Aplikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Pengaturan Aplikasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pengaturan Aplikasi</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Toggle Status Aplikasi -->
                        <div class="mb-4">
                            <h6>Status Aplikasi</h6>
                            <form action="<?= base_url('admin/pengaturan/update_status') ?>" method="post"
                                id="statusForm">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle" name="status"
                                        <?= ($pengaturan->status_aplikasi == 1) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusToggle">
                                        <?= ($pengaturan->status_aplikasi == 1) ? 'Aplikasi Aktif' : 'Aplikasi Nonaktif' ?>
                                    </label>
                                </div>
                            </form>
                        </div>

                        <!-- Backup Database -->
                        <div class="mb-4">
    <h6>Backup Database</h6>
    <button id="backupBtn" class="btn btn-primary">
        <i class="bi bi-download"></i> Backup Database
    </button>
</div>
                        
                        <!-- Hapus Data Warga -->
<div class="mb-4">
    <h6>Hapus Semua Data Warga</h6>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        <i class="bi bi-trash"></i> Hapus Semua Data Warga
    </button>
</div>


                        <!-- Restore Database -->
                        <!--<div class="mb-4">-->
                        <!--    <h6>Restore Database</h6>-->
                        <!--    <form action="<?= base_url('admin/pengaturan/restore_database') ?>" method="post"-->
                        <!--        enctype="multipart/form-data">-->
                        <!--        <div class="input-group">-->
                        <!--            <input type="file" class="form-control" name="backup_file" accept=".gz" required>-->
                        <!--            <button type="submit" class="btn btn-warning">-->
                        <!--                <i class="bi bi-upload"></i> Restore Database-->
                        <!--            </button>-->
                        <!--        </div>-->
                        <!--    </form>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- Modal Konfirmasi Ubah Status -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmStatusModalLabel">Konfirmasi Ubah Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengubah status aplikasi?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatusButton">Ya, Ubah</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Data -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/warga/verifikasi_hapus') ?>" method="post">
                <div class="modal-body">
                    <p>Silakan masukkan password Anda untuk mengonfirmasi penghapusan semua data warga.</p>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan Password" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Script untuk Toggle Status -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusToggle = document.getElementById('statusToggle');
    const statusForm = document.getElementById('statusForm');
    const confirmStatusModal = new bootstrap.Modal(document.getElementById('confirmStatusModal'));
    const confirmStatusButton = document.getElementById('confirmStatusButton');

    // Simpan status toggle sebelumnya
    let previousStatus = statusToggle.checked;

    statusToggle.addEventListener('change', function() {
        // Tampilkan modal konfirmasi
        confirmStatusModal.show();
    });

    // Jika pengguna membatalkan perubahan
    confirmStatusModal._element.addEventListener('hide.bs.modal', function() {
        // Kembalikan toggle ke status sebelumnya
        statusToggle.checked = previousStatus;
    });

    // Jika pengguna mengonfirmasi perubahan
    confirmStatusButton.addEventListener('click', function() {
        // Submit form untuk mengubah status
        statusForm.submit();
    });
});
</script>