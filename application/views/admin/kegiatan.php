<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Kegiatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data Kegiatan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Kegiatan</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Button to Add New Kegiatan -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addKegiatanModal">   <i class="bi bi-plus-circle"></i>
                            Tambah Kegiatan
                        </button>

                        <!-- Table to Display Kegiatan Data -->
                        <div class="table-responsive">
                              <table id="dataKegiatanTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Kegiatan</th>
                                        <th>Lokasi</th>
                                        <th>Waktu</th>
                                        <th>Maps</th>
                                        <th>Lampiran</th>
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
<!-- Add Kegiatan Modal -->
<div class="modal fade" id="addKegiatanModal" tabindex="-1" aria-labelledby="addKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Gunakan modal-xl agar lebih lebar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKegiatanModalLabel">Tambah Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/kegiatan/add') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" name="judul" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lokasi" class="form-label">Lokasi <sup>Masukan nama lokasi/tempat</sup></label>
                                <input type="text" class="form-control" name="lokasi" required>
                            </div>

                            <div class="col-md-6">
                                <label for="waktu" class="form-label">Waktu</label>
                                <input type="datetime-local" class="form-control" name="waktu" required>
                            </div>
                            <div class="col-md-6">
                                <label for="maps" class="form-label">Maps (Optional)<a href="https://maps.app.goo.gl/dDvBUF44CnxY2ztp7" target="_blank">Maps</a><sup> Copy link dari maps ke sini</sup></label>
                                <input type="text" class="form-control" name="maps" value="-" required>
                            </div>

                            <div class="col-md-6">
                                <label for="lampiran" class="form-label">Lampiran (Optional)</label>
                                <input type="file" class="form-control" name="lampiran">
                            </div>
                            <div class="col-md-12">
                                <label for="isi" class="form-label">Isi Kegiatan</label>
                                <textarea class="form-control" name="isi" rows="3" required></textarea>
                            </div>
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
