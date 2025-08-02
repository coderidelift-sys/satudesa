# SatuDesa - Sistem Informasi Desa Terpadu

## Deskripsi Aplikasi

SatuDesa adalah sistem informasi desa terpadu yang dibangun menggunakan framework CodeIgniter 3. Aplikasi ini dirancang untuk memudahkan pengelolaan data desa dan memberikan layanan digital kepada masyarakat desa.

### Fitur Utama

#### 🏠 **Portal Publik**
- **Dashboard Utama**: Menampilkan statistik warga (total laki-laki, perempuan, KK)
- **Banner Carousel**: Slider informasi dan pengumuman desa
- **Profil Desa**: Informasi lengkap tentang desa
- **Visi & Misi**: Tampilan visi dan misi desa
- **Struktur Organisasi**: Bagan struktur pemerintahan desa
- **Peta Wilayah**: Visualisasi peta desa dan wilayah
- **Wisata**: Informasi tempat wisata di desa
- **UMKM**: Direktori usaha mikro kecil menengah
- **Kegiatan**: Informasi kegiatan dan event desa
- **Bantuan Sosial (Bansos)**: Informasi program bantuan sosial
- **Aduan**: Sistem pengaduan masyarakat
- **Kontak**: Informasi kontak desa
- **Shopping**: Marketplace produk lokal

#### 🔐 **Panel Admin**
- **Dashboard Admin**: Statistik dan grafik data desa
- **Manajemen Data Dusun**: CRUD data dusun/wilayah
- **Manajemen UMKM**: Pengelolaan data UMKM
- **Manajemen Aduan**: Penanganan aduan masyarakat
- **Manajemen Kegiatan**: CRUD kegiatan desa
- **Manajemen Kontak**: Pengaturan informasi kontak
- **Manajemen Banner**: Upload dan pengaturan banner
- **Manajemen Maps**: Konfigurasi peta desa
- **Log Data**: Monitoring aktivitas sistem
- **Sistem Surat**: Template dan pengelolaan surat-menyurat

#### 📊 **Fitur Analitik**
- **Grafik Usia Warga**: Visualisasi demografi berdasarkan usia
- **Grafik Data Warga**: Statistik kependudukan
- **Grafik Surat**: Analisis penggunaan layanan surat
- **Visitor Counter**: Pencatat pengunjung website

#### 🔒 **Keamanan**
- **Sistem Login**: Autentikasi dengan CAPTCHA
- **Session Management**: Pengelolaan sesi pengguna
- **Login Attempt Limiter**: Pembatasan percobaan login
- **Activity Logging**: Pencatatan aktivitas pengguna

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+ dengan CodeIgniter 3
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Libraries**: 
  - TCPDF (Generate PDF)
  - CAPTCHA Helper
  - Form Validation
  - Session Management
- **Icons**: Font Awesome
- **Timezone**: Asia/Jakarta

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Apache/Nginx Web Server
- Composer (opsional)
- Extension PHP: mysqli, gd, mbstring, zip

## Instalasi dari Git

### 1. Clone Repository

```bash
git clone https://github.com/username/satudesa.git
cd satudesa
```

### 2. Konfigurasi Database

1. Buat database baru di MySQL:
```sql
CREATE DATABASE u716088511_dbdesatrial;
```

2. Import database dari file SQL:
```bash
mysql -u root -p u716088511_dbdesatrial < new.sql
```

3. Edit konfigurasi database di `application/config/database.php`:
```php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'root',        // Sesuaikan dengan username MySQL Anda
    'password' => '',            // Sesuaikan dengan password MySQL Anda
    'database' => 'u716088511_dbdesatrial',
    'dbdriver' => 'mysqli',
    // ... konfigurasi lainnya
);
```

### 3. Konfigurasi Web Server

#### Apache
1. Pastikan mod_rewrite aktif
2. Buat virtual host atau letakkan di document root
3. Pastikan file `.htaccess` ada di root project

#### Nginx
Tambahkan konfigurasi berikut:
```nginx
server {
    listen 80;
    server_name satudesa.local;
    root /path/to/satudesa;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4. Set Permissions

```bash
chmod -R 755 application/
chmod -R 777 uploads/
chmod -R 777 assets/captcha/
```

### 5. Konfigurasi Base URL

Edit `application/config/config.php`:
```php
$config['base_url'] = 'http://localhost/satudesa/'; // Sesuaikan dengan URL Anda
```

### 6. Akses Aplikasi

- **Frontend**: `http://localhost/satudesa/`
- **Admin Panel**: `http://localhost/satudesa/admin/login`

## Demo Akun

### Akun Administrator

```
Username: admin
Password: admin123
Level: Administrator
```

**Hak Akses:**
- Akses penuh ke semua fitur admin
- Manajemen data master
- Konfigurasi sistem
- Monitoring dan laporan

### Akun Operator

```
Username: operator
Password: operator123
Level: Operator
```

**Hak Akses:**
- Input dan edit data
- Manajemen konten
- Penanganan aduan
- Tidak dapat mengubah konfigurasi sistem

### Akun Staff

```
Username: staff
Password: staff123
Level: Staff
```

**Hak Akses:**
- View data
- Input data terbatas
- Tidak dapat menghapus data penting

## Struktur Project

```
satudesa/
├── application/
│   ├── controllers/
│   │   ├── admin/          # Controller admin panel
│   │   ├── Aduan.php       # Controller aduan publik
│   │   ├── Main.php        # Controller homepage
│   │   └── ...
│   ├── models/
│   │   ├── M_all.php       # Model utama
│   │   ├── M_auth.php      # Model autentikasi
│   │   └── ...
│   ├── views/
│   │   ├── admin/          # Views admin panel
│   │   ├── layouts/        # Template layouts
│   │   └── ...
│   └── config/
├── assets_admin/           # Asset admin (CSS, JS, images)
├── uploads/               # Folder upload files
├── system/               # CodeIgniter core files
├── index.php            # Entry point aplikasi
└── new.sql             # Database schema
```

## Flow Aplikasi

### 1. **Alur Pengguna Publik**
```
Homepage → Menu Navigasi → Detail Informasi
   ↓
Layanan (Aduan, Surat) → Form Input → Konfirmasi
```

### 2. **Alur Admin**
```
Login → Dashboard → Manajemen Data → CRUD Operations
   ↓
Monitoring → Reports → Logout
```

### 3. **Alur Sistem Surat**
```
Template Surat → Field Definition → User Input → Generate PDF
```

## Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Aplikasi ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

## Support

Untuk pertanyaan dan dukungan teknis, silakan hubungi:
- Email: admin@satudesa.com
- GitHub Issues: [Link ke repository issues]

## Changelog

### v1.0.0
- Rilis awal aplikasi
- Fitur dasar manajemen desa
- Sistem autentikasi dan otorisasi
- Template surat dinamis

---

**Dikembangkan dengan ❤️ untuk kemajuan desa digital Indonesia**