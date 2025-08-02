<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Produk</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Menu</a></li>
                <li class="breadcrumb-item active">Shopping</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Produk</h5>

                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addProdukModal">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </button>

                        <div class="table-responsive">
                            <table id="dataShopTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Satuan</th>
                                        <th>Penjual</th>
                                        <th>WA</th>
                                        <th>Alamat</th>
                                        <th>Gambar</th>
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
</main>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="addProdukModal" tabindex="-1" aria-labelledby="addProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/shopping/add') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" required>
                        </div>
                        <div class="col">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>
                        <div class="col">
                            <label>Satuan (Misal Kg,Meter Dll)</label>
                            <input type="text" class="form-control" name="satuan" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Nama Penjual</label>
                            <input type="text" class="form-control" name="nama_penjual" required>
                        </div>
                        <div class="col">
                            <label>No. WhatsApp</label>
                            <input type="text" class="form-control" name="no_wa" required>
                        </div>
                        <div class="col">
                            <label>Alamat Penjual</label>
                            <input type="text" class="form-control" name="alamat_penjual" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Gambar Produk</label>
                        <input type="file" class="form-control" name="gambar" accept="image/*" required>
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

