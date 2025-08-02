<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Warga</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Data Warga</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Warga [Per Kartu Keluarga]</h5>

                        <!-- Success/Error Messages -->
                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                        <?php endif; ?>

                        <!-- Tombol Tambah Warga -->
                        <button type="button" class="btn btn-primary btn-sm mb-1" data-bs-toggle="modal"
                            data-bs-target="#tambahWargaModal">
                            <i class="bi bi-plus-circle"></i> Tambah Warga
                        </button>

                        <!-- Dropdown untuk Download & Upload (Ukuran Kecil) -->
                        <div class="btn-group btn-sm mb-1">
                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-arrow-down-up"></i> Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-sm">
                                <!-- Download Options -->
                                <li class="dropdown-header">Download</li>
        <li>
    <button id="downloadExcelBtn" class="dropdown-item">
        <i class="bi bi-file-earmark-excel"></i> Download Data (Excel)
    </button>
</li>
<li>
    <button id="downloadPdfBtn" class="dropdown-item">
        <i class="bi bi-file-earmark-pdf"></i> Download Data (Pdf)
    </button>
</li>

                                    <hr class="dropdown-divider">
                                </li>
                                <!-- Upload Options -->
                                <li class="dropdown-header">Upload</li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#importModal">
                                        <i class="bi bi-upload"></i> Upload [Per KK]
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#importModalAnggota">
                                        <i class="bi bi-upload"></i> Upload Anggota
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- View: admin/warga.php -->
                        <div class="table-responsive">
                            <table id="dataWargaTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor KK</th>
                                        <th>Kepala Keluarga</th>
                                        <th>Alamat</th>
                                        <th>RT</th>
                                        <th>RW</th>
                                        <th>Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Kota</th>
                                        <th>Kode Pos</th>
                                        <th>Propinsi</th>
                                        <th>Anggota</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>

<!-- Modal Edit Warga (Hanya 1x) -->
<div class="modal fade" id="editWargaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEditWarga" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Warga</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Nomor KK</label>
              <input type="text" class="form-control" name="nomor_kk" id="edit_nomor_kk" required>
            </div>
            <div class="col-md-6">
              <label>Nama Kepala Keluarga</label>
              <input type="text" class="form-control" name="kepala_keluarga" id="edit_kepala_keluarga" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Dusun</label>
              <select class="form-select" name="alamat" id="edit_alamat" required>
                <option value="">-- Pilih Dusun --</option>
                <?php foreach ($dusun as $d): ?>
                  <option value="<?= $d->nama_dusun ?>"><?= $d->nama_dusun ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label>RT</label>
              <input type="number" class="form-control" name="rt" id="edit_rt" required>
            </div>
            <div class="col-md-3">
              <label>RW</label>
              <input type="number" class="form-control" name="rw" id="edit_rw" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Desa</label>
              <input type="text" class="form-control" name="desa" id="edit_desa" required>
            </div>
            <div class="col-md-6">
              <label>Kecamatan</label>
              <input type="text" class="form-control" name="kecamatan" id="edit_kecamatan" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Kota</label>
              <input type="text" class="form-control" name="kota" id="edit_kota" required>
            </div>
            <div class="col-md-6">
              <label>Kode Pos</label>
              <input type="number" class="form-control" name="kode_pos" id="edit_kode_pos" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <label>Propinsi</label>
              <input type="text" class="form-control" name="propinsi" id="edit_propinsi" required>
            </div>
          </div>

        </div>
        <div class="modal-footer mt-2">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Modal Tambah Warga -->
<div class="modal fade" id="tambahWargaModal" tabindex="-1" aria-labelledby="tambahWargaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Tambahkan modal-lg agar cukup lebar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahWargaModalLabel">Tambah Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/warga/tambah'); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_kk" class="form-label">Nomor KK<sup> *Lihat pada bagian atas KK anda</sup></label>
                                <input type="number" class="form-control" id="nomor_kk" name="nomor_kk" minlength="16" maxlength="16" placeholder="Wajib 16 Angka" required>
                            </div>
                            <div class="mb-3">
                                <label for="kepala_keluarga" class="form-label">Nama Kepala Keluarga</label>
                                <input type="text" class="form-control" id="kepala_keluarga" name="kepala_keluarga" placeholder="Masukkan Nama Kepala Keluarga" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <select class="form-select select2" id="alamat" name="alamat" required>
                                    <option value="">-- Pilih Alamat --</option>
                                    <?php foreach ($dusun as $d): ?>
                                        <option value="<?= $d->nama_dusun ?>"><?= $d->nama_dusun ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="rt" class="form-label">RT</label>
                               <select name="rt" class="form-control">
                                    <option value="">-- Pilih RT --</option>
                                    <?php
                                    for ($i = 1; $i <= 100; $i++) {
                                        $kode = str_pad($i, 3, '0', STR_PAD_LEFT);
                                        echo "<option value='$kode'>$kode</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="mb-3">
                                <label for="rw" class="form-label">RW</label>
                                <select name="rw" class="form-control">
                                <option value="">-- Pilih RW --</option>
                                <?php
                                for ($i = 1; $i <= 100; $i++) {
                                    $kode = str_pad($i, 3, '0', STR_PAD_LEFT);
                                    echo "<option value='$kode'>$kode</option>";
                                }
                                ?>
                            </select>

                            </div>
                        </div>

                        <div class="col-md-6">
    <div class="mb-3">
        <label for="desa" class="form-label">Desa</label>
        <input type="text" class="form-control" id="desa" name="desa" 
            value="<?= $profil_desa->nama_desa ?>" readonly required>
    </div>
    <div class="mb-3">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control" id="kecamatan" name="kecamatan" 
            value="<?= $profil_desa->nama_kecamatan ?>" readonly required>
    </div>
    <div class="mb-3">
        <label for="kota" class="form-label">Kota / Kabupaten</label>
        <input type="text" class="form-control" id="kota" name="kota" 
            value="<?= $profil_desa->nama_kabupaten ?>" readonly required>
    </div>
    <div class="mb-3">
        <label for="kode_pos" class="form-label">Kode Pos</label>
        <input type="number" class="form-control" id="kode_pos" name="kode_pos" 
            value="<?= $profil_desa->kode_pos ?>" readonly required>
    </div>
    <div class="mb-3">
        <label for="propinsi" class="form-label">Propinsi</label>
        <input type="text" class="form-control" id="propinsi" name="propinsi" 
            value="<?= $profil_desa->nama_propinsi ?>" readonly required>
    </div>
</div>

                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal Import WARGA PER kk -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Warga per KK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/warga/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="file_import" class="form-control" accept=".xlsx" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="downloadTemplateWargaBtn">Download Template</button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import anggota keluarga -->
<div class="modal fade" id="importModalAnggota" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Anggota Keluarga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('admin/warga/import_anggota_keluarga') ?>" method="post"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="file_import" class="form-control" accept=".xlsx" required>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-info" id="downloadTemplateAnggotaBtn">Download Template</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function editWarga(id, nomor_kk, kepala_keluarga, alamat, rt, rw, desa, kecamatan, kota, kode_pos, propinsi) {
    $('#edit_id').val(id);
    $('#edit_nomor_kk').val(nomor_kk);
    $('#edit_kepala_keluarga').val(kepala_keluarga);
    $('#edit_alamat').val(alamat);
    $('#edit_rt').val(rt);
    $('#edit_rw').val(rw);
    $('#edit_desa').val(desa);
    $('#edit_kecamatan').val(kecamatan);
    $('#edit_kota').val(kota);
    $('#edit_kode_pos').val(kode_pos);
    $('#edit_propinsi').val(propinsi);
    $('#formEditWarga').attr('action', '<?= base_url('admin/warga/edit/') ?>' + id);
    $('#editWargaModal').modal('show');
}
</script>


<script>

// Confirm Delete
function confirmDelete(url) {
    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        window.location.href = url;
    }
}
</script>
<!-- Modal Hapus Warga -->
<?php foreach ($warga as $item): ?>
<div class="modal fade" id="hapusWargaModal<?= $item->id; ?>" tabindex="-1"
    aria-labelledby="hapusWargaModalLabel<?= $item->id; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusWargaModalLabel<?= $item->id; ?>">Hapus Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data warga ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/warga/hapus/' . $item->id); ?>" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>