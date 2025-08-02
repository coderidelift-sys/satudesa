<main id="main" class="main">
    <div class="pagetitle">
        <h1>Update Profil Desa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Profil Desa</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Update Profil Desa</h5>

                        <!-- Display success/error messages -->
                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <!-- General Form Elements -->
                        <form action="<?= base_url('admin/profil/update') ?>" method="post"
                            enctype="multipart/form-data">
                            <!-- INFO UTAMA DESA -->
                            <div class="row mb-3">
                                <label for="nama_desa" class="col-sm-2 col-form-label">Nama Desa</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_desa" value="<?= isset($profil->nama_desa) ? htmlspecialchars($profil->nama_desa) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama_kecamatan" class="col-sm-2 col-form-label">Kecamatan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_kecamatan" value="<?= isset($profil->nama_kecamatan) ? htmlspecialchars($profil->nama_kecamatan) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama_kabupaten" class="col-sm-2 col-form-label">Kabupaten</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_kabupaten" value="<?= isset($profil->nama_kabupaten) ? htmlspecialchars($profil->nama_kabupaten) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama_propinsi" class="col-sm-2 col-form-label">Provinsi</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_propinsi" value="<?= isset($profil->nama_propinsi) ? htmlspecialchars($profil->nama_propinsi) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kode_pos" class="col-sm-2 col-form-label">Kode Pos</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kode_pos" value="<?= isset($profil->kode_pos) ? htmlspecialchars($profil->kode_pos) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kode_desa" class="col-sm-2 col-form-label">Kode Desa</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kode_desa" value="<?= isset($profil->kode_desa) ? htmlspecialchars($profil->kode_desa) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="luas_wilayah" class="col-sm-2 col-form-label">Luas Wilayah (km²)</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="luas_wilayah" value="<?= isset($profil->luas_wilayah) ? htmlspecialchars($profil->luas_wilayah) : '' ?>" step="0.01" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama_kades" class="col-sm-2 col-form-label">Nama Kepala Desa</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_kades" value="<?= isset($profil->nama_kades) ? htmlspecialchars($profil->nama_kades) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nikp" class="col-sm-2 col-form-label">NIK Kepala Desa</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nikp" value="<?= isset($profil->nikp) ? htmlspecialchars($profil->nikp) : '' ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="masa_jabatan" class="col-sm-2 col-form-label">Masa Jabatan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="masa_jabatan" value="<?= isset($profil->masa_jabatan) ? htmlspecialchars($profil->masa_jabatan) : '' ?>" required placeholder="Misal: 2020 - 2026">
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="tentang" class="col-sm-2 col-form-label">Tentang Desa</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="tentang"
                                        rows="5"><?= $profil->tentang ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="alamat_desa" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="alamat_desa"
                                        value="<?= $profil->alamat_desa ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="telepon"
                                        value="<?= $profil->telepon ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" value="<?= $profil->email ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="situs_desa" class="col-sm-2 col-form-label">Situs Resmi</label>
                                <div class="col-sm-10">
                                    <input type="situs_desa" class="form-control" name="situs_desa" value="<?= $profil->situs_desa ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="foto">
                                    <input type="hidden" name="old_foto" value="<?= $profil->foto ?>">
                                    <?php if ($profil->foto) : ?>
                                    <img src="<?= base_url('assets/profil/' . $profil->foto) ?>" width="100"
                                        class="mt-2">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="lokasi" class="col-sm-2 col-form-label">Lokasi (Google Maps)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lokasi"
                                        value="<?= $profil->lokasi ?>">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form><!-- End General Form Elements -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->