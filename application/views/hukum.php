<div class="container" style="margin-top: -20px;">
    <!-- Produk Hukum -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-gavel mr-2" style="color: #6c5ce7;"></i>
            <b>Produk Hukum</b>
        </h5>

        <?php foreach ($hukum as $jenis): ?>
        <div class="mb-4">
            <h6 class="mb-3" style="color: #6c5ce7; font-weight: 600; padding-left: 8px; border-left: 4px solid #6c5ce7;">
                <?= $jenis->nama_hukum; ?>
            </h6>

            <?php if (!empty($daftar_hukum[$jenis->id])): ?>
                <div class="legal-list">
                    <?php foreach ($daftar_hukum[$jenis->id] as $item): ?>
                    <div class="card mb-2 legal-item" style="border-radius: 8px; border: none; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                        <div class="card-header bg-white" style="border-radius: 8px 8px 0 0; cursor: pointer;"
                             data-toggle="collapse" data-target="#collapse<?= $item->id ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0" style="color: #2c3e50; font-weight: 500;">
                                    <?= $item->judul ?>
                                </h6>
                                <i class="fas fa-chevron-down" style="color: #95a5a6; transition: transform 0.3s ease;"></i>
                            </div>
                        </div>
                        
                        <div class="collapse" id="collapse<?= $item->id ?>">
                            <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 8px 8px;">
                                <div class="legal-content" style="color: #495057; line-height: 1.7;">
                                    <?= nl2br($item->isi); ?>
                                </div>
                                
                                <?php if (!empty($item->lampiran)): ?>
                                <div class="mt-3 pt-3" style="border-top: 1px solid #e9ecef;">
                                    <a href="<?= base_url('assets/lampiran_hukum/' . $item->lampiran); ?>" 
                                       target="_blank" 
                                       class="btn btn-sm" 
                                       style="background-color: #6c5ce7; color: white; border-radius: 20px;">
                                        <i class="fas fa-paperclip mr-1"></i> Unduh Lampiran
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="border-radius: 8px;">
                    <i class="fas fa-info-circle mr-2"></i> Belum ada data hukum.
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Script untuk Animasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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