<main id="main" class="main">
  <div class="pagetitle">
    <h1>Daftar Hukum</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/hukum') ?>">Data Jenis Hukum</a></li>
        <li class="breadcrumb-item active"><?= $jenis_hukum->nama_hukum ?></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar Hukum - <strong><?= $jenis_hukum->nama_hukum ?></strong></h5>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Tombol Kembali -->
  <a href="<?= base_url('admin/hukum') ?>" class="btn btn-secondary btn-sm">
    <i class="bi bi-arrow-left-circle"></i> Kembali ke Jenis Hukum
  </a>
  
  <!-- Tombol Tambah -->
  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDaftarHukumModal">
    <i class="bi bi-plus-circle"></i> Tambah Daftar Hukum
  </button>
</div>

            <!-- Tabel -->
            <div class="table-responsive">
             <table id="dataDaftarHukumTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Lampiran</th>
                    <th>Created At</th>
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

<!-- Modal Tambah -->
<div class="modal fade" id="addDaftarHukumModal" tabindex="-1" aria-labelledby="addDaftarHukumModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="<?= base_url('admin/hukum/add_daftar_hukum') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_jenis" value="<?= $jenis_hukum->id ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="addDaftarHukumModalLabel">Tambah Daftar Hukum</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="isi" class="form-control" rows="5" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Lampiran (opsional)</label>
            <input type="file" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
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
