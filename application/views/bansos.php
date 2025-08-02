 <!-- Search Bar -->
 <div class="search-bar d-flex align-items-center">
     <input id="search-input" class="form-control" placeholder="Search BANSOS..." type="text" />
     <i class="fas fa-search ml-2"></i>
 </div>

 <!-- Konten Utama -->
 <div class="container">
     <!-- Informasi BANSOS -->
     <div class="antrian mb-4">
         <h5><b>Informasi Bantuan Sosial (BANSOS)</b></h5>

         <?php if (!empty($bansos)) : ?>
         <?php foreach ($bansos as $b) : ?>
         <!-- Card BANSOS -->
         <div class="card p-3 mb-1">
             <div class="d-flex justify-content-between align-items-center">
                 <h7 class="mb-0">
                     <?php echo $b->judul; ?>
                 </h7>
                 <button class="btn btn-link p-0" type="button" data-toggle="collapse"
                     data-target="#detailBansos<?php echo $b->id; ?>" aria-expanded="false"
                     aria-controls="detailBansos<?php echo $b->id; ?>">
                     <i class="fas fa-chevron-down"></i> <!-- Ikon panah -->
                 </button>
             </div>
             <p class="mb-0 text-muted small">
                 <i class="fas fa-clock"></i> Last Update:
                 <?php echo date('d M Y H:i:s', strtotime($b->created_at)); ?> WIB | Admin
             </p>

             <!-- Detail BANSOS (Collapse) -->
             <div class="collapse mt-3" id="detailBansos<?php echo $b->id; ?>">
                 <div class="card card-body">
                     <p>
                         <?php echo $b->isi; ?>
                     </p>
                     <p class="mb-0">
                         <strong>Lokasi:</strong> <?php echo $b->lokasi; ?><br>
                         <strong>Waktu:</strong> <?php echo $b->waktu; ?>
                     </p>

                     <!-- Lampiran File -->
                     <?php if (!empty($b->lampiran)) : ?>
                     <div class="mt-3">
                         <h6><b>Lampiran:</b></h6>
                         <ul class="list-unstyled">
                             <li>
                                 <a href="<?php echo base_url('assets/lampiran_bansos/' . $b->lampiran); ?>"
                                     target="_blank" class="text-decoration-none">
                                      <i class="fas fa-file text-danger mr-2"></i> Lihat Lampiran
                                 </a>
                             </li>
                         </ul>
                     </div>
                     <?php endif; ?>
                 </div>
             </div>
         </div>
         <?php endforeach; ?>
         <?php else : ?>
         <p class="text-danger">Tidak ada informasi BANSOS tersedia.</p>
         <?php endif; ?>
     </div>
 </div>

 <!-- Script untuk Fitur Filter -->
 <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const bansosCards = document.querySelectorAll('.card.p-3.mb-3');

    // Fungsi untuk memfilter card BANSOS
    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.trim()
            .toLowerCase(); // Ambil nilai input dan ubah ke lowercase

        bansosCards.forEach(function(card) {
            const judul = card.querySelector('h5').textContent
                .toLowerCase(); // Ambil judul BANSOS

            // Tampilkan atau sembunyikan card berdasarkan pencarian
            if (judul.includes(searchTerm)) {
                card.style.display = 'block'; // Tampilkan card
            } else {
                card.style.display = 'none'; // Sembunyikan card
            }
        });
    });
});
 </script>