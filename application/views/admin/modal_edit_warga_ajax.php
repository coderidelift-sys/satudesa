<!-- Modal Edit Warga -->
<div class="modal fade" id="editWargaModal" tabindex="-1" aria-labelledby="editWargaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/warga/edit/' . $item->id); ?>" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor KK</label>
                            <input type="text" class="form-control" name="nomor_kk" value="<?= $item->nomor_kk; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Kepala Keluarga</label>
                            <input type="text" class="form-control" name="kepala_keluarga" value="<?= $item->kepala_keluarga; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Dusun</label>
                            <select class="form-select select2" name="alamat" required>
                                <option value="">-- Pilih Dusun --</option>
                                <?php foreach ($dusun as $d): ?>
                                    <option value="<?= $d->nama_dusun; ?>" <?= ($item->alamat == $d->nama_dusun) ? 'selected' : ''; ?>>
                                        <?= $d->nama_dusun; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RT</label>
                            <input type="number" class="form-control" name="rt" value="<?= $item->rt; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">RW</label>
                            <input type="number" class="form-control" name="rw" value="<?= $item->rw; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Desa</label>
                            <input type="text" class="form-control" name="desa" value="<?= $item->desa; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" name="kecamatan" value="<?= $item->kecamatan; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota</label>
                            <input type="text" class="form-control" name="kota" value="<?= $item->kota; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Pos</label>
                            <input type="number" class="form-control" name="kode_pos" value="<?= $item->kode_pos; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Propinsi</label>
                            <input type="text" class="form-control" name="propinsi" value="<?= $item->propinsi; ?>" required>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
