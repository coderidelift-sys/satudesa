<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi Notifikasi Update Fitur -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-bell mr-2" style="color: #3498db;"></i>
            <b>Notifikasi Update Fitur</b>
        </h5>

        <?php if (empty($pembaruan)) : ?>
            <div class="alert alert-info" style="border-radius: 8px; background-color: #e7f5ff; border-left: 4px solid #228be6;">
                <i class="fas fa-info-circle mr-2"></i> Tidak ada data pembaruan ditemukan.
            </div>
        <?php else : ?>
            <?php foreach ($pembaruan as $item) : ?>
                <!-- Card Notifikasi -->
                <div class="card mb-1" style="border-radius: 10px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <div class="card-header bg-white" style="border-radius: 10px 10px 0 0; cursor: pointer;"
                         data-toggle="collapse" data-target="#detailNotifikasi<?= $item->id ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1" style="color: #495057; font-weight: 600;">
                                    <?= $item->nama_fitur ?>
                                </h6>
                                <p class="mb-0 text-muted small" style="font-size: 0.8rem;">
                                    <i class="far fa-calendar-alt mr-1"></i> 
                                    <?= date('d M Y', strtotime($item->created_at)) ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-user-tie mr-1"></i> Oleh Developer
                                </p>
                            </div>
                            <i class="fas fa-chevron-down" style="color: #adb5bd; transition: transform 0.3s ease;"></i>
                        </div>
                    </div>

                    <!-- Detail Notifikasi (Collapse) -->
                    <div class="collapse" id="detailNotifikasi<?= $item->id ?>">
                        <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 10px 10px;">
                            <p style="color: #495057; line-height: 1.6; margin-bottom: 0;">
                                <?= nl2br($item->deskripsi) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Script untuk Fitur Filter dan Animasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const notifikasiCards = document.querySelectorAll('.card.mb-3');

    function filterNotifikasi() {
        const searchTerm = searchInput.value.trim().toLowerCase();

        notifikasiCards.forEach(function(card) {
            const namaFitur = card.querySelector('h6').textContent.toLowerCase();
            card.style.display = namaFitur.includes(searchTerm) ? 'block' : 'none';
        });
    }

    // Animasi icon chevron
    document.querySelectorAll('[data-toggle="collapse"]').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('.fa-chevron-down');
            icon.style.transform = icon.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });

    searchInput?.addEventListener('input', filterNotifikasi);
    searchButton?.addEventListener('click', filterNotifikasi);
});
</script>