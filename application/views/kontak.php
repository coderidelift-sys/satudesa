<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi Narahubung Staff -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
         <i class="fas fa-phone-alt mr-2" style="color: #3498db;"></i>
            <b>Kontak Pelayanan</b>
        </h5>

        <?php if (!empty($kontak_staff)) : ?>
            <?php foreach ($kontak_staff as $staff) : ?>
                <!-- Card Staff -->
                <div class="card mb-3" style="border-radius: 10px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <img alt="Staff icon"
                                src="<?php echo base_url('assets/icon_aplikasi/staff.png')?>"
                                class="rounded-circle mr-3" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #e9ecef;" />
                            <div style="flex: 1;">
                                <h6 class="mb-1" style="color: #3498db; font-weight: 600;"><?php echo $staff->nama_staff; ?></h6>
                                <p class="mb-2 text-muted small" style="font-size: 0.85rem;"><?php echo $staff->jabatan; ?></p>

                                <!-- Tombol WhatsApp -->
                                <a href="https://wa.me/<?php echo $staff->telepon; ?>" target="_blank"
                                    class="btn btn-success btn-sm" style="border-radius: 20px; padding: 5px 15px; background-color: #25D366; border: none;">
                                    <i class="fab fa-whatsapp mr-1"></i> Hubungi via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-info" style="border-radius: 8px;">
                <i class="fas fa-info-circle"></i> Tidak ada data narahubung staff tersedia.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script untuk Fitur Filter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const staffCards = document.querySelectorAll('.card.mb-3');

    // Fungsi untuk memfilter card staff
    searchInput?.addEventListener('input', function() {
        const searchTerm = searchInput.value.trim().toLowerCase();

        staffCards.forEach(function(card) {
            const namaStaff = card.querySelector('h6').textContent.toLowerCase();
            card.style.display = namaStaff.includes(searchTerm) ? 'block' : 'none';
        });
    });
});
</script>