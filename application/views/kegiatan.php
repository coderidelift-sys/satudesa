<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi Kegiatan -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-calendar-check mr-2" style="color: #3498db;"></i>
            <b>Daftar Kegiatan</b>
        </h5>

        <?php if (!empty($kegiatan)) : ?>
        <div class="activity-list">
            <?php foreach ($kegiatan as $k) : ?>
            <!-- Card Kegiatan -->
            <div class="card mb-3 activity-card" style="border-radius: 10px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div class="card-header bg-white" style="border-radius: 10px 10px 0 0; cursor: pointer;"
                     data-toggle="collapse" data-target="#detailKegiatan<?php echo $k->id; ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1" style="color: #3498db; font-weight: 600;">
                                <?php echo $k->judul; ?>
                            </h6>
                            <p class="mb-0 text-muted small">
                                <i class="far fa-clock mr-1"></i> 
                                <?php echo date('d M Y H:i', strtotime($k->created_at)); ?> WIB
                                <span class="mx-1">•</span>
                                <i class="fas fa-user-shield mr-1"></i> Admin
                            </p>
                        </div>
                        <i class="fas fa-chevron-down" style="color: #95a5a6; transition: transform 0.3s ease;"></i>
                    </div>
                </div>

                <!-- Detail Kegiatan (Collapse) -->
                <div class="collapse" id="detailKegiatan<?php echo $k->id; ?>">
                    <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 10px 10px;">
                        <div class="activity-content" style="color: #495057; line-height: 1.7;">
                            <?php echo nl2br($k->isi); ?>
                        </div>

                        <div class="activity-details mt-3 pt-3" style="border-top: 1px solid #eee;">
                            <div class="detail-item mb-2">
                                <strong><i class="fas fa-map-marker-alt mr-2" style="color: #e74c3c;"></i> Lokasi:</strong>
                                <?php echo $k->lokasi; ?>
                            </div>
                            <div class="detail-item mb-3">
                                <strong><i class="far fa-clock mr-2" style="color: #e74c3c;"></i> Waktu:</strong>
                                <?php echo $k->waktu; ?>
                            </div>

                            <!-- Lampiran File -->
                            <?php if (!empty($k->lampiran)) : ?>
                            <div class="attachment mt-3 pt-3" style="border-top: 1px solid #eee;">
                                <h6 style="font-weight: 600; color: #2c3e50;">
                                    <i class="fas fa-paperclip mr-2"></i> Lampiran
                                </h6>
                                <a href="<?php echo base_url('assets/lampiran_kegiatan/' . $k->lampiran); ?>"
                                    target="_blank" class="btn btn-outline-danger btn-sm" style="border-radius: 20px;">
                                    <i class="fas fa-file-pdf mr-1"></i> Unduh Dokumen
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="alert alert-info" style="border-radius: 8px;">
            <i class="fas fa-info-circle mr-2"></i> Tidak ada kegiatan tersedia.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script untuk Fitur Filter dan Animasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const activityCards = document.querySelectorAll('.activity-card');

    // Fungsi untuk memfilter card kegiatan
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();

            activityCards.forEach(function(card) {
                const judul = card.querySelector('h6').textContent.toLowerCase();
                const isi = card.querySelector('.activity-content')?.textContent.toLowerCase() || '';
                
                if (judul.includes(searchTerm) || isi.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Animasi icon chevron
    document.querySelectorAll('[data-toggle="collapse"]').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('.fa-chevron-down');
            if (icon) {
                icon.style.transform = icon.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });
});
</script>