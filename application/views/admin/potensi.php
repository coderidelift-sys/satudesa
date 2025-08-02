<main id="main" class="main">
    <div class="pagetitle">
        <h1>Potensi Desa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Data</a></li>
                <li class="breadcrumb-item active">Potensi Desa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Potensi Desa</h5>

                        <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success"> <?= $this->session->flashdata('success') ?> </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger"> <?= $this->session->flashdata('error') ?> </div>
                        <?php endif; ?>

                        <button type="button" class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addPotensiModal">
                            <i class="bi bi-plus-circle"></i> Tambah Potensi
                        </button>

                        <div class="table-responsive">
                           
 <table id="dataPotensiTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Dusun</th>
                                        <th>Bidang</th>
                                        <th>Objek</th>
                                        <th>Penghasilan Tahunan</th>
                                        <th>Nominal</th>
                                        <th>Update at</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>



<!-- Add Potensi Modal -->
<div class="modal fade" id="addPotensiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Potensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/potensidesa/add') ?>" method="post">
                <div class="modal-body">
                    <label class="form-label">NIK</label>
                    <select class="form-control" name="nik" id="nik_potensi" required>
                        <option value="">Pilih NIK</option>
                    </select>
                    <label class="form-label">Bidang</label>
                    <input type="text" class="form-control" name="bidang" placeholder="Pertanian, Perkebunan, UMKM Dll"
                        required>
                    <label class="form-label">Objek</label>
                    <input type="text" class="form-control" name="objek"
                        placeholder="Padi, Kebun Jagung, Keripik Pisang Dll" required>
                    <label class="form-label">Penghasilan Tahunan</label>
                    <input type="number" class="form-control" name="penghasilan_tahunan" required>
                    <label class="form-label">Satuan</label>
                    <select class="form-select" name="satuan" required>
                        <option value="">Pilih Satuan</option>
                        <optgroup label="Berat">
                            <option value="gram">Gram (g)</option>
                            <option value="kilogram">Kilogram (kg)</option>
                            <option value="kwintal">Kwintal</option>
                            <option value="ton">Ton</option>
                        </optgroup>
                        <optgroup label="Panjang">
                            <option value="centimeter">Centimeter (cm)</option>
                            <option value="meter">Meter (m)</option>
                            <option value="kilometer">Kilometer (km)</option>
                        </optgroup>
                        <optgroup label="Volume">
                            <option value="mililiter">Mililiter (mL)</option>
                            <option value="liter">Liter (L)</option>
                            <option value="meter_kubik">Kubik Meter (m³)</option>
                        </optgroup>
                        <optgroup label="Jumlah">
                            <option value="lembar">Lembar</option>
                            <option value="rim">Rim</option>
                            <option value="pack">Pack</option>
                            <option value="unit">Unit</option>
                            <option value="kodi">Kodi</option>
                            <option value="gross">Gross</option>
                        </optgroup>
                    </select>

                    <label class="form-label">Nominal Penghasilan (Perkiraan)</label>
                    <input type="number" class="form-control" name="nominal" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>