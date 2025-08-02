<main id="main" class="main">
    <div class="pagetitle">
        <h1>Akses Data Warga</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Akses Data</a></li>
                <li class="breadcrumb-item active">Dekripsi Nomor KK</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Masukkan Kunci Akses</h5>

                        <?php if ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Kunci Akses</label>
                                <input type="password" class="form-control" name="kunci_akses" required>
                            </div>

                            <!-- Tombol Buka Akses & Kunci Akses -->
                            <button type="submit" formaction="<?= base_url('admin/aksesdata/buka_akses') ?>" class="btn btn-success w-100 mb-2">BUKA AKSES</button>
                            <button type="submit" formaction="<?= base_url('admin/aksesdata/kunci_akses') ?>" class="btn btn-danger w-100">KUNCI AKSES</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
