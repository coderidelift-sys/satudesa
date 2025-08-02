<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengaturan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Pengaturan</li>
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

                        <!-- General Form Elements -->
                        <form action="<?= base_url('admin/aplikasi/update') ?>" method="post"
                            enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="nama_aplikasi" class="col-sm-2 col-form-label">Nama Aplikasi</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_aplikasi"
                                        value="<?= $aplikasi->nama_aplikasi ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nama_kepanjangan" class="col-sm-2 col-form-label">Nama Kepanjangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_kepanjangan"
                                        value="<?= $aplikasi->nama_kepanjangan ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="logo_aplikasi" class="col-sm-2 col-form-label">Logo Aplikasi</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="logo_aplikasi">
                                    <input type="hidden" name="old_logo" value="<?= $aplikasi->logo_aplikasi ?>">
                                    <?php if ($aplikasi->logo_aplikasi) : ?>
                                    <img src="<?= base_url('assets/aplikasi/' . $aplikasi->logo_aplikasi) ?>"
                                        width="100" class="mt-2">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                            </div>
                        </form><!-- End General Form Elements -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->