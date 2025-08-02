<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Wisata</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data Wisata</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Wisata</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Button to Add New Wisata -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addWisataModal">
                            <i class="bi bi-plus-circle"></i>
                            Tambah Wisata
                        </button>

                        <!-- Table to Display Wisata Data -->
                        <div class="table-responsive">
                            <table id="dataWisataTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Wisata</th>
                                        <th>Deskripsi</th>
                                        <th>Lokasi</th>
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

<!-- Add Wisata Modal -->
<div class="modal fade" id="addWisataModal" tabindex="-1" aria-labelledby="addWisataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWisataModalLabel">Tambah Wisata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/wisata/add') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_wisata" class="form-label">Nama Wisata</label>
                        <input type="text" class="form-control" name="nama_wisata" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required
                            placeholder="Jelasakan tentang wisata..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi <a
                                href="https://maps.app.goo.gl/dDvBUF44CnxY2ztp7" target="_blank">Maps</a><sup> Buka
                                Maps, lalu share dan copy link nya ke kolom lokasi.</sup></label>
                        <input type="text" class="form-control" name="lokasi" required>
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