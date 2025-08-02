<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi Wisata Desa -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-umbrella-beach mr-2"></i>
            <b>Informasi Wisata Desa</b>
        </h5>

        <div id="wisata-list">
            <?php if (!empty($wisata)) : ?>
            <?php foreach ($wisata as $w) : ?>
            <!-- Card Wisata -->
            <div class="card mb-3 wisata-item" style="border-radius: 10px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div style="flex: 1;">
                            <h6 class="mb-1" style="color: #e74c3c; font-weight: 600;">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span class="wisata-nama"><?php echo $w->nama_wisata; ?></span>
                            </h6>
                            <p class="mb-0 text-muted small">
                                <i class="fas fa-clock mr-1"></i> Kunjungan terakhir: <?= date('d M Y') ?>
                            </p>
                        </div>
                        <div class="views d-flex align-items-center bg-light rounded-pill px-3 py-1" style="background-color: #f8f9fa !important;">
                            <i class="fas fa-eye text-secondary mr-2"></i>
                            <span class="text-secondary small font-weight-bold">400</span>
                        </div>
                    </div>
                    
                    <p class="mb-3 wisata-deskripsi" style="color: #495057; line-height: 1.6;">
                        <?php echo nl2br($w->deskripsi); ?>
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?php echo $w->lokasi; ?>" target="_blank" 
                           class="btn btn-sm" 
                           style="background-color: #e74c3c; color: white; border-radius: 20px; padding: 5px 15px;">
                            <i class="fas fa-map-marked-alt mr-2"></i> Lihat di Peta
                        </a>
                        <?php if (!empty($w->gambar)) : ?>
                        <a href="<?php echo base_url('assets/wisata/' . $w->gambar); ?>" 
                           target="_blank" 
                           class="btn btn-outline-secondary btn-sm" 
                           style="border-radius: 20px; padding: 5px 15px;">
                            <i class="fas fa-images mr-1"></i> Galeri
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else : ?>
            <div class="alert alert-info" style="border-radius: 8px;">
                <i class="fas fa-info-circle mr-2"></i> Data wisata tidak tersedia.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript untuk Pencarian -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase().trim();
            const wisataItems = document.querySelectorAll('.wisata-item');

            wisataItems.forEach(function(item) {
                const namaWisata = item.querySelector('.wisata-nama').textContent.toLowerCase();
                const deskripsiWisata = item.querySelector('.wisata-deskripsi').textContent.toLowerCase();

                if (namaWisata.includes(searchQuery) || deskripsiWisata.includes(searchQuery)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>