<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Struktur Organisasi Desa -->
    <div class="card mb-2" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <div class="card-header" style="background-color: #4CAF50; color: white; padding: 12px 16px;">
            <h5 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-sitemap mr-2"></i>
                <b>Struktur Organisasi Desa</b>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($struktur_desa)) : ?>
                <div style="overflow: auto; max-height: 600px; border-radius: 8px; border: 1px solid #e9ecef;">
                    <img src="<?php echo base_url('assets/foto_struktur/' . $struktur_desa->foto_struktur); ?>"
                        alt="Struktur Organisasi Desa" 
                        class="img-fluid"
                        style="width: 100%; height: auto; display: block;">
                </div>
                <div class="text-center mt-3">
                    <a href="<?php echo base_url('assets/foto_struktur/' . $struktur_desa->foto_struktur); ?>" 
                       target="_blank" 
                       class="btn btn-sm" 
                       style="background-color: #4CAF50; color: white; border-radius: 20px;">
                        <i class="fas fa-expand mr-1"></i> Lihat Gambar Penuh
                    </a>
                </div>
            <?php else : ?>
                <div class="alert alert-warning" style="border-radius: 8px; border-left: 4px solid #ffc107;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Data struktur organisasi desa tidak tersedia.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Visi Desa -->
    <div class="card mb-2" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%); color: white;">
        <div class="card-header" style="background-color: rgba(0,0,0,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
            <h5 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-bullseye mr-2"></i>
                <b>VISI DESA</b>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($visi_misi)) : ?>
                <ul style="list-style-type: none; padding-left: 0;">
                    <?php
                    $visi_list = explode("\n", $visi_misi->visi);
                    foreach ($visi_list as $index => $visi) :
                        if (!empty(trim($visi))) :
                    ?>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex;">
                        <span style="margin-right: 10px; color: #C8E6C9;"><?= $index + 1 ?>.</span>
                        <span><?= trim($visi) ?></span>
                    </li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            <?php else : ?>
                <div class="alert alert-light" style="border-radius: 8px; background-color: rgba(255,255,255,0.15); color: white;">
                    <i class="fas fa-info-circle mr-2"></i> Visi desa belum tersedia.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Misi Desa -->
    <div class="card mb-2" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white;">
        <div class="card-header" style="background-color: rgba(0,0,0,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
            <h5 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-tasks mr-2"></i>
                <b>MISI DESA</b>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($visi_misi)) : ?>
                <ul style="list-style-type: none; padding-left: 0;">
                    <?php
                    $misi_list = explode("\n", $visi_misi->misi);
                    foreach ($misi_list as $index => $misi) :
                        if (!empty(trim($misi))) :
                    ?>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex;">
                        <span style="margin-right: 10px; color: #BBDEFB;"><?= $index + 1 ?>.</span>
                        <span><?= trim($misi) ?></span>
                    </li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            <?php else : ?>
                <div class="alert alert-light" style="border-radius: 8px; background-color: rgba(255,255,255,0.15); color: white;">
                    <i class="fas fa-info-circle mr-2"></i> Misi desa belum tersedia.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>