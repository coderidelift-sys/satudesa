<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login Page</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?php echo base_url('')?>assets_admin/img/favicon.png" rel="icon">
    <link href="<?php echo base_url('')?>assets_admin/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo base_url('')?>assets_admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?php echo base_url('')?>assets_admin/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?php echo base_url('')?>assets_admin/css/style.css" rel="stylesheet">
    
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>

</head>

<body>

    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="#" class="logo d-flex align-items-center w-auto">
                                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="">
                                    <span class="d-none d-lg-block"><?php echo $aplikasi->nama_aplikasi; ?></span><br>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>

                                    <!-- Display error message if login fails -->
                                    <?php if ($this->session->flashdata('error')) : ?>
                                    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                                    <?php endif; ?>
                                    

                                    <form class="row g-3 needs-validation" action="<?= base_url('admin/login/login') ?>"
                                        method="post" novalidate>
                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="username" class="form-control"
                                                    id="yourUsername" required>
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                           <div class="input-group">
    <input type="password" name="password" class="form-control" id="yourPassword" required>
    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
        <i class="bi bi-eye-slash" id="eyeIcon"></i>
    </button>
</div>
<div class="invalid-feedback">Please enter your password!</div>

                                        </div>
                                        <div class="col-12">
                                            <label for="captcha" class="form-label">Masukkan CAPTCHA</label>
                                            <div class="input-group">
                                            <?php echo $captcha_image; ?> <br> 
                                                <input type="text" name="captcha" class="form-control" required>
                                            </div>
                                            <div class="invalid-feedback">Silakan masukkan kode CAPTCHA.</div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100 mb-1" type="submit">
                                                <i class="fas fa-sign-in-alt"></i> Login
                                            </button>
                                            <a href="<?php echo base_url('main') ?>" class="btn btn-secondary w-100">
                                                <i class="fas fa-home"></i> Back to Home
                                            </a>
                                        </div>

                                    </form>
                                    
                                </div>
                            </div>
                            
<div class="credits" style="font-size: 12px;">
   Terdaftar di PSE <a href="https://pse.komdigi.go.id/pse" target="_blank">Kementerian Komunikasi dan Digital</a>
</div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main><!-- End #main -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?php echo base_url('')?>assets_admin/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/chart.js/chart.umd.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/echarts/echarts.min.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/quill/quill.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?php echo base_url('')?>assets_admin/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="<?php echo base_url('')?>assets_admin/js/main.js"></script>

<script>
    // Jika ada pesan error yang mengandung timer, jalankan countdown
    var errorMessage = document.getElementById("error-message");
    if (errorMessage && errorMessage.innerText.includes("Silakan coba lagi dalam")) {
        var seconds = parseInt(errorMessage.innerText.match(/\d+/)[0]); // Ambil angka dari pesan

        function countdown() {
            if (seconds > 0) {
                errorMessage.innerText = "Terlalu banyak percobaan gagal. Silakan coba lagi dalam " + seconds + " detik.";
                seconds--;
                setTimeout(countdown, 1000);
            } else {
                location.reload(); // Refresh halaman saat timer selesai
            }
        }
        countdown();
    }
</script>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('yourPassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Ganti ikon
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>


<script>

  const firebaseConfig = {
  apiKey: "AIzaSyDZqRRV_2D1oMh6poCaHDU5J8n2XCnmMJw",
  authDomain: "pwanotif-9dea1.firebaseapp.com",
  projectId: "pwanotif-9dea1",
  storageBucket: "pwanotif-9dea1.firebasestorage.app",
  messagingSenderId: "1043147027293",
  appId: "1:1043147027293:web:74162206e485f7fe552eb4",
  measurementId: "G-C4J2CFC8MC"
};

  firebase.initializeApp(firebaseConfig);

  // 2. Setup messaging
  const messaging = firebase.messaging();

  // 3. Request permission & get token
  messaging
    .requestPermission()
    .then(function () {
      return messaging.getToken({ vapidKey: "YOUR_PUBLIC_VAPID_KEY" });
    })
    .then(function (token) {
      console.log("Push Token:", token);

      // Optional: kirim token ke server-mu
      // fetch('/save-token', {
      //   method: 'POST',
      //   body: JSON.stringify({ token }),
      //   headers: { 'Content-Type': 'application/json' }
      // });
    })
    .catch(function (err) {
      console.error("Permission denied or error", err);
    });

  // 4. Listener untuk menerima pesan saat halaman aktif
  messaging.onMessage(function (payload) {
    console.log("Message received. ", payload);
    new Notification(payload.notification.title, {
      body: payload.notification.body,
      icon: payload.notification.icon
    });
  });
</script>




</body>

</html>