<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi Pengumuman -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-bullhorn mr-2" style="color: #e74c3c;"></i>
            <b>Daftar Pengumuman</b>
        </h5>

        <?php if (!empty($pengumuman)) : ?>
        <div class="announcement-list">
            <?php foreach ($pengumuman as $p) : ?>
            <!-- Card Pengumuman -->
            <div class="card mb-3 announcement-card" style="border-radius: 10px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div class="card-header bg-white" style="border-radius: 10px 10px 0 0; cursor: pointer;"
                     data-toggle="collapse" data-target="#detailPengumuman<?php echo $p->id; ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1" style="color: #2c3e50; font-weight: 600;">
                                <?php echo $p->judul; ?>
                            </h6>
                            <p class="mb-0 text-muted small">
                                <i class="far fa-clock mr-1"></i> 
                                <?php echo date('d M Y H:i', strtotime($p->created_at)); ?> WIB
                                <span class="mx-1">•</span>
                                <i class="fas fa-user-shield mr-1"></i> Admin
                            </p>
                        </div>
                        <i class="fas fa-chevron-down" style="color: #95a5a6; transition: transform 0.3s ease;"></i>
                    </div>
                </div>

                <!-- Detail Pengumuman (Collapse) -->
                <div class="collapse" id="detailPengumuman<?php echo $p->id; ?>">
                    <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 10px 10px;">
                        <div class="announcement-content" style="color: #495057; line-height: 1.7;">
                            <?php echo nl2br($p->isi); ?>
                        </div>

                        <div class="announcement-details mt-3 pt-3" style="border-top: 1px solid #eee;">
                            <div class="detail-item mb-2">
                                <strong><i class="fas fa-map-marker-alt mr-2" style="color: #e74c3c;"></i> Lokasi:</strong>
                                <?php echo $p->lokasi; ?>
                            </div>
                            <div class="detail-item mb-2">
                                <strong><i class="far fa-clock mr-2" style="color: #e74c3c;"></i> Waktu:</strong>
                                <?php echo $p->waktu; ?>
                            </div>
                            <?php if (!empty($p->link)) : ?>
                            <div class="detail-item mb-3">
                                <strong><i class="fas fa-link mr-2" style="color: #e74c3c;"></i> Link:</strong>
                                <a href="<?php echo $p->link; ?>" class="btn btn-sm mt-2" 
                                   style="background-color: #e74c3c; color: white; border-radius: 20px;" 
                                   target="_blank">
                                    <i class="fas fa-external-link-alt mr-1"></i> Kunjungi Link
                                </a>
                            </div>
                            <?php endif; ?>

                            <!-- Lampiran File -->
                            <?php if (!empty($p->lampiran)) : ?>
                            <div class="attachment mt-3 pt-3" style="border-top: 1px solid #eee;">
                                <h6 style="font-weight: 600; color: #2c3e50;">
                                    <i class="fas fa-paperclip mr-2"></i> Lampiran
                                </h6>
                                <a href="<?php echo base_url('assets/lampiran_pengumuman/' . $p->lampiran); ?>"
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
            <i class="fas fa-info-circle mr-2"></i> Tidak ada pengumuman tersedia.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script untuk Fitur Filter dan Animasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const announcementCards = document.querySelectorAll('.announcement-card');

    // Fungsi untuk memfilter card pengumuman
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();

            announcementCards.forEach(function(card) {
                const judul = card.querySelector('h6').textContent.toLowerCase();
                const isi = card.querySelector('.announcement-content')?.textContent.toLowerCase() || '';
                
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