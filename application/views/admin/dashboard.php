<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Pengajuan Surat</h5>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-files" style="font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Total Surat: <span class="text-primary"><?php echo $total_surat; ?>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-hourglass-split" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-warning">Pending:</span>
                                        <h6><?php echo $pending_s; ?>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-gear-wide-connected" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-info">Diproses:</span>
                                        <h6><?php echo $diproses_surat; ?>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-check-circle" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-success">Selesai:</span>
                                        <h6><?php echo $selesai_surat; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End Sales Card -->



                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Data Aduan</h5>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-chat-dots" style="font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Total Aduan: <span class="text-danger"></span><?= $total; ?></h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-hourglass-split" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-warning">Pending:</span>
                                        <h6><?= $pending; ?>

                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-gear-wide-connected" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-info">Diproses:</span>
                                        <h6><?= $diproses; ?>

                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-check-circle" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-success">Selesai:</span>
                                        <h6><?= $selesai; ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End Revenue Card -->


                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <!-- Jumlah Warga -->
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Warga</h5>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-people" style="font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><span class="text-primary"><?= $total_warga ?></span> Jiwa</h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-gender-male" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-success">Laki-laki:</span> <?= $total_laki ?> Jiwa
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-gender-female" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="fw-bold text-danger">Perempuan:</span> <?= $total_perempuan ?> Jiwa
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah KK -->
                            <div class="card-body">
                                <h6 class="card-title">Jumlah KK</h6>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-purple text-white"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-house-door" style="font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><span class="text-dark"><?= $total_kk ?></span> KK</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Customers Card -->

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Grafik Warga Dusun</h5>
                                <canvas id="grafikDataWargaChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Data Kategori Usia</h5>
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kelompok Usia</th>
                                            <th>Jumlah Warga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Balita (1-3 tahun)</td>
                                            <td><?= $grafik_usia_warga['balita']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Anak (4-12 tahun)</td>
                                            <td><?= $grafik_usia_warga['anak']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Remaja (13-17 tahun)</td>
                                            <td><?= $grafik_usia_warga['remaja']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Pemuda (18-35 tahun)</td>
                                            <td><?= $grafik_usia_warga['pemuda']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Dewasa (36-50 tahun)</td>
                                            <td><?= $grafik_usia_warga['dewasa']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Lansia (51-100 tahun)</td>
                                            <td><?= $grafik_usia_warga['lansia']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Data Pengajuan Surat</h5>
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Jenis Surat</th>
                                            <th>Pending</th>
                                            <th>Diproses</th>
                                            <th>Selesai</th>
                                            <th>Total Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_surat as $row) : ?>
                                        <tr>
                                            <td><?= $row['jenis_surat']; ?></td>
                                            <td><?= $row['pending']; ?></td>
                                            <td><?= $row['diproses']; ?></td>
                                            <td><?= $row['selesai']; ?></td>
                                            <td><?= $row['total']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Grafik Pengajuan Surat</h5>
                                <div id="grafikSurat"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Grafik Metode Pengajuan Surat</h5>
                                <canvas id="grafikSuratMetode"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Grafik Potensi Berdasarkan Alamat</h5>
                                <div id="grafikPotensi"></div> <!-- Tempat untuk grafik -->
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Detail Grafik Potensi</h5>
                                <div id="grafikDetailPotensi"></div> <!-- Tempat untuk detail grafik -->
                            </div>
                        </div>
                    </div>
                    <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        var base_url = "<?= base_url() ?>"; // Ambil base_url dari CI3

                        // Data untuk grafik utama
                        var options = {
                            series: [{
                                name: 'Total Penghasilan',
                                data: <?= $total_nominal ?> // Data total nominal (numerik)
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                events: {
                                    click: function(event, chartContext, config) {
                                        // Ambil alamat yang diklik
                                        var alamat = config.config.xaxis.categories[config
                                            .dataPointIndex];
                                        loadDetailChart(
                                            alamat); // Panggil fungsi untuk memuat detail grafik
                                    }
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '55%',
                                    endingShape: 'rounded'
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            xaxis: {
                                categories: <?= $alamat ?> // Data alamat
                            },
                            colors: ['#4154f1'],
                            tooltip: {
                                enabled: true,
                                y: {
                                    formatter: function(value, {
                                        seriesIndex,
                                        dataPointIndex,
                                        w
                                    }) {
                                        // Format tooltip dengan total nominal dalam Rupiah
                                        var formattedValue = <?= $total_nominal_formatted ?>[
                                            dataPointIndex];
                                        return `Total: ${formattedValue}`;
                                    }
                                }
                            }
                        };

                        // Render grafik utama
                        var chart = new ApexCharts(document.querySelector("#grafikPotensi"), options);
                        chart.render();

                        function loadDetailChart(alamat) {
                            // Encode alamat sebelum mengirimkannya melalui URL
                            var encodedAlamat = encodeURIComponent(alamat);
                            fetch(`${base_url}/admin/dashboard/get_detail_chart/${encodedAlamat}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // Hapus grafik detail sebelumnya (jika ada)
                                    if (window.detailChart) {
                                        window.detailChart.destroy();
                                    }

                                    // Render detail chart dengan data yang diterima
                                    var detailOptions = {
                                        series: [{
                                            name: 'Detail Penghasilan',
                                            data: data
                                                .detail_penghasilan // Data penghasilan detail
                                        }],
                                        chart: {
                                            type: 'bar',
                                            height: 350
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,
                                                columnWidth: '55%',
                                                endingShape: 'rounded'
                                            },
                                        },
                                        dataLabels: {
                                            enabled: false
                                        },
                                        xaxis: {
                                            categories: data
                                                .detail_categories // Kategori detail (bidang)
                                        },
                                        colors: ['#4154f1'],
                                        tooltip: {
                                            enabled: true,
                                            y: {
                                                formatter: function(value, {
                                                    seriesIndex,
                                                    dataPointIndex,
                                                    w
                                                }) {
                                                    // Tampilkan detail di tooltip
                                                    return `
                                Bidang: ${data.detail_categories[dataPointIndex]}, 
                                Objek: ${data.detail_objek[dataPointIndex]}, 
                                Penghasilan: ${data.detail_penghasilan_tahunan[dataPointIndex]} ${data.detail_satuan[dataPointIndex]}, 
                                Total: Rp ${data.detail_penghasilan[dataPointIndex].toLocaleString()}
                            `;
                                                }
                                            }
                                        }
                                    };

                                    // Render grafik detail
                                    window.detailChart = new ApexCharts(document.querySelector(
                                        "#grafikDetailPotensi"), detailOptions);
                                    window.detailChart.render();
                                })
                                .catch(error => {
                                    console.error('Error fetching detail chart data:', error);
                                });
                        }
                    });
                    </script>
                    <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        var options = {
                            series: [{
                                    name: 'Pending',
                                    data: <?= json_encode(array_column($grafik_surat, 'pending')) ?>
                                },
                                {
                                    name: 'Diproses',
                                    data: <?= json_encode(array_column($grafik_surat, 'diproses')) ?>
                                },
                                {
                                    name: 'Selesai',
                                    data: <?= json_encode(array_column($grafik_surat, 'selesai')) ?>
                                },
                                {
                                    name: 'Total',
                                    data: <?= json_encode(array_column($grafik_surat, 'total')) ?>
                                }
                            ],
                            chart: {
                                type: 'bar',
                                height: 350
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '55%',
                                    endingShape: 'rounded'
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            xaxis: {
                                categories: <?= json_encode(array_column($grafik_surat, 'jenis_surat')) ?>
                            },
                            colors: ['#FF5733', '#FFC300', '#28A745', '#4154f1']
                        };

                        var chart = new ApexCharts(document.querySelector("#grafikSurat"), options);
                        chart.render();
                    });
                    </script>
                    <!-- End Reports -->

                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var ctx = document.getElementById("grafikSuratMetode").getContext("2d");

                        var data = {
                            labels: ["Offline", "Online"], // Label kategori
                            datasets: [{
                                data: [
                                    <?php 
                $offline = 0; $online = 0;
                foreach ($grafik_metode as $g) {
                    if ($g['metode'] == 'Offline') { $offline = $g['total']; }
                    if ($g['metode'] == 'Online') { $online = $g['total']; }
                }
                echo "$offline, $online"; 
                ?>
                                ],
                                backgroundColor: ["#f39c12", "#3498db"], // Warna batang grafik
                                borderColor: ["#e67e22", "#2980b9"], // Warna border batang grafik
                                borderWidth: 1
                            }]
                        };

                        var options = {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }, // Menghilangkan legenda warna
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        };

                        new Chart(ctx, {
                            type: "bar", // Bisa diganti "pie" atau "doughnut"
                            data: data,
                            options: options
                        });
                    });
                    </script>
                    <!-- End Grafik Metode Pengajuan -->

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var ctx = document.getElementById('grafikDataWargaChart').getContext('2d');
                        var dataGrafik = <?= json_encode($grafik_data_warga); ?>;

                        var labels = dataGrafik.map(item => item.alamat);
                        var totalKK = dataGrafik.map(item => parseInt(item.total_kk));
                        var totalWarga = dataGrafik.map(item => parseInt(item.total_warga));
                        var totalLaki = dataGrafik.map(item => parseInt(item.total_laki));
                        var totalPerempuan = dataGrafik.map(item => parseInt(item.total_perempuan));

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Total KK',
                                        data: totalKK,
                                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                    },
                                    {
                                        label: 'Total Warga',
                                        data: totalWarga,
                                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                                    },
                                    {
                                        label: 'Laki-laki',
                                        data: totalLaki,
                                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                                    },
                                    {
                                        label: 'Perempuan',
                                        data: totalPerempuan,
                                        backgroundColor: 'rgba(255, 159, 64, 0.6)'
                                    }
                                ]
                            }
                        });
                    });
                    </script>

                </div>
            </div><!-- End Left side columns -->

            <!-- Website Traffic -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>

                        <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                        <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                        <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                    </ul>
                </div>
            </div><!-- End Right side columns -->

        </div>
    </section>

</main><!-- End #main -->