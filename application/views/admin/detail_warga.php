<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Data Warga</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/warga'); ?>">Data Warga</a></li>
                <li class="breadcrumb-item active">Detail Data Warga</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nomor KK: <?= $nomor_kk; ?> | <?= $kepala_keluarga; ?>
                        </h5>
                                                <!-- Success/Error Messages -->
                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                        <?php endif; ?>
                        
                        <!-- Tombol Tambah Anggota Keluarga -->
                        <button type="button" class="btn btn-primary mb-1 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahAnggotaModal">
                            <i class="bi bi-plus-circle"></i> Tambah Anggota
                        </button>
                        <!-- Dropdown Download -->
                        <div class="btn-group mb-1 btn-sm">
                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-download"></i> Download
                            </button>
                            <ul class="dropdown-menu">
         <li>
    <button class="dropdown-item download-excel-anggota" data-nokk="<?= $nomor_kk ?>">
        <i class="bi bi-file-earmark-excel"></i> Download Data (Excel)
    </button>
</li>
<li>
    <button class="dropdown-item download-pdf-anggota" data-nokk="<?= $nomor_kk ?>">
        <i class="bi bi-file-earmark-pdf"></i> Download PDF
    </button>
</li>

                            </ul>
                        </div>



                        <!-- Data Anggota Keluarga Table -->
                        <div class="table-responsive">
                         <table id="dataDetailTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Gender</th>
                                        <th>Agama</th>
                                        <th>Status</th>
                                        <th>Posisi</th>
                                        <th>Pekerjaan</th>
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

<!-- Modal Edit -->
<?php foreach ($anggota_keluarga as $anggota): ?>
<div class="modal fade" id="editAnggotaModal<?= $anggota->id; ?>" tabindex="-1" aria-labelledby="editAnggotaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/warga/edit_anggota'); ?>" method="post">
            <input type="hidden" name="id" value="<?= $anggota->id; ?>">
            <input type="hidden" name="nomor_kk" value="<?= $anggota->nomor_kk; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAnggotaLabel">Edit Anggota Keluarga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $anggota->id; ?>">
                    <div class="mb-3">
                        <label for="nik<?= $anggota->id; ?>" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik<?= $anggota->id; ?>" name="nik"
                            value="<?= $anggota->nik; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap<?= $anggota->id; ?>" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap<?= $anggota->id; ?>"
                            name="nama_lengkap" value="<?= $anggota->nama_lengkap; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_lahir<?= $anggota->id; ?>" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tgl_lahir<?= $anggota->id; ?>" name="tgl_lahir"
                            value="<?= $anggota->tgl_lahir; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender<?= $anggota->id; ?>" class="form-label">Gender</label>
                        <select class="form-select" id="gender<?= $anggota->id; ?>" name="gender" required>
                            <option value="Laki-laki" <?= $anggota->gender == 'Laki-laki' ? 'selected' : ''; ?>>
                                Laki-laki</option>
                            <option value="Perempuan" <?= $anggota->gender == 'Perempuan' ? 'selected' : ''; ?>>
                                Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="agama<?= $anggota->id; ?>" class="form-label">Agama</label>
                        <select class="form-select" id="agama<?= $anggota->id; ?>" name="agama" required>
                            <option value="Islam" <?= $anggota->agama == 'Islam' ? 'selected' : ''; ?>>
                                Islam</option>
                            <option value="Kristen" <?= $anggota->agama == 'Kristen' ? 'selected' : ''; ?>>
                                Kristen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status<?= $anggota->id; ?>" class="form-label">Status Kawin</label>
                        <select class="form-select" id="status_kawin<?= $anggota->id; ?>" name="status_kawin" required>
                            <option value="Sudah Menikah"
                                <?= $anggota->status_kawin == 'Sudah Menikah' ? 'selected' : ''; ?>>
                                Sudah Menikah</option>
                            <option value="Belum Menikah"
                                <?= $anggota->status_kawin == 'Belum Menikah' ? 'selected' : ''; ?>>
                                Belum Menikah</option>
                            <option value="Menikah Cerai"
                                <?= $anggota->status_kawin == 'Menikah Cerai' ? 'selected' : ''; ?>>
                                Menikah Cerai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="posisi<?= $anggota->id; ?>" class="form-label">Posisi</label>
                        <select class="form-select" id="posisi<?= $anggota->id; ?>" name="posisi" required>
                            <option value="Kepala Rumah Tangga"
                                <?= $anggota->posisi == 'Kepala Rumah Tangga' ? 'selected' : ''; ?>>Kepala Rumah Tangga
                            </option>
                            <option value="Istri" <?= $anggota->posisi == 'Istri' ? 'selected' : ''; ?>>Istri</option>
                            <option value="Anak" <?= $anggota->posisi == 'Anak' ? 'selected' : ''; ?>>Anak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pekerjaan<?= $anggota->id; ?>" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan<?= $anggota->id; ?>" name="pekerjaan"
                            value="<?= $anggota->pekerjaan; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>


<!-- Modal Tambah Anggota -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/warga/tambah_anggota'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahAnggotaLabel">Tambah Anggota Keluarga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="nomor_kk" value="<?= $nomor_kk; ?>">
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" placeholder="Wajib 16 Angka"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama</label>
                        <select class="form-select" id="agama" name="agama" required>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status_kawin" name="status_kawin" required>
                            <option value="Belum Menikah">Belum Menikah</option>
                            <option value="Sudah Menikah">Sudah Menikah</option>
                            <option value="Menikah Cerai">Menikah Cerai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="posisi" class="form-label">Posisi</label>
                        <select class="form-select" id="posisi" name="posisi" required>
                            <option value="Kepala Rumah Tangga">Kepala Rumah Tangga</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<?php foreach ($anggota_keluarga as $anggota): ?>
<div class="modal fade" id="hapusAnggotaModal<?= $anggota->id; ?>" tabindex="-1" aria-labelledby="hapusAnggotaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/warga/hapus_anggota'); ?>" method="post">
            <input type="hidden" name="id" value="<?= $anggota->id; ?>">
            <input type="hidden" name="nomor_kk" value="<?= $anggota->nomor_kk; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusAnggotaLabel">Hapus Anggota Keluarga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $anggota->id; ?>">
                    <p>Apakah Anda yakin ingin menghapus <strong><?= $anggota->nama_lengkap; ?></strong> dari daftar
                        anggota keluarga?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>