</div>

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span>SATUDESA APPS</span></strong>. All Rights Reserved
  </div>
</footer>
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<!-- jQuery (Wajib sebelum plugin lain) -->
<script src="<?= base_url('assets/luar/jquery-3.6.0.min.js') ?>"></script>

<!-- Vendor JS Files -->
<script src="<?= base_url('assets_admin/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/apexcharts/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/chart.js/chart.umd.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/echarts/echarts.min.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/quill/quill.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/simple-datatables/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/tinymce/tinymce.min.js') ?>"></script>
<script src="<?= base_url('assets_admin/vendor/php-email-form/validate.js') ?>"></script>

<!-- DataTables -->
<script src="<?= base_url('assets/luar/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/luar/dataTables.bootstrap4.min.js') ?>"></script>

<!-- SweetAlert2 -->
<script src="<?= base_url('assets/assets_js/js/sweetalert2@11.js') ?>"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Main Template JS -->
<script src="<?= base_url('assets_admin/js/main.js') ?>"></script>

<script>
$(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();
    let id = $(this).data('id');

    $.ajax({
        url: "<?= base_url('admin/warga/get_warga_by_id/') ?>" + id,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                // Hapus modal lama dulu supaya gak numpuk
                $('#editWargaModal').remove();

                // Append modal baru ke body
                $('body').append(res.modal);

                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('editWargaModal'));
                modal.show();

                // Optional: Inisialisasi select2 jika kamu pakai
                $('.select2').select2({
                    dropdownParent: $('#editWargaModal')
                });
            } else {
                alert(res.message);
            }
        },
        error: function() {
            alert('Gagal memuat data.');
        }
    });
});

</script>


<!-- Inisialisasi DataTables -->
<script>
$(document).ready(function () {
  $('#dataWargaTable').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?= base_url('admin/warga/ajax_list') ?>",
      "type": "POST"
    },
    "columnDefs": [{
      "orderable": false,
      "targets": [0, 12]
    }]
  });
});
</script>

<script>
    $(document).on('click', '.btn-edit-warga', function () {
    var id = $(this).data('id');
    $.ajax({
        url: '<?= base_url("admin/warga/get_edit_modal/") ?>' + id,
        type: 'GET',
        success: function (response) {
            $('#modalContainer').html(response);
            $('#editWargaModal' + id).modal('show');
        },
        error: function () {
            alert('Gagal memuat data modal.');
        }
    });
});

</script>

<script>
$(document).ready(function() {
    // Inisialisasi semua <select> sebagai Select2
    $('select').each(function() {
        $(this).select2({
            dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : $(this).parent(),
            theme: 'bootstrap-5',
            allowClear: true,
            placeholder: $(this).attr('placeholder') || '-- Pilih --'
        });
    });
});
</script>



<!-- NIK DI MENU POTENSI -->
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?= base_url('admin/potensidesa/get_nik_options') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {
            let options = '<option value="">Pilih NIK</option>';
            data.forEach(item => {
                options +=
                    `<option value="${item.nik}">${item.nama_lengkap} | ${item.nik}</option>`;
            });
            $("#nik_potensi").html(options);
            $("#nik_potensi_edit").html(options);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});
</script>

<script>
    $('#formEditPotensi').submit(function(e) {
  e.preventDefault();
  $.post("<?= base_url('admin/potensidesa/update') ?>", $(this).serialize(), function(response) {
    const res = JSON.parse(response);
    if (res.status === 'success') {
      $('#modalEditPotensi').modal('hide');
      alert('Data berhasil diperbarui!');
      location.reload(); // atau datatable.ajax.reload() jika pakai datatables
    } else {
      alert('Gagal: ' + res.msg);
    }
  });
});

</script>

<script>
$(document).ready(function() {
    $.ajax({
        url: "<?= base_url('admin/surlah/get_nama_ortu_options') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log("Data diterima:", data); // Debug: cek hasil dari controller

            let options_ibu = '<option value="">Pilih Nama Ibu</option>';
            let options_ayah = '<option value="">Pilih Nama Ayah</option>';

            data.forEach(item => {
                let displayText = `${item.nama_lengkap} | ${item.nik} | ${item.umur} tahun`;
                let posisi = item.posisi ? item.posisi.toLowerCase() : "";

                if (posisi === "istri") {
                    options_ibu +=
                        `<option value="${item.nama_lengkap}" data-umur="${item.umur}">${displayText}</option>`;
                } else if (posisi === "kepala rumah tangga") {
                    options_ayah +=
                        `<option value="${item.nama_lengkap}" data-umur="${item.umur}">${displayText}</option>`;
                }
            });

            $("#nama_ibu").html(options_ibu);
            $("#nama_ayah").html(options_ayah);
        },
        error: function(xhr, status, error) {
            console.error("Error:", xhr.responseText);
        }
    });

    // Otomatis isi umur ibu
    $("#nama_ibu").change(function() {
        let umur = $(this).find(":selected").data("umur");
        $("#umur_ibu").val(umur || "");
    });

    // Otomatis isi umur ayah
    $("#nama_ayah").change(function() {
        let umur = $(this).find(":selected").data("umur");
        $("#umur_ayah").val(umur || "");
    });
});
</script>


<script>
$(document).ready(function() {
    $("#nama_ayah").change(function() {
        let namaAyah = $(this).val();

        if (namaAyah) {
            $.ajax({
                url: "<?= base_url('admin/surlah/get_alamat_by_nama') ?>",
                type: "GET",
                data: {
                    nama: namaAyah
                },
                dataType: "json",
                success: function(data) {
                    let alamatLengkap =
                        `${data.alamat}, RT ${data.rt}/RW ${data.rw}, ${data.desa}, ${data.kecamatan}, ${data.kota}, ${data.kode_pos}, ${data.propinsi}`;
                    $("#alamat").val(alamatLengkap);
                },
                error: function(xhr) {
                    console.error("Gagal mengambil data alamat:", xhr.responseText);
                    $("#alamat").val("");
                }
            });
        } else {
            $("#alamat").val(""); // Kosongkan jika Nama Ayah tidak dipilih
        }
    });
});
</script>

<script>
$(document).ready(function() {
    let namaIbuTerpilih = "<?= $item->nama_ibu ?>";
    let namaAyahTerpilih = "<?= $item->nama_ayah ?>";

    // Load Nama Ibu
    $.ajax({
        url: "<?= base_url('admin/surlah/get_nama_ortu_options') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {
            let options = '<option value="">Pilih Nama Ibu</option>';
            data.forEach(item => {
                let selected = (item.nama_lengkap === namaIbuTerpilih) ? "selected" : "";
                options +=
                    `<option value="${item.nama_lengkap}" ${selected}>${item.nama_lengkap} | ${item.nik}</option>`;
            });
            $("#nama_ibu_edit").html(options);
        },
        error: function(xhr) {
            console.error("Gagal memuat data ibu:", xhr.responseText);
        }
    });

    // Load Nama Ayah
    $.ajax({
        url: "<?= base_url('admin/surlah/get_nama_ortu_options') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {
            let options = '<option value="">Pilih Nama Ayah</option>';
            data.forEach(item => {
                let selected = (item.nama_lengkap === namaAyahTerpilih) ? "selected" : "";
                options +=
                    `<option value="${item.nama_lengkap}" ${selected}>${item.nama_lengkap} | ${item.nik}</option>`;
            });
            $("#nama_ayah_edit").html(options);
        },
        error: function(xhr) {
            console.error("Gagal memuat data ayah:", xhr.responseText);
        }
    });
});
</script>

<script>
function konfirmasiDelete(url) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
}

function konfirmasiKirimWa(url) {
    Swal.fire({
        title: 'Kirim Pesan WhatsApp?',
        text: "Pesan akan dikirim ke pemohon!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#25D366',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
}
</script>
<script>
function updateSapaanDanWaktu() {
    const sekarang = new Date();
    const jam = sekarang.getHours();
    const menit = sekarang.getMinutes();
    const detik = sekarang.getSeconds();

    const hari = sekarang.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    let sapaan = '';
    if (jam >= 5 && jam < 11) {
        sapaan = 'Selamat Pagi';
    } else if (jam >= 11 && jam < 15) {
        sapaan = 'Selamat Siang';
    } else if (jam >= 15 && jam < 18) {
        sapaan = 'Selamat Sore';
    } else {
        sapaan = 'Selamat Malam';
    }

    document.getElementById('sapaan').innerText = sapaan;
    document.getElementById('waktu-sekarang').innerText =
        `${hari}, ${jam.toString().padStart(2, '0')}:${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;
}

setInterval(updateSapaanDanWaktu, 1000);
updateSapaanDanWaktu(); // Panggil pertama kali agar tidak menunggu interval
</script>

<!-- Add this script at the end of your view or in a separate JS file -->
<script>
$(document).ready(function() {
    $('#logdataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('admin/logdata/get_logdata_json'); ?>",
            "type": "GET"
        },
        "columns": [{
                "data": null,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "username"
            },
            {
                "data": "action"
            },
            {
                "data": "description"
            },
            {
                "data": "ip_address"
            },
            {
                "data": "user_agent"
            },
            {
                "data": "created_at"
            }
        ],
        "responsive": true,
        "order": [
            [6, 'desc']
        ] // Default sort by created_at descending
    });
});
</script>
<script>
$(document).ready(function() {
    $('#wargaTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('admin/semuawarga/get_warga_json'); ?>",
            "type": "GET"
        },
        "columns": [{
                "data": null,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "nomor_kk"
            },
            {
                "data": "nik"
            },
            {
                "data": "nama_lengkap"
            },
            {
                "data": "tgl_lahir",
                "render": function(data, type, row) {
                    if (data) {
                        // Ubah format tanggal dari YYYY-MM-DD ke DD-MM-YYYY
                        var dateParts = data.split('-');
                        if (dateParts.length === 3) {
                            return dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                        }
                    }
                    return data; // Jika format tidak sesuai, kembalikan aslinya
                }
            },
            {
                "data": "gender"
            },
            {
                "data": "agama"
            },
            {
                "data": "status_kawin"
            },
            {
                "data": "posisi"
            },
            {
                "data": "pekerjaan"
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return row.alamat + ', RT ' + row.rt + '/RW ' + row.rw + ', ' +
                        row.desa + ', ' + row.kecamatan + ', ' +
                        row.kota + ', ' + row.kode_pos + ', ' + row.propinsi;
                }
            }
        ],
        "order": [
            [0, 'asc']
        ],
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json"
        }
    });
});
</script>

<script>
function loadDataTable(selector, ajaxUrl, nonOrderableColumns = [], extraData = {}) {
    $(document).ready(function () {
        $(selector).DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": ajaxUrl,
                "type": "POST",
                "data": function (d) {
                    // Tambahkan data tambahan ke parameter DataTables
                    return $.extend({}, d, extraData);
                }
            },
            "columnDefs": [{
                "orderable": false,
                "targets": nonOrderableColumns
            }]
        });
    });
}

</script>

<script>
loadDataTable('#dataKontakTable', "<?= base_url('admin/kontak/ajax_list') ?>", [4]);
</script>

<script>
loadDataTable('#dataShopTable', "<?= base_url('admin/shopping/ajax_list') ?>", [8]);
</script>

<script>
loadDataTable('#dataRegulasiTable', "<?= base_url('admin/regulasi/ajax_list') ?>", [4]);
</script>

<script>
loadDataTable('#dataPembaruanTable', "<?= base_url('admin/pembaruan/ajax_list') ?>", [4]);
</script>


<script>
loadDataTable('#dataFaqTable', "<?= base_url('admin/faq/ajax_list') ?>", [3]);
</script>

<script>
loadDataTable('#dataPengumumanTable', "<?= base_url('admin/pengumuman/ajax_list') ?>", [6]);
</script>

<script>
loadDataTable('#dataBansosTable', "<?= base_url('admin/bansos/ajax_list') ?>", [7]);
</script>


<script>
loadDataTable('#dataDusunTable', "<?= base_url('admin/dusun/ajax_list') ?>", [6]);
</script>

<script>
loadDataTable('#dataPotensiTable', "<?= base_url('admin/potensidesa/ajax_list') ?>", [9]);
</script>

<script>
loadDataTable('#dataDetailTable', "<?= base_url('admin/warga/ajax_list_anggota') ?>", [9], {
    nomor_kk: "<?= $this->uri->segment(4) ?>" 
});
</script>

<script>
loadDataTable('#dataAduanTable', "<?= base_url('admin/aduan/ajax_list') ?>", [9]); 
</script>

<script>
loadDataTable('#dataKegiatanTable', "<?= base_url('admin/kegiatan/ajax_list') ?>", [7]); 
</script>

<script>
loadDataTable('#dataWargaTable', "<?= base_url('admin/warga/ajax_list') ?>", [12]); 
</script>

<script>
loadDataTable('#dataWisataTable', "<?= base_url('admin/wisata/ajax_list') ?>", [4]); 
</script>

<script>
loadDataTable('#dataUmkmTable', "<?= base_url('admin/umkm/ajax_list') ?>", [5]); 
</script>

<script>
loadDataTable('#dataBannerTable', "<?= base_url('admin/banner/ajax_list') ?>", [2]); 
</script>

<script>
loadDataTable('#dataHukumTable', "<?= base_url('admin/hukum/ajax_list') ?>", [2]); 
</script>

<script>
  loadDataTable('#dataDaftarHukumTable', "<?= base_url('admin/hukum/ajax_daftar_hukum/' . $jenis_hukum->id) ?>", [5]);
</script>

<script>
loadDataTable('#dataUserTable', "<?= base_url('admin/user/ajax_list') ?>", [6]); 
</script>


<script>
// Saat halaman selesai load: sembunyikan overlay
window.addEventListener('load', function() {
    const ov = document.getElementById('loading-overlay');
    if (ov) ov.style.display = 'none';
});

// Saat halaman akan unload/reload: tampilkan overlay
window.addEventListener('beforeunload', function() {
    const ov = document.getElementById('loading-overlay');
    if (ov) ov.style.display = 'flex';
});
</script>

<script>
document.getElementById('backupBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Backup Database?',
        text: "File SQL akan diunduh dan disimpan di perangkat Anda.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Buka URL backup di tab baru
            window.open('<?= base_url('admin/pengaturan/backup_database') ?>', '_blank');

            // Tampilkan notifikasi setelah klik "Ya"
            Swal.fire({
                icon: 'success',
                title: 'Sedang memproses...',
                text: 'File backup sedang disiapkan untuk diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>

<script>
document.getElementById('downloadTemplateAnggotaBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Download Template?',
        text: "Template Excel akan diunduh ke perangkat Anda.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Ya, unduh sekarang',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Buka URL download di tab baru
            window.open("<?= site_url('admin/warga/download_template_anggota_keluarga') ?>", '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Sedang memproses...',
                text: 'File sedang disiapkan untuk diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>

<script>
document.getElementById('downloadTemplateWargaBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Download Template?',
        text: "Template Excel akan diunduh ke perangkat Anda.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Ya, unduh sekarang',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("<?= site_url('admin/warga/download_template') ?>", '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Sedang memproses...',
                text: 'File template sedang disiapkan.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>
<script>
document.getElementById('downloadExcelBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Download Excel?',
        text: "Data warga akan diunduh dalam format Excel.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Ya, unduh',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("<?= base_url('admin/warga/export') ?>", '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Mengunduh...',
                text: 'File Excel sedang disiapkan.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});

document.getElementById('downloadPdfBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Download PDF?',
        text: "Data warga akan diunduh dalam format PDF.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Ya, unduh',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("<?= base_url('admin/warga/download_pdf') ?>", '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Mengunduh...',
                text: 'File PDF sedang disiapkan.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>

<script>
document.querySelectorAll('.download-excel-anggota').forEach(btn => {
    btn.addEventListener('click', function () {
        const nokk = this.getAttribute('data-nokk');
        Swal.fire({
            title: 'Download Excel?',
            text: "Data anggota keluarga akan diunduh dalam format Excel.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, unduh',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.open("<?= base_url('admin/warga/export_anggota_keluarga/') ?>" + nokk, '_blank');
                Swal.fire({
                    icon: 'success',
                    title: 'Mengunduh...',
                    text: 'File Excel sedang disiapkan.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
});

document.querySelectorAll('.download-pdf-anggota').forEach(btn => {
    btn.addEventListener('click', function () {
        const nokk = this.getAttribute('data-nokk');
        Swal.fire({
            title: 'Download PDF?',
            text: "Data anggota keluarga akan diunduh dalam format PDF.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, unduh',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.open("<?= base_url('admin/warga/download_pdf_anggota/') ?>" + nokk, '_blank');
                Swal.fire({
                    icon: 'success',
                    title: 'Mengunduh...',
                    text: 'File PDF sedang disiapkan.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
});
</script>


<script>
$(document).on('click', '.btn-export-excel', function () {
    const url = $(this).data('url');

    Swal.fire({
        title: 'Download Excel?',
        text: "Data warga akan diunduh dalam format Excel.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, unduh',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(url, '_blank');
            Swal.fire({
                icon: 'success',
                title: 'Mengunduh...',
                text: 'File Excel sedang disiapkan.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});
</script>


<!--AUTO LOGOUT -->
<script>
let logoutTimer;

// Fungsi untuk meng-redirect ke logout
function startInactivityTimer() {
    clearTimeout(logoutTimer);
    logoutTimer = setTimeout(function () {
        window.location.href = "<?= base_url('admin/login/logout') ?>"; // sesuaikan dengan route logout kamu
    }, 5 * 60 * 1000); // 2 menit = 120000 ms
}

// Event: reset timer setiap ada interaksi
window.onload = startInactivityTimer;
document.onmousemove = startInactivityTimer;
document.onkeypress = startInactivityTimer;
document.onclick = startInactivityTimer;
document.onscroll = startInactivityTimer;
</script>






</body>

</html>