<!-- File: application/views/admin/pembaruan/index.php -->
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Pembaruan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data Pembaruan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pembaruan</h5>

                        <!-- Tampilkan pesan sukses/error -->
                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Tombol Tambah Data -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#addPembaruanModal">   <i class="bi bi-plus-circle"></i>
                            Tambah Pembaruan
                        </button>

                        <!-- Tabel Data Pembaruan -->
                        <div class="table-responsive">
                           <table id="dataPembaruanTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Fitur</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Dibuat</th>
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

<!-- Add Pembaruan Modal -->
<div class="modal fade" id="addPembaruanModal" tabindex="-1" aria-labelledby="addPembaruanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPembaruanModalLabel">Tambah Pembaruan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/pembaruan/add') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_fitur" class="form-label">Nama Fitur</label>
                        <input type="text" class="form-control" name="nama_fitur" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
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