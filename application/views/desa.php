<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Tentang Desa -->
    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <div class="card-header" style="background-color: #4CAF50; color: white; padding: 12px 16px;">
            <h5 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-info-circle mr-2"></i>
                <b>Tentang Desa</b>
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-4" style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <img src="<?php echo base_url('assets/profil/' . $profil_desa->foto); ?>" 
                     alt="Foto Desa" 
                     class="img-fluid w-100" 
                     style="height: auto; max-height: 400px; object-fit: cover;">
            </div>
            <div class="about-content" style="color: #495057; line-height: 1.8; font-size: 0.7rem;">
                <?php echo nl2br($profil_desa->tentang); ?>
            </div>
        </div>
    </div>

    <!-- Kontak Desa -->
    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white;">
        <div class="card-header" style="background-color: rgba(0,0,0,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
            <h5 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-phone-alt mr-2"></i>
                <b>Kontak Desa</b>
            </h5>
        </div>
        <div class="card-body">
            <p style="opacity: 0.9; margin-bottom: 1.5rem;">
                <i class="fas fa-comment-dots mr-2"></i> Jika Anda memiliki pertanyaan atau ingin berkunjung, silakan hubungi:
            </p>
            
            <ul class="contact-list" style="list-style: none; padding-left: 0;">
                <li class="mb-3" style="display: flex; align-items: flex-start;">
                    <div style="margin-right: 12px; color: #C8E6C9;">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 2px;">Alamat</strong>
                        <?php echo $profil_desa->alamat_desa; ?>
                    </div>
                </li>
                
                <li class="mb-3" style="display: flex; align-items: flex-start;">
                    <div style="margin-right: 12px; color: #C8E6C9;">
                        <i class="fas fa-phone fa-lg"></i>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 2px;">Telepon</strong>
                        <a href="tel:<?php echo $profil_desa->telepon; ?>" style="color: white; text-decoration: underline;">
                            <?php echo $profil_desa->telepon; ?>
                        </a>
                    </div>
                </li>
                
                <li style="display: flex; align-items: flex-start;">
                    <div style="margin-right: 12px; color: #C8E6C9;">
                        <i class="fas fa-envelope fa-lg"></i>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 2px;">Email</strong>
                        <a href="mailto:<?php echo $profil_desa->email; ?>" style="color: white; text-decoration: underline;">
                            <?php echo $profil_desa->email; ?>
                        </a>
                    </div>
                </li>
            </ul>
            
        </div>
    </div>
</div>