<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengajuan Surat Kedukaan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Surat sukem</a></li>
                <li class="breadcrumb-item active">Data Surat sukem</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengajuan Surat Kedukaan</h5>
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
                                        <th>Gender</th>
                                        <th>Umur</th>
                                        <th>Pekerjaan</th>
                                        <th>Alamat</th>
                                        <th>Tgl Kedukaan</th>
                                        <th>Lokasi Kedukaan</th>
                                        <th>Keterangan</th>
                                        <th>Nama Pelapor</th>
                                        <th>Hubungan</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Status</th>
                                        <th>Tgl Update</th>
                                        <th>Expired At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($sukem as $item) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item->no_pengajuan ?></td>
                                        <td><?= $item->no_surat ?></td>
                                        <td><?= $item->nik ?></td>
                                        <td><?= $item->nama ?></td>
                                        <td><?= $item->gender ?></td>
                                        <td><?= $item->umur ?> Tahun</td>
                                        <td><?= $item->pekerjaan ?></td>
                                        <td>Kp. <?= $item->alamat ?> RT.<?= $item->rt ?> RT.<?= $item->rw ?>
                                            Desa.<?= $item->desa ?> Kec.<?= $item->kecamatan ?> Kab.<?= $item->kota ?>
                                            <?= $item->propinsi ?></td>
                                        <td><?= $item->tgl_kedukaan ?></td>
                                        <td><?= $item->lokasi_kedukaan ?></td>
                                        <td><?= $item->keterangan ?></td>
                                        <td><?= $item->nama_pelapor ?></td>
                                        <td><?= $item->hubungan ?></td>
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
                                                            onclick="konfirmasiDelete('<?= base_url('admin/sukem/delete/' . $item->id) ?>')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= base_url('admin/sukem/downloadskk/'.urlencode(base64_encode($item->id_alias))) ?>"
                                                            target="_blank" class="dropdown-item">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php if ($item->status == 'Selesai') : ?>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            onclick="konfirmasiKirimWa('<?= base_url('admin/sukem/kirim_surat/'.urlencode(base64_encode($item->id_alias))) ?>')">
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
                                    <?php foreach ($sukem as $item) : ?>
                                    <!-- Modal Edit Pengajuan Surat Sukem -->
                                    <div class="modal fade" id="editSuratModal<?= $item->id ?>" tabindex="-1"
                                        aria-labelledby="editSuratLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <!-- Modal diperlebar -->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSuratLabel">Edit Pengajuan Surat
                                                        Kedukaan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <form action="<?= base_url('admin/sukem/update') ?>" method="post">
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
                                                                    <label for="tgl_kedukaan" class="form-label">Tanggal
                                                                        Kedukaan</label>
                                                                    <input type="date" name="tgl_kedukaan"
                                                                        class="form-control"
                                                                        value="<?= $item->tgl_kedukaan ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="lokasi_kedukaan"
                                                                        class="form-label">Lokasi Kedukaan</label>
                                                                    <textarea name="lokasi_kedukaan"
                                                                        class="form-control" rows="2"
                                                                        required><?= $item->lokasi_kedukaan ?></textarea>
                                                                </div>
                                                            </div>

                                                            <!-- Kolom Kanan -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="keterangan"
                                                                        class="form-label">Keterangan</label>
                                                                    <textarea name="keterangan" class="form-control"
                                                                        rows="2"
                                                                        required><?= $item->keterangan ?></textarea>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="nama_pelapor" class="form-label">Nama
                                                                        Pelapor</label>
                                                                    <input type="text" name="nama_pelapor"
                                                                        class="form-control"
                                                                        value="<?= $item->nama_pelapor ?>" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="hubungan" class="form-label">Hubungan
                                                                        dengan Almarhum/Almarhumah</label>
                                                                    <input type="text" name="hubungan"
                                                                        class="form-control"
                                                                        value="<?= $item->hubungan ?>" required>
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
                    $selected = (isset($item->tanda_tangan) && $item->tanda_tangan === $value) ? 'selected' : '';
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

<!-- Modal Tambah Pengajuan Surat Sukem -->
<div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- Modal diperlebar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSuratLabel">Tambah Pengajuan Surat Kedukaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/sukem/add') ?>" method="post">
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
                          <select name="nik" id="nik" class="form-control" required>
                            <option value="">-- Pilih NIK --</option>
                            <?php foreach ($anggota_keluarga as $a): ?>
                                <option value="<?= $a->nik ?>"><?= $a->nik ?> - <?= $a->nama_lengkap ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>

                            <div class="mb-3">
                                <label for="tgl_kedukaan" class="form-label">Tanggal Kedukaan</label>
                                <input type="date" name="tgl_kedukaan" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="lokasi_kedukaan" class="form-label">Lokasi Kedukaan</label>
                                <textarea name="lokasi_kedukaan" class="form-control" rows="2"
                                    placeholder="Masukkan Lokasi Kedukaan" required></textarea>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2"
                                    placeholder="Masukkan Keterangan" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="nama_pelapor" class="form-label">Nama Pelapor</label>
                                <input type="text" name="nama_pelapor" class="form-control"
                                    placeholder="Masukkan Nama Pelapor" required>
                            </div>

                            <div class="mb-3">
                                <label for="hubungan" class="form-label">Hubungan dengan Almarhum/Almarhumah</label>
                                <input type="text" name="hubungan" class="form-control"
                                    placeholder="Contoh: Istri, Anak, Saudara, Tetangga" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_wa" class="form-label">No WA</label>
                                <input type="text" name="no_wa" class="form-control" placeholder="Contoh: 0856xxxxxx"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="tanda_tangan" class="form-label">Tanda Tangan</label>
                                <select name="tanda_tangan" class="form-control" required>
                                    <option value="">-- Pilih Penandatangan --</option>
                                    <?php foreach ($perangkat_desa as $perangkat) : ?>
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