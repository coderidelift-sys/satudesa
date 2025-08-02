<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Informasi FAQ -->
    <div class="antrian mb-4">
        <h5 style="color: #2c3e50; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f1f1;">
            <i class="fas fa-question-circle mr-2" style="color: #3498db;"></i>
            <b>FAQ (Tanya Jawab)</b>
        </h5>

        <?php if (!empty($faq)) : ?>
        <div class="faq-list">
            <?php foreach ($faq as $f) : ?>
            <!-- Card FAQ -->
            <div class="card mb-3 faq-item" style="border-radius: 10px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <div class="card-header bg-white" style="border-radius: 10px 10px 0 0; cursor: pointer;"
                     data-toggle="collapse" data-target="#faq<?php echo $f->id; ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" style="color: #2c3e50; font-weight: 500;">
                            <span class="badge bg-primary mr-2" style="font-weight: 400;">Q</span>
                            <?php echo $f->pertanyaan; ?>
                        </h6>
                        <i class="fas fa-chevron-down" style="color: #95a5a6; transition: transform 0.3s ease;"></i>
                    </div>
                </div>

                <!-- Jawaban FAQ (Collapse) -->
                <div class="collapse" id="faq<?php echo $f->id; ?>">
                    <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 10px 10px;">
                        <div class="faq-answer" style="color: #495057; line-height: 1.7; position: relative;">
                            <span class="badge bg-success mr-2" style="font-weight: 400; position: absolute; left: 0; top: 0;">A</span>
                            <div style="padding-left: 30px;">
                                <?php echo nl2br($f->jawaban); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="alert alert-info" style="border-radius: 8px;">
            <i class="fas fa-info-circle mr-2"></i> Tidak ada FAQ tersedia.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script untuk Fitur Filter dan Animasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const faqItems = document.querySelectorAll('.faq-item');

    // Fungsi untuk memfilter FAQ
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();

            faqItems.forEach(function(item) {
                const question = item.querySelector('h6').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer')?.textContent.toLowerCase() || '';
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
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