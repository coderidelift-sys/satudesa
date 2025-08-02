<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data UMKM</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data UMKM</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar UMKM</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Button to Add New UMKM -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addUmkmModal">
                            <i class="bi bi-plus-circle"></i>
                            Tambah UMKM
                        </button>

                        <!-- Table to Display UMKM Data -->
                        <div class="table-responsive">
                             <table id="dataUmkmTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">

                                    <tr>
                                        <th>No</th>
                                        <th>Nama Usaha</th>
                                        <th>Deskripsi</th>
                                        <th>Telepon</th>
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

<!-- Add UMKM Modal -->
<div class="modal fade" id="addUmkmModal" tabindex="-1" aria-labelledby="addUmkmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUmkmModalLabel">Tambah UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/umkm/add') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Nama Usaha</label>
                        <input type="text" class="form-control" name="nama_usaha" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required placeholder="Jelasakan tentang usaha..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Telepon Telepon (Misal: 62856***)</label>
                        <input type="number" class="form-control" name="telepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi" placeholder="Misal : Dusun Langkob" required>
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