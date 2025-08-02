<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengajuan Surat Status</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Surat Status</a></li>
                <li class="breadcrumb-item active">Data Surat Status</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengajuan Surat Status</h5>
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
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tgl Lahir</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Status</th>
                                        <th>Tgl Update</th>
                                        <th>Expired At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($status as $item) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item->no_pengajuan ?></td>
                                        <td><?= $item->no_surat ?></td>
                                        <td><?= $item->nik ?></td>
                                        <td><?= $item->nama ?></td>
                                        <td><?= $item->tempat_lahir ?></td>
                                        <td><?= $item->tgl_lahir ?></td>
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
                                                            onclick="konfirmasiDelete('<?= base_url('admin/suratstatus/delete/' . $item->id) ?>')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= base_url('admin/suratstatus/downloadss/'.urlencode(base64_encode($item->id_alias))) ?>"
                                                            target="_blank" class="dropdown-item">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php if ($item->status == 'Selesai') : ?>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            onclick="konfirmasiKirimWa('<?= base_url('admin/suratstatus/kirim_surat/'.urlencode(base64_encode($item->id_alias))) ?>')">
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
                                    <?php foreach ($status as $item) : ?>
                                    <!-- Modal Edit Pengajuan Surat status -->
                                    <div class="modal fade" id="editSuratModal<?= $item->id ?>" tabindex="-1"
                                        aria-labelledby="editSuratLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSuratLabel">Edit Pengajuan Surat
                                                        status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <form action="<?= base_url('admin/suratstatus/update') ?>"
                                                    method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $item->id ?>">

                                                        <div class="row">
                                                            <!-- Kolom Kiri -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="no_surat" class="form-label">Nomor
                                                                        Surat</label>
                                                                    <input type="text" name="no_surat"
                                                                        class="form-control"
                                                                        value="<?= $item->no_surat ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="nik" class="form-label">NIK</label>
                                                                    <input type="text" name="nik" class="form-control"
                                                                        value="<?= $item->nik ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="nama" class="form-label">Nama
                                                                        Lengkap</label>
                                                                    <input type="text" name="nama" class="form-control"
                                                                        value="<?= $item->nama ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="tempat_lahir" class="form-label">Tempat
                                                                        Lahir</label>
                                                                    <input type="text" name="tempat_lahir"
                                                                        class="form-control"
                                                                        value="<?= $item->tempat_lahir ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="pekerjaan" class="form-label">Pekerjaan
                                                                    </label>
                                                                    <input type="text" name="pekerjaan"
                                                                        class="form-control"
                                                                        value="<?= $item->pekerjaan ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="tgl_lahir" class="form-label">Tanggal
                                                                        Lahir</label>
                                                                    <input type="date" name="tgl_lahir"
                                                                        class="form-control"
                                                                        value="<?= $item->tgl_lahir ?>" required>
                                                                </div>
                                                            </div>

                                                            <!-- Kolom Kanan -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="alamat_lengkap"
                                                                        class="form-label">Alamat Lengkap</label>
                                                                    <textarea name="alamat_lengkap" class="form-control"
                                                                        rows="3"
                                                                        required><?= $item->alamat_lengkap ?></textarea>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="status_kawin" class="form-label">Status
                                                                        Perkawinan
                                                                    </label>
                                                                    <input type="text" name="status_kawin"
                                                                        class="form-control"
                                                                        value="<?= $item->status_kawin ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="status_sekarang"
                                                                        class="form-label">Status
                                                                        Sekarang
                                                                    </label>
                                                                    <input type="text" name="status_sekarang"
                                                                        class="form-control"
                                                                        value="<?= $item->status_sekarang ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="no_wa" class="form-label">Nomor
                                                                        WhatsApp</label>
                                                                    <input type="number" name="no_wa"
                                                                        class="form-control" value="<?= $item->no_wa ?>"
                                                                        required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="status"
                                                                        class="form-label">Status</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="pending"
                                                                            <?= $item->status == 'Pending' ? 'selected' : '' ?>>
                                                                            Pending</option>
                                                                        <option value="diproses"
                                                                            <?= $item->status == 'Diproses' ? 'selected' : '' ?>>
                                                                            Diproses</option>
                                                                        <option value="selesai"
                                                                            <?= $item->status == 'Selesai' ? 'selected' : '' ?>>
                                                                            Selesai</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="tanda_tangan" class="form-label">Tanda
                                                                        Tangan</label>
                                                                    <select name="tanda_tangan" class="form-control"
                                                                        required>
                                                                        <option value="">-- Pilih Penandatangan --
                                                                        </option>
                                                                        <?php if (!empty($perangkat_desa)) : ?>
                                                                        <?php foreach ($perangkat_desa as $perangkat) : ?>
                                                                        <?php 
                    $value = htmlspecialchars($perangkat->jabatan . ' - ' . $perangkat->nama); 
                    $selected = (isset($item->tanda_tangan) && $item->tanda_tangan == $value) ? 'selected' : '';
                ?>
                                                                        <option value="<?= $value; ?>"
                                                                            <?= $selected; ?>>
                                                                            <?= $perangkat->jabatan . ' | ' . $perangkat->nama . ' | ' . $perangkat->nik; ?>
                                                                        </option>
                                                                        <?php endforeach; ?>
                                                                        <?php else : ?>
                                                                        <option value="">Data perangkat desa tidak
                                                                            tersedia</option>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </div>


                                                            </div>
                                                        </div> <!-- End Row -->
                                                    </div> <!-- End Modal Body -->

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

<!-- Modal Tambah Pengajuan Surat status -->
<div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSuratLabel">Tambah Pengajuan Surat status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/suratstatus/add') ?>" method="post">
                <div class="modal-body">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_surat" class="form-label">Nomor Surat</label>
                                <input type="text" name="no_surat" class="form-control"
                                    placeholder="Masukkan Nomor Surat" value="-" required>
                            </div>

                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    placeholder="Masukkan Tempat Lahir" required>
                            </div>

                            <div class="mb-3">
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="form-control" placeholder="Masukan Pekerjaan"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" class="form-control" required>
                            </div>
                        </div>


                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" class="form-control" rows="3"
                                    placeholder="Masukkan Alamat Lengkap" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status_kawin" class="form-label">Status Perkawinan</label>
                                <input type="text" name="status_kawin" class="form-control"
                                    placeholder="Ex : Cerai, Kawin dll" required>
                            </div>
                            <div class="mb-3">
                                <label for="status_sekarang" class="form-label">Status Sekarang</label>
                                <input type="text" name="status_sekarang" class="form-control"
                                    placeholder="Ex : Belum Kawin dll" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="no_wa" class="form-control" placeholder="Contoh: 08123456789"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending">Pending</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tanda_tangan" class="form-label">Tanda Tangan</label>
                                <select name="tanda_tangan" class="form-control" required>
                                    <option value="">-- Pilih Penandatangan --</option>
                                    <?php foreach ($perangkat_desa as $perangkat): ?>
                                    <option value="<?= $perangkat->jabatan . ' - ' . $perangkat->nama; ?>">
                                        <?= $perangkat->jabatan . ' | ' . $perangkat->nama . ' | ' . $perangkat->nik; ?>
                                    </option>
                                    <?php endforeach; ?>
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