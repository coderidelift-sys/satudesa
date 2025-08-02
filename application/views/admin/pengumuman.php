<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Pengumuman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data Pengumuman</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengumuman</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Button to Add New Pengumuman -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addPengumumanModal">
                            <i class="bi bi-plus-circle"></i> 
                            Tambah Pengumuman
                        </button>

                        <!-- Table to Display Pengumuman Data -->
                        <div class="table-responsive">
                            <table id="dataPengumumanTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Pengumuman</th>
                                        <th>Lokasi</th>
                                        <th>Waktu</th>
                                        <th>Maps</th>
                                         <th>Link</th>
                                        <th>Lampiran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<div class="modal fade" id="addPengumumanModal" tabindex="-1" aria-labelledby="addPengumumanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-lg"> <!-- ubah di sini -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPengumumanModalLabel">Tambah Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= base_url('admin/pengumuman/add') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Isi Pengumuman</label>
                            <textarea class="form-control" name="isi" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="datetime-local" class="form-control" name="waktu" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maps</label>
                            <input type="text" class="form-control" name="maps" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lampiran</label>
                            <input type="file" class="form-control" name="lampiran">
                        </div>
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
