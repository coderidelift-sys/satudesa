
<!-- Konten Utama -->
<div class="container">
    <!-- Informasi Pengaduan -->
    <div class="antrian mb-4">
        <h5><b>Daftar Akun Warga</b></h5>
        <!-- Menampilkan pesan sukses -->
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <!-- Pesan sukses dengan no tracking -->
            Aduan berhasil ditambahkan. <strong>No Tracking Anda: <span
                    id="noTracking"><?php echo substr($this->session->flashdata('success'), -13); ?></span> <button
                    class="btn btn-secondary btn-sm" id="copyButton" onclick="copyTracking()">
                    <i class="fa fa-copy"></i> Salin
                </button></strong>
        </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

  <!-- Form Daftar Akun Warga -->
<div class="card p-3 mb-4">
    <form action="<?= base_url('warga/simpan_pendaftaran') ?>" method="post">
        <div class="form-group">
            <label for="nik">NIK</label>
            <input type="text" name="nik" id="nik" class="form-control" placeholder="Masukkan NIK Anda" required>
        </div>
        <div class="form-group">
            <label for="username">Buat Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="konfirmasi_password">Konfirmasi Kata Sandi</label>
            <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control"
                placeholder="Konfirmasi Password" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="agreeCheck" required>
            <label class="form-check-label" for="agreeCheck">
                Saya menyetujui <a href="#" data-toggle="modal" data-target="#modalPersetujuan">penggunaan data pribadi</a>.
            </label>
        </div>
        <button type="submit" class="btn btn-success">Daftar</button>
    </form>
</div>

<!-- Modal Persetujuan -->
<div class="modal fade" id="modalPersetujuan" tabindex="-1" aria-labelledby="modalPersetujuanLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPersetujuanLabel">Persetujuan Penggunaan Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Dengan mendaftar, saya menyetujui bahwa data saya digunakan untuk validasi sistem pelayanan desa. Data
        tidak akan disebarkan ke pihak ketiga tanpa persetujuan.</p>
        <p>Saya juga bertanggung jawab atas kebenaran data yang saya isi.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Saya Mengerti</button>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('konfirmasi_password').value;

    if (pass !== confirm) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok.');
    }
});
</script>
