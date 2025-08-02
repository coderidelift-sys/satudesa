<main id="main" class="main">
    <div class="pagetitle">
        <h1>Update Struktur & Visi Misi Desa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Struktur & Visi Misi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Display success/error messages -->
                <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                <?php endif; ?>

                <!-- Form Update Struktur Organisasi -->
                <div class="card p-3 mb-4">
                    <h5><b>Update Struktur Organisasi Desa</b></h5>
                    <form action="<?= base_url('admin/struktur/update_struktur') ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label for="foto_struktur" class="col-sm-2 col-form-label">Foto Struktur</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="file" name="foto_struktur">
                                <input type="hidden" name="old_foto_struktur" value="<?= $struktur->foto_struktur ?>">
                                <?php if ($struktur->foto_struktur) : ?>
                                <img src="<?= base_url('assets/foto_struktur/' . $struktur->foto_struktur) ?>"
                                    width="200" class="mt-2">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Struktur</button>
                        </div>
                    </form>
                </div>

                <!-- Form Update Visi Misi -->
                <div class="card p-3 mb-4">
                    <h5><b>Update Visi Misi Desa</b></h5>
                    <form action="<?= base_url('admin/struktur/update_visi_misi') ?>" method="post">
                        <div class="row mb-3">
                            <label for="visi" class="col-sm-2 col-form-label">Visi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="visi" rows="3"><?= $visi_misi->visi ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="misi" class="col-sm-2 col-form-label">Misi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="misi" rows="5"><?= $visi_misi->misi ?></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Visi Misi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->