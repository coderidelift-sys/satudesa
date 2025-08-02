<!-- Konten Utama -->
<div class="container" style="margin-top:-20px;">
    <!-- Informasi Pelayanan -->
    <div class="antrian mb-4">
       <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
           <i class="fas fa-envelope mr-2" style="color: #3498db;"></i>
            <b>Pelayanan Surat</b>
        </h5>

        <!-- Menampilkan pesan sukses -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success" style="border-radius: 8px; border-left: 4px solid #28a745;">
                <?= $this->session->flashdata('success'); ?><br>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" style="border-radius: 8px; border-left: 4px solid #dc3545;">
                <?= $this->session->flashdata('error'); ?><br>
            </div>
        <?php endif; ?>

        <!-- Card Regulasi Surat -->
        <?php foreach ($regulasi as $row): ?>
            <div class="p-3 mb-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #3498db; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="d-flex justify-content-between align-items-center card-toggle">
                    <h6 class="mb-0" style="color: #2c3e50; font-weight: 600;"><?= $row->judul_regulasi ?></h6>
                    <button class="btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRegulasi<?= $row->id ?>" aria-expanded="false" aria-controls="collapseRegulasi<?= $row->id ?>" style="background-color: #3498db; color: white; border-radius: 20px; padding: 5px 15px;">
                        <i class="bi bi-info-circle"></i> Regulasi
                    </button>
                </div>

                <div class="collapse mt-3" id="collapseRegulasi<?= $row->id ?>">
                    <div class="card card-body" style="border-radius: 8px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <h6 style="color: #3498db; font-weight: 600;"><strong>Deskripsi:</strong></h6>
                        <p style="color: #495057;"><?= nl2br($row->deskripsi) ?></p>

                        <h6 style="color: #3498db; font-weight: 600;"><strong>Persyaratan:</strong></h6>
                        <p style="color: #495057;"><?= nl2br($row->persyaratan) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div> <!-- /.antrian -->
</div> <!-- /.container -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const notifikasiCards = document.querySelectorAll('.yellow.p-3.mb-3.rounded'); // Periksa apakah ini memang class yg dipakai

    function filterNotifikasi() {
        const searchTerm = searchInput.value.trim().toLowerCase();

        notifikasiCards.forEach(function(card) {
            const namaFitur = card.querySelector('h6.mb-0.text-dark')?.textContent.toLowerCase() || '';

            card.style.display = namaFitur.includes(searchTerm) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterNotifikasi);
});
</script>