<!-- Search Bar -->
<div class="search-bar d-flex align-items-center mb-3">
    <input id="search-input" class="form-control" placeholder="Cari produk..." type="text" />
    <i class="fas fa-search ml-2"></i>
</div>

<!-- Konten Produk Online Shop Desa -->
<div class="container">
    <h5><b>Online Shop Desa</b></h5>

    <div class="row">
        <?php foreach ($produk_list as $produk) : ?>
            <div class="col-6 col-md-6 mb-3 produk-item">
                <div class="card h-100 text-center shadow-sm">
                    <div class="p-3">
                        <img src="<?= base_url('assets/upload/produk/' . $produk->gambar) ?>"
                             class="rounded-circle mx-auto d-block"
                             alt="<?= $produk->nama_produk ?>"
                             style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ccc;">
                    </div>
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1"><b><?= $produk->nama_produk ?></b></h6>
                        <p class="mb-1 text-success">Rp <?= number_format($produk->harga, 0, ',', '.') ?></p>

                        <!-- Tombol Detail buka modal -->
                        <button type="button" class="btn btn-info btn-sm w-100" data-toggle="modal" data-target="#modalDetail<?= $produk->id ?>">
                            Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Detail Produk -->
            <div class="modal fade" id="modalDetail<?= $produk->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $produk->id ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel<?= $produk->id ?>"><?= $produk->nama_produk ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body text-center">
                    <img src="<?= base_url('assets/upload/produk/' . $produk->gambar) ?>"
                         class="img-fluid mb-3"
                         style="max-height: 200px; object-fit: contain;"
                         alt="<?= $produk->nama_produk ?>">
                    <p class="mb-1"><strong>Harga:</strong> Rp <?= number_format($produk->harga, 0, ',', '.') ?></p>
                    <p class="mb-1"><strong>Satuan:</strong> <?= $produk->satuan ?></p>
                    <p class="mb-1"><strong>Penjual:</strong> <?= $produk->nama_penjual ?></p>
                    <p class="mb-2"><strong>Alamat:</strong> <?= $produk->alamat_penjual ?></p>
                    <a href="https://wa.me/<?= $produk->no_wa ?>" target="_blank" class="btn btn-success btn-sm w-100">
                        <i class="fab fa-whatsapp"></i> Beli via WhatsApp
                    </a>
                  </div>
                </div>
              </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Script untuk Filter Produk -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const produkItems = document.querySelectorAll('.produk-item');

    searchInput.addEventListener('input', function () {
        const keyword = searchInput.value.toLowerCase();

        produkItems.forEach(function (item) {
            const nama = item.querySelector('.card-title').textContent.toLowerCase();
            item.style.display = nama.includes(keyword) ? 'block' : 'none';
        });
    });
});
</script>
