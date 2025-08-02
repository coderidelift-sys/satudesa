<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Dusun</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Data Dusun</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Dusun</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- Button to Add New Kontak Staff -->
                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addDusunModal">   <i class="bi bi-plus-circle"></i>
                            Tambah Dusun
                        </button>

                        <!-- Table to Display Kontak Staff Data -->
                        <div class="table-responsive">
                            <table id="dataDusunTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                   <tr>
                                    <th>No</th>
                                    <th>Nama Dusun</th>
                                    <th>Nama Kadus</th>
                                    <th>Total RW</th>
                                    <th>Total RT</th>
                                    <th>Created At</th>
                                    <th>Aksi</th>
                                  </tr>
                                </thead>
                            </table>
                            </div>
                           </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- Add Kontak Staff Modal -->
<div class="modal fade" id="addDusunModal" tabindex="-1" aria-labelledby="addDusunModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDusunModalLabel">Tambah Dusun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/dusun/add') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_dusun" class="form-label">Nama Dusun</label>
                        <input type="text" class="form-control" name="nama_dusun" required>
                    </div>
                     <div class="mb-3">
                        <label for="nama_kadus" class="form-label">Nama Kepala Dusun</label>
                        <input type="text" class="form-control" name="nama_kadus" required>
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