<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi UMKM Desa -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-store mr-2" style="color: #6c5ce7;"></i>
            <b>Daftar UMKM Desa</b>
        </h5>

        <?php if (!empty($umkm)) : ?>
        <div class="row">
            <?php foreach ($umkm as $u) : ?>
            <!-- Card UMKM -->
            <div class="col-md-6 mb-3 umkm-card" data-nama="<?php echo strtolower($u->nama_usaha); ?>">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div style="flex: 1;">
                                <h6 class="mb-1" style="color: #6c5ce7; font-weight: 600;">
                                    <i class="fas fa-store mr-2"></i>
                                    <?php echo $u->nama_usaha; ?>
                                </h6>
                                <div class="badge bg-light text-dark mb-2" style="font-weight: 500; font-size: 0.75rem;">
                                    <i class="fas fa-tag mr-1"></i> <?php echo $u->kategori ?? 'UMKM'; ?>
                                </div>
                            </div>
                            <div class="views d-flex align-items-center bg-light rounded-pill px-3 py-1">
                                <i class="fas fa-eye text-secondary mr-2" style="font-size: 0.8rem;"></i>
                                <span class="text-secondary small font-weight-bold">400</span>
                            </div>
                        </div>
                        
                        <p class="mb-3" style="color: #495057; line-height: 1.6;">
                            <?php echo nl2br($u->deskripsi); ?>
                        </p>
                        
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                            <span style="color: #6c757d; font-size: 0.9rem;"><?php echo $u->lokasi; ?></span>
                        </div>
                        
                        <div class="d-flex">
                            <a href="https://wa.me/<?php echo $u->telepon; ?>" target="_blank"
                                class="btn btn-sm mr-2" 
                                style="background-color: #25D366; color: white; border-radius: 20px; flex: 1;">
                                <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                            </a>
                            <?php if (!empty($u->gambar)) : ?>
                            <a href="<?php echo base_url('assets/umkm/' . $u->gambar); ?>" 
                               target="_blank" 
                               class="btn btn-outline-secondary btn-sm" 
                               style="border-radius: 20px; flex: 1;">
                                <i class="fas fa-images mr-1"></i> Galeri
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="alert alert-info" style="border-radius: 8px;">
            <i class="fas fa-info-circle mr-2"></i> Data UMKM tidak tersedia.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script untuk Fitur Filter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const umkmCards = document.querySelectorAll('.umkm-card');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();

            umkmCards.forEach(function(card) {
                const namaUsaha = card.getAttribute('data-nama');
                const deskripsi = card.querySelector('p').textContent.toLowerCase();
                
                if (namaUsaha.includes(searchTerm) || deskripsi.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
</script>