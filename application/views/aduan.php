<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi Pengaduan -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-exclamation-triangle mr-2" style="color: #e74c3c;"></i>
            <b>Data Pengaduan</b>
        </h5>

        <!-- Menampilkan pesan sukses -->
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" style="border-radius: 8px; border-left: 4px solid #28a745;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-check-circle mr-2"></i>
                    Aduan berhasil ditambahkan. <strong>No Tracking: 
                    <span id="noTracking"><?php echo substr($this->session->flashdata('success'), -13); ?></span></strong>
                </div>
                <button class="btn btn-sm" id="copyButton" onclick="copyTracking()" 
                        style="background-color: #28a745; color: white; border-radius: 20px;">
                    <i class="fas fa-copy mr-1"></i> Salin
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger" style="border-radius: 8px; border-left: 4px solid #dc3545;">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= $this->session->flashdata('error') ?>
        </div>
        <?php endif; ?>

        <!-- Tombol Buat Aduan -->
        <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAduan"
                style="border-radius: 20px; padding: 8px 20px;">
            <i class="fas fa-plus mr-2"></i> Buat Aduan Baru
        </button>

        <!-- Statistik Pengaduan -->
        <div class="row">
            <!-- Hari Ini -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-body text-center">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-circle mb-3" style="background-color: rgba(13,110,253,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-day text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <h6 class="mb-2" style="color: #6c757d;">Hari Ini</h6>
                            <h3 class="mb-0 text-primary" style="font-weight: 700;"><?php echo $hari_ini; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Minggu Ini -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-body text-center">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-circle mb-3" style="background-color: rgba(25,135,84,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-week text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <h6 class="mb-2" style="color: #6c757d;">Minggu Ini</h6>
                            <h3 class="mb-0 text-success" style="font-weight: 700;"><?php echo $minggu_ini; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-body text-center">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-circle mb-3" style="background-color: rgba(255,193,7,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-alt text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                            <h6 class="mb-2" style="color: #6c757d;">Bulan Ini</h6>
                            <h3 class="mb-0 text-warning" style="font-weight: 700;"><?php echo $bulan_ini; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tahun Ini -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-body text-center">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-circle mb-3" style="background-color: rgba(220,53,69,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar text-danger" style="font-size: 1.5rem;"></i>
                            </div>
                            <h6 class="mb-2" style="color: #6c757d;">Tahun Ini</h6>
                            <h3 class="mb-0 text-danger" style="font-weight: 700;"><?php echo $tahun_ini; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Aduan -->
<div class="modal fade" id="modalAduan" tabindex="-1" aria-labelledby="modalAduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background-color: #e74c3c; color: white; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="modalAduanLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Form Pengaduan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAduan" action="<?= base_url('admin/aduan/add') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    
                    <div class="form-group">
                        <label style="font-weight: 600;">NIK</label>
                        <input type="text" id="nik" name="nik" class="form-control" placeholder="Masukkan NIK Anda (16 digit angka)" 
                               maxlength="16" required 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                               style="border-radius: 8px; padding: 12px;">
                        <small class="text-muted">Pastikan NIK yang Anda masukkan benar</small>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600;">Isi Aduan</label>
                        <textarea name="isi_aduan" class="form-control" rows="5"
                            placeholder="Masukkan data lokasi/tempat dan informasi detailnya, misal: 'Di jalan tanjakan jangkorang terjadi longsor dan menutup akses jalan'"
                            required style="border-radius: 8px;"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600;">Upload Foto (Opsional)</label>
                        <div class="custom-file">
                            <input type="file" name="foto" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile" data-browse="Pilih">Pilih file...</label>
                        </div>
                        <small class="text-muted">Format: JPG/PNG (maks. 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 20px;">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitAduan" style="border-radius: 20px;">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Aduan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi untuk menyalin no tracking ke clipboard
function copyTracking() {
    var copyText = document.getElementById("noTracking");
    var button = document.getElementById("copyButton");
    
    // Simpan teks asli tombol
    var originalText = button.innerHTML;
    
    // Buat elemen textarea untuk menyalin
    var textArea = document.createElement("textarea");
    textArea.value = copyText.textContent;
    document.body.appendChild(textArea);
    textArea.select();
    
    try {
        document.execCommand("copy");
        // Ubah tampilan tombol saat berhasil
        button.innerHTML = '<i class="fas fa-check mr-1"></i> Tersalin!';
        button.style.backgroundColor = '#28a745';
        
        // Kembalikan ke tampilan semula setelah 2 detik
        setTimeout(function() {
            button.innerHTML = originalText;
            button.style.backgroundColor = '#28a745';
        }, 2000);
    } catch (err) {
        console.error('Gagal menyalin teks: ', err);
    }
    
    document.body.removeChild(textArea);
}

// Script untuk menampilkan nama file yang dipilih
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = document.getElementById("customFile").files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});

// Validasi form sebelum submit
document.getElementById('formAduan').addEventListener('submit', function(e) {
    var nik = document.getElementById('nik').value;
    if (nik.length !== 16) {
        e.preventDefault();
        alert('NIK harus terdiri dari 16 digit angka');
        document.getElementById('nik').focus();
    }
});
</script>