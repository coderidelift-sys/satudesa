<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Aduan Warga</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Aduan</a></li>
                <li class="breadcrumb-item active">Data Aduan Warga</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Aduan Warga</h5>

                        <!-- Menampilkan pesan sukses -->
                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
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
                                                <li>Tanggapi setiap data aduan yang masuk dan update statusnya.</li>
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    </li>
                                </ul>

                            </li>
                        </ul>


                        <!-- Table to Display Aduan Data -->
                        <div class="table-responsive">
                              <table id="dataAduanTable" class="table table-borderless table-striped nowrap" style="width: 100%">
                               <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>No Tracking</th>
                                        <th>NIK</th>
                                        <th>Isi Aduan</th>
                                        <th>Foto</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Jawaban</th>
                                        <th>Ditanggapi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                               
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->