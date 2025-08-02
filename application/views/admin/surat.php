<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Pengajuan SKU dan SKTM</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Surat</a></li>
                <li class="breadcrumb-item active">Data Surat SKU dan SKTM</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengajuan SKU dan SKTM</h5>
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
                                        <th>No Pengajuan</th>
                                        <th>No Surat</th>
                                        <th>Jenis Surat</th>
                                        <th>NIK</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Tgl Update</th>
                                        <th>Expired At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($surat as $item) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item->no_pengajuan ?></td>
                                        <td><?= $item->no_surat ?></td>
                                        <td><?= $item->jenis_surat ?></td>
                                        <td><?= $item->nik ?></td>
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
                                        <td><?= $item->keterangan ?></td>
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
                                                            onclick="konfirmasiDelete('<?= base_url('admin/surat/delete/' . $item->id) ?>')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php if ($item->jenis_surat == 'Surat Keterangan Tidak Mampu') : ?>
                                                        <a href="<?= base_url('admin/surat/downloadsktm/'.urlencode(base64_encode($item->id_alias))) ?>"
                                                            target="_blank" class="dropdown-item">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                        <?php elseif ($item->jenis_surat == 'Surat Keterangan Usaha') : ?>
                                                        <a href="<?= base_url('admin/surat/downloadsku/'.urlencode(base64_encode($item->id_alias))) ?>"
                                                            target="_blank" class="dropdown-item">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                        <?php else : ?>
                                                        <span class="dropdown-item text-muted">Tidak ada file</span>
                                                        <?php endif; ?>
                                                    </li>

                                                    <?php if ($item->status == 'Selesai') : ?>
                                                    <li>
                                                        <?php if ($item->jenis_surat == 'Surat Keterangan Tidak Mampu') : ?>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            onclick="konfirmasiKirimWa('<?= base_url('admin/surat/kirim_surat/'.urlencode(base64_encode($item->id_alias))) ?>')">
                                                            <i class="bi bi-whatsapp"></i> Kirim
                                                        </a>
                                                        <?php elseif ($item->jenis_surat == 'Surat Keterangan Usaha') : ?>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            onclick="konfirmasiKirimWa('<?= base_url('admin/surat/kirim_surat/'.urlencode(base64_encode($item->id_alias))) ?>')">
                                                            <i class="bi bi-whatsapp"></i> Kirim
                                                        </a>
                                                        <?php else : ?>
                                                        <span class="dropdown-item text-muted">Tidak bisa kirim</span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <?php endif; ?>

                                                </ul>
                                            </div>
                                        </td>



                                    </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($surat as $item) : ?>
                                    <!-- Modal Edit Pengajuan Surat -->
                                    <div class="modal fade" id="editSuratModal<?= $item->id ?>" tabindex="-1"
                                        aria-labelledby="editSuratLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSuratLabel">Edit Status Pengajuan
                                                        Surat</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="<?= base_url('admin/surat/update_status') ?>"
                                                    method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $item->id ?>">

                                                        <!-- No Surat -->
                                                        <div class="mb-3">
                                                            <label for="no_wa" class="form-label">Input Nomor Surat
                                                            </label>
                                                            <input type="text" name="no_surat" class="form-control"
                                                                required placeholder="Input Nomor Surat/Dokumen"
                                                                value="-">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status Pengajuan
                                                                Surat</label>
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

                                                        <!-- Keterangan -->
                                                        <div class="mb-3">
                                                            <label for="keterangan" class="form-label">Tanggapan /
                                                                Keterangan</label>
                                                            <textarea name="keterangan" class="form-control" rows="3"
                                                                required
                                                                placeholder="Misal: Surat ini dibuat untuk persyaratan tertentu."><?= $item->keterangan ?></textarea>
                                                        </div>

                                                        <!-- No Whatsapp Aktif -->
                                                        <div class="mb-3">
                                                            <label for="no_wa" class="form-label">No Whatsapp
                                                                Aktif</label>
                                                            <input type="text" name="no_wa" class="form-control"
                                                                minlength="11" maxlength="13" required
                                                                placeholder="Misal: 0856xxxxxxxx"
                                                                value="<?= $item->no_wa ?>">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSuratLabel">Tambah Pengajuan Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/surat/add') ?>" method="post">
                <div class="modal-body">
                   <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <select name="nik" id="nik" class="form-control" required>
                            <option value="">-- Pilih NIK --</option>
                            <?php foreach ($anggota_keluarga as $a): ?>
                                <option value="<?= $a->nik ?>"><?= $a->nik ?> - <?= $a->nama_lengkap ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_surat" class="form-label">Jenis Surat</label>
                        <select name="jenis_surat" class="form-control" required>
                            <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                            <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="no_wa" class="form-label">No Whatsapp Aktif</label>
                        <input type="text" name="no_wa" class="form-control" required
                            placeholder="Misal : 0856xxxxxxxx">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>