<!-- Konten Utama -->
<div class="container" style="margin-top: -20px;">
    <!-- Tentang Desa -->
    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <div class="card-header" style="background-color: #4CAF50; color: white; padding: 12px 16px; border-bottom: none;">
            <h5 class="mb-0" style="font-weight: 600; display: flex; align-items: center;">
                <i class="fas fa-map-marked-alt mr-2"></i>
                <b>Map Kantor Desa</b>
            </h5>
        </div>
        <div class="card-body p-0">
            <!-- Map Container with Loading Indicator -->
            <div class="map-container" style="position: relative; height: 300px;">
                <div class="map-loading" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center; background: #f5f5f5;">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <iframe 
                    src="<?php echo $profil_desa->lokasi; ?>" 
                    width="100%" 
                    height="100%" 
                    style="border: none; position: absolute; top: 0; left: 0;"
                    allowfullscreen 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    onload="document.querySelector('.map-loading').style.display = 'none'">
                </iframe>
            </div>
            
            <!-- Map Footer -->
            <div class="p-3" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                <a href="<?php echo $profil_desa->lokasi; ?>" target="_blank" 
                   class="btn btn-sm" 
                   style="background-color: #4CAF50; color: white; border-radius: 20px;">
                    <i class="fas fa-expand mr-1"></i> Buka Peta Lebar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Map Interaction Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add map container resizing on window resize
    window.addEventListener('resize', function() {
        const maps = document.querySelectorAll('.map-container iframe');
        maps.forEach(map => {
            const container = map.parentElement;
            map.style.height = container.offsetHeight + 'px';
        });
    });
});
</script>