<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Regulasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data Regulasi Layanan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Regulasi Layanan</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Button to Add New Regulasi -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addRegulasiModal">
                            <i class="bi bi-plus-circle"></i> Tambah Regulasi
                        </button>

                        <!-- Table to Display Regulasi Data -->
                        <div class="table-responsive">
                              <table id="dataRegulasiTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Regulasi</th>
                                        <th>Deskripsi</th>
                                        <th>Persyaratan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- Add Regulasi Modal -->
<div class="modal fade" id="addRegulasiModal" tabindex="-1" aria-labelledby="addRegulasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRegulasiModalLabel">Tambah Regulasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/regulasi/add') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_regulasi" class="form-label">Judul Regulasi</label>
                        <input type="text" class="form-control" name="judul_regulasi" placeholder="ex: Surat Pengantar Desa dll" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="persyaratan" class="form-label">Persyaratan *Enter untuk penomoran</label>
                        <textarea class="form-control" name="persyaratan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
