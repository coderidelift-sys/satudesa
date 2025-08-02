<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengajuan Surat Lahir</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Surat Lahir</a></li>
                <li class="breadcrumb-item active">Data Surat Lahir</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengajuan Surat Lahir</h5>
                        <!-- Menampilkan pesan sukses -->
                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                            <br>
                        </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                            <br>
                        </div>
                        <?php endif; ?>

                        <ul class="list-unstyled">
                            <li>
                                <ul class="list-unstyled">
                                    <li>
                                        <div class="alert alert-primary alert-dismissible fade show" role="alert"
                                            style="padding: 8px 12px; margin-bottom: 5px; font-size: 14px;">
                                            <h5 class="alert-heading" style="margin-bottom: 4px; font-size: 16px;">
                                                Informasi Penting!</h5>
                                            <ul style="margin-left: 15px; padding-left: 20px;">
                                                <li>Untuk menampilkan tombol Whatsapp, pastikan status surat sudah
                                                    <strong>SELESAI</strong>.
                                                </li>
                                                <li>Link file PDF akan kadaluarsa selama 24 jam terhitung setelah
                                                    berhasil dikirim ke Whatsapp.</li>
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    </li>
                                </ul>

                            </li>
                        </ul>

                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahSuratModal">
                            <i class="bi bi-plus-circle"></i> Tambah Pengajuan Surat
                        </button>


                        <!-- Table to Display Pengajuan Data -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anak</th>
                                        <th>Nama Ibu</th>
                                        <th>Nama Ayah</th>
                                        <th>Tgl Kelahiran</th>
                                        <th>Lokasi Lahir</th>
                                        <th>Anak Ke</th>
                                        <th>Gender</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Status</th>
                                        <th>Tgl Update</th>
                                        <th>Expired At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($suratlahir as $item) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item->nama_anak ?></td>
                                        <td><?= $item->nama_ibu ?></td>
                                        <td><?= $item->nama_ayah ?></td>
                                        <td><?= $item->tgl_lahir ?></td>
                                        <td><?= $item->lokasi_lahir ?></td>
                                        <td><?= $item->anak_ke ?></td>
                                        <td><?= $item->gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                        <td><?= $item->tgl_pengajuan ?></td>
                                        <td>
                                            <?php if ($item->status == 'Pending') : ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            <?php elseif ($item->status == 'Diproses') : ?>
                                            <span class="badge bg-primary">Diproses</span>
                                            <?php elseif ($item->status == 'Selesai') : ?>
                                            <span class="badge bg-success">Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item->tgl_update ?></td>
                                        <td>
                                            <?php if (empty($item->expired_at)) : ?>
                                            <span class="badge bg-warning text-dark">Belum Dikirim</span>
                                            <?php elseif (strtotime($item->expired_at) < time()) : ?>
                                            <span class="badge bg-danger">Expired</span>
                                            <?php else : ?>
                                            <?= $item->expired_at ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-gear"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editSuratModal<?= $item->id ?>">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            onclick="konfirmasiDelete('<?= base_url('admin/surlah/delete/' . $item->id) ?>')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= base_url('admin/surlah/downloadskl/'.urlencode(base64_encode($item->id_alias))) ?>"
                                                            target="_blank" class="dropdown-item">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php if ($item->status == 'Selesai') : ?>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            onclick="konfirmasiKirimWa('<?= base_url('admin/surlah/kirim_surat/'.urlencode(base64_encode($item->id_alias))) ?>')">
                                                            <i class="bi bi-whatsapp"></i> Kirim
                                                        </a>
                                                        <?php else : ?>
                                                        <span class="dropdown-item text-muted">Tidak bisa kirim</span>
                                                        <?php endif; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php foreach ($suratlahir as $item) : ?>
                                    <!-- Modal Edit Pengajuan Surat Lahir -->
                                    <div class="modal fade" id="editSuratModal<?= $item->id ?>" tabindex="-1"
                                        aria-labelledby="editSuratLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSuratLabel">Edit Pengajuan Surat
                                                        Lahir</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <form action="<?= base_url('admin/surlah/update') ?>" method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $item->id ?>">

                                                        <div class="row">
                                                            <!-- Kolom Kiri -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="nama_anak" class="form-label">Nama
                                                                        Anak</label>
                                                                    <input type="text" name="nama_anak"
                                                                        class="form-control"
                                                                        value="<?= $item->nama_anak ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="nama_ibu" class="form-label">Nama
                                                                        Ibu</label>
                                                                    <select class="form-control" name="nama_ibu"
                                                                        id="nama_ibu_<?= $item->id ?>" required>
                                                                        <option value="<?= $item->nama_ibu ?>" selected>
                                                                            <?= $item->nama_ibu ?></option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <input type="text" class="form-control"
                                                                        id="umur_ibu_<?= $item->id ?>" name="umur_ibu"
                                                                        value="<?= $item->umur_ibu ?>" hidden>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="nama_ayah" class="form-label">Nama
                                                                        Ayah</label>
                                                                    <select class="form-control" name="nama_ayah"
                                                                        id="nama_ayah_<?= $item->id ?>" required>
                                                                        <option value="<?= $item->nama_ayah ?>"
                                                                            selected><?= $item->nama_ayah ?></option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <input type="text" class="form-control"
                                                                        id="umur_ayah_<?= $item->id ?>" name="umur_ayah"
                                                                        value="<?= $item->umur_ayah ?>" hidden>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="tgl_lahir" class="form-label">Tanggal
                                                                        Lahir Anak</label>
                                                                    <input type="date" name="tgl_lahir"
                                                                        class="form-control"
                                                                        value="<?= $item->tgl_lahir ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="lokasi_lahir" class="form-label">Lokasi
                                                                        Lahir</label>
                                                                    <input type="text" name="lokasi_lahir"
                                                                        class="form-control"
                                                                        value="<?= $item->lokasi_lahir ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="anak_ke" class="form-label">Anak
                                                                        Ke</label>
                                                                    <input type="number" name="anak_ke"
                                                                        class="form-control"
                                                                        value="<?= $item->anak_ke ?>" required>
                                                                </div>
                                                            </div>

                                                            <!-- Kolom Kanan -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="gender" class="form-label">Jenis
                                                                        Kelamin</label>
                                                                    <select name="gender" class="form-control" required>
                                                                        <option value="L"
                                                                            <?= $item->gender == 'L' ? 'selected' : '' ?>>
                                                                            Laki-laki</option>
                                                                        <option value="P"
                                                                            <?= $item->gender == 'P' ? 'selected' : '' ?>>
                                                                            Perempuan</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Agama Ibu -->
                                                                <div class="mb-3">
                                                                    <label for="agama_ibu" class="form-label">Agama
                                                                        Ibu</label>
                                                                    <select class="form-control" name="agama_ibu"
                                                                        required>
                                                                        <option value="Islam"
                                                                            <?= $item->agama_ibu == 'Islam' ? 'selected' : '' ?>>
                                                                            Islam</option>
                                                                        <option value="Kristen"
                                                                            <?= $item->agama_ibu == 'Kristen' ? 'selected' : '' ?>>
                                                                            Kristen</option>
                                                                        <option value="Katolik"
                                                                            <?= $item->agama_ibu == 'Katolik' ? 'selected' : '' ?>>
                                                                            Katolik</option>
                                                                        <option value="Hindu"
                                                                            <?= $item->agama_ibu == 'Hindu' ? 'selected' : '' ?>>
                                                                            Hindu</option>
                                                                        <option value="Buddha"
                                                                            <?= $item->agama_ibu == 'Buddha' ? 'selected' : '' ?>>
                                                                            Buddha</option>
                                                                        <option value="Konghucu"
                                                                            <?= $item->agama_ibu == 'Konghucu' ? 'selected' : '' ?>>
                                                                            Konghucu</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Agama Ayah -->
                                                                <div class="mb-3">
                                                                    <label for="agama_ayah" class="form-label">Agama
                                                                        Ayah</label>
                                                                    <select class="form-control" name="agama_ayah"
                                                                        required>
                                                                        <option value="Islam"
                                                                            <?= $item->agama_ayah == 'Islam' ? 'selected' : '' ?>>
                                                                            Islam</option>
                                                                        <option value="Kristen"
                                                                            <?= $item->agama_ayah == 'Kristen' ? 'selected' : '' ?>>
                                                                            Kristen</option>
                                                                        <option value="Katolik"
                                                                            <?= $item->agama_ayah == 'Katolik' ? 'selected' : '' ?>>
                                                                            Katolik</option>
                                                                        <option value="Hindu"
                                                                            <?= $item->agama_ayah == 'Hindu' ? 'selected' : '' ?>>
                                                                            Hindu</option>
                                                                        <option value="Buddha"
                                                                            <?= $item->agama_ayah == 'Buddha' ? 'selected' : '' ?>>
                                                                            Buddha</option>
                                                                        <option value="Konghucu"
                                                                            <?= $item->agama_ayah == 'Konghucu' ? 'selected' : '' ?>>
                                                                            Konghucu</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Alamat -->
                                                                <div class="mb-3">
                                                                    <label for="alamat"
                                                                        class="form-label">Alamat</label>
                                                                    <textarea name="alamat" class="form-control"
                                                                        rows="3"
                                                                        required><?= $item->alamat ?></textarea>
                                                                </div>

                                                                <!-- No HP -->
                                                                <div class="mb-3">
                                                                    <label for="no_wa" class="form-label">No HP</label>
                                                                    <input type="text" name="no_wa" class="form-control"
                                                                        value="<?= $item->no_wa ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="status"
                                                                        class="form-label">Status</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="Pending"
                                                                            <?= $item->status == 'Pending' ? 'selected' : '' ?>>
                                                                            Pending</option>
                                                                        <option value="Diproses"
                                                                            <?= $item->status == 'Diproses' ? 'selected' : '' ?>>
                                                                            Diproses</option>
                                                                        <option value="Selesai"
                                                                            <?= $item->status == 'Selesai' ? 'selected' : '' ?>>
                                                                            Selesai</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan
                                                            Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- Modal Tambah Pengajuan Surat -->
<div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- Perbesar Modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSuratLabel">Tambah Pengajuan Surat Lahir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/surlah/add') ?>" method="post">
                <div class="modal-body">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_anak" class="form-label">Nama Anak</label>
                                <input type="text" name="nama_anak" class="form-control"
                                    placeholder="Masukkan nama anak" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu *Ketik huruf awal nama*</label>
                                <select class="form-control" name="nama_ibu" id="nama_ibu" required>
                                    <option value="">Pilih Nama Ibu</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" id="umur_ibu" name="umur_ibu" hidden>
                            </div>

                            <div class="mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah *Ketik huruf awal nama*</label>
                                <select class="form-control" name="nama_ayah" id="nama_ayah" required>
                                    <option value="">Pilih Nama Ayah</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" id="umur_ayah" name="umur_ayah" hidden>
                            </div>


                            <div class="mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir Anak</label>
                                <input type="date" name="tgl_lahir" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="lokasi_lahir" class="form-label">Lokasi Lahir</label>
                                <input type="text" name="lokasi_lahir" class="form-control"
                                    placeholder="Contoh: RSUD Cisolok" required>
                            </div>

                            <div class="mb-3">
                                <label for="anak_ke" class="form-label">Anak Ke</label>
                                <input type="number" name="anak_ke" class="form-control" placeholder="Contoh: 2"
                                    required>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin Anak</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <!-- Agama Ibu -->
                            <div class="mb-3">
                                <label for="agama_ibu" class="form-label">Agama Ibu</label>
                                <select class="form-control" name="agama_ibu" id="agama_ibu" required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <!-- Agama Ayah -->
                            <div class="mb-3">
                                <label for="agama_ayah" class="form-label">Agama Ayah</label>
                                <select class="form-control" name="agama_ayah" id="agama_ayah" required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>


                            <!-- Alamat (Hanya Muncul dari Nama Ayah) -->
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3"
                                    placeholder="Alamat otomatis dari Nama Ayah" required readonly></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="no_wa" class="form-label">No HP</label>
                                <input type="text" name="no_wa" class="form-control" placeholder="Contoh: 08123456789"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div> <!-- End Row -->
                </div> <!-- End Modal Body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>