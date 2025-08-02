<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '') ? '' : 'collapsed' ?>"
                href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <?php if (in_array($this->session->userdata('level'), ['admin', 'super_admin','Operator'])) : ?>
        <!-- Informasi -->
        <li class="nav-heading">INFORMASI DESA</li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'profil' || $this->uri->segment(2) == 'struktur' || $this->uri->segment(2) == 'wisata' || $this->uri->segment(2) == 'umkm') ? '' : 'collapsed' ?>"
                data-bs-target="#informasi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Informasi Desa</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="informasi-nav"
                class="nav-content collapse <?= ($this->uri->segment(2) == 'profil' || $this->uri->segment(2) == 'map' || $this->uri->segment(2) == 'struktur' || $this->uri->segment(2) == 'wisata' || $this->uri->segment(2) == 'umkm') ? 'show' : '' ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= base_url('admin/profil') ?>"
                        class="<?= ($this->uri->segment(2) == 'profil') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Profil Desa</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/map') ?>"
                        class="<?= ($this->uri->segment(2) == 'map') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Mapping</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/struktur') ?>"
                        class="<?= ($this->uri->segment(2) == 'struktur') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Struktur Organisasi</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/wisata') ?>"
                        class="<?= ($this->uri->segment(2) == 'wisata') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Wisata</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/umkm') ?>"
                        class="<?= ($this->uri->segment(2) == 'umkm') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>UMKM</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Informasi Nav -->

        <!-- Pelayanan -->
        <li class="nav-heading">POSTING INFORMASI</li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'potensidesa' || $this->uri->segment(2) == 'pengumuman' || $this->uri->segment(2) == 'kegiatan' || $this->uri->segment(2) == 'bansos' || $this->uri->segment(2) == 'faq' || $this->uri->segment(2) == 'kontak' || $this->uri->segment(2) == 'regulasi' || $this->uri->segment(2) == 'shopping'|| $this->uri->segment(2) == 'hukum') ? '' : 'collapsed' ?>"
                data-bs-target="#pelayanan-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Posting Informasi</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="pelayanan-nav"
                class="nav-content collapse <?= ($this->uri->segment(2) == 'potensidesa' || $this->uri->segment(2) == 'pengumuman' || $this->uri->segment(2) == 'kegiatan' || $this->uri->segment(2) == 'bansos' || $this->uri->segment(2) == 'faq' || $this->uri->segment(2) == 'kontak' || $this->uri->segment(2) == 'regulasi' || $this->uri->segment(2) == 'shopping' || $this->uri->segment(2) == 'hukum')  ? 'show' : '' ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= base_url('admin/potensidesa') ?>"
                        class="<?= ($this->uri->segment(2) == 'potensidesa') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Potensi Desa</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/kegiatan') ?>"
                        class="<?= ($this->uri->segment(2) == 'kegiatan') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Kegiatan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pengumuman') ?>"
                        class="<?= ($this->uri->segment(2) == 'pengumuman') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Pengumuman</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/bansos') ?>"
                        class="<?= ($this->uri->segment(2) == 'bansos') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Bansos</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/faq') ?>"
                        class="<?= ($this->uri->segment(2) == 'faq') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>FAQ (Tanya Jawab)</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/kontak') ?>"
                        class="<?= ($this->uri->segment(2) == 'kontak') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Kontak Staff</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/regulasi') ?>"
                        class="<?= ($this->uri->segment(2) == 'regulasi') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Regulasi Pelayanan</span>
                    </a>
                </li>
                 <li>
                    <a href="<?= base_url('admin/hukum') ?>"
                        class="<?= ($this->uri->segment(2) == 'hukum') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Produk Hukum</span>
                    </a>
                </li>
                 <li>
                    <a href="<?= base_url('admin/shopping') ?>"
                        class="<?= ($this->uri->segment(2) == 'shopping') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Online Shop</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Pelayanan Nav -->
        <?php endif; ?>

        <?php if (in_array($this->session->userdata('level'), ['admin', 'super_admin'])) : ?>
        <!-- Manajemen Warga -->
        <li class="nav-heading">MANAJEMEN WARGA</li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'warga' || $this->uri->segment(2) == 'aduan' || $this->uri->segment(2) == 'semuawarga' || $this->uri->segment(2) == 'dusun') ? '' : 'collapsed' ?>"
                data-bs-target="#manajemen-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Manajemen Data</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="manajemen-nav"
                class="nav-content collapse <?= ($this->uri->segment(2) == 'warga' || $this->uri->segment(2) == 'semuawarga' || $this->uri->segment(2) == 'aduan' || $this->uri->segment(2) == 'dusun') ? 'show' : '' ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= base_url('admin/warga') ?>"
                        class="<?= ($this->uri->segment(2) == 'warga') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Data Warga [KK]</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/semuawarga') ?>"
                        class="<?= ($this->uri->segment(2) == 'semuawarga') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Cari Warga</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/dusun') ?>"
                        class="<?= ($this->uri->segment(2) == 'dusun') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Data Dusun</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/aduan') ?>"
                        class="<?= ($this->uri->segment(2) == 'aduan') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Data Aduan</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Manajemen Warga Nav -->
        <?php endif; ?>
        
         <?php if (in_array($this->session->userdata('level'), ['Pelayanan', 'admin', 'super_admin'])) : ?>
        <!-- Manajemen Surat -->
        <li class="nav-heading">MANAJEMEN SURAT</li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'surat') ? '' : 'collapsed' ?>"
                data-bs-target="#surat-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Manajemen Surat</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="surat-nav"
                class="nav-content collapse <?= ($this->uri->segment(2) == 'buatsurat/list' || $this->uri->segment(2) == 'buatsurat/konfigurasi' || $this->uri->segment(2) == 'buatsurat' || $this->uri->segment(2) == 'bape' || $this->uri->segment(2) == 'surat' || $this->uri->segment(2) == 'sukem' || $this->uri->segment(2) == 'surlah' || $this->uri->segment(2) == 'domisili' || $this->uri->segment(2) == 'suratstatus' ) ? 'show' : '' ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= base_url('admin/surat') ?>"
                        class="<?= ($this->uri->segment(2) == 'surat') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>SKTM & SKU</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/surlah') ?>"
                        class="<?= ($this->uri->segment(2) == 'surlah') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Keterangan Lahir</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/domisili') ?>"
                        class="<?= ($this->uri->segment(2) == 'domisili') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Keterangan Domisili</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/suratstatus') ?>"
                        class="<?= ($this->uri->segment(2) == 'suratstatus') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Keterangan Status</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/sukem') ?>"
                        class="<?= ($this->uri->segment(2) == 'sukem') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Keterangan Kematian</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/bape') ?>"
                        class="<?= ($this->uri->segment(2) == 'bape') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>BA Penguburan</span>
                    </a>
                </li>
				<?php if (in_array($this->session->userdata('level'), ['super_admin'])) : ?>
					<li>
						<a href="<?= base_url('admin/buatsurat/konfigurasi') ?>"
							class="<?= ($this->uri->segment(3) == 'konfigurasi') ? 'active' : '' ?>">
							<i class="bi bi-circle"></i><span>Konfigurasi Template Surat</span>
						</a>
					</li>
				<?php endif; ?>
                <li>
                    <a href="<?= base_url('admin/buatsurat/list') ?>"
                        class="<?= ($this->uri->segment(3) == 'list') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Daftar Surat</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/buatsurat') ?>"
                        class="<?= ($this->uri->segment(2) == 'buatsurat' && !in_array($this->uri->segment(3), ['konfigurasi', 'list'])) ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Buat Surat</span>
                    </a>
                </li>
            </ul>

        </li><!-- End Manajemen Surat Nav -->
        <?php endif; ?>
        
        <?php if (in_array($this->session->userdata('level'), ['super_admin'])) : ?>
        <!-- Sistem -->
        <li class="nav-heading">SISTEM</li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'pengaturan' || $this->uri->segment(2) == 'logdata' || $this->uri->segment(2) == 'aplikasi' || $this->uri->segment(2) == 'pembaruan' || $this->uri->segment(2) == 'about' || $this->uri->segment(2) == 'banner') ? '' : 'collapsed' ?>"
                data-bs-target="#sistem-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>Setting Sistem</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="sistem-nav"
                class="nav-content collapse <?= ($this->uri->segment(2) == 'pengaturan' || $this->uri->segment(2) == 'logdata' || $this->uri->segment(2) == 'aplikasi' || $this->uri->segment(2) == 'pembaruan' || $this->uri->segment(2) == 'about' || $this->uri->segment(2) == 'banner') ? 'show' : '' ?>"
                data-bs-parent="#sidebar-nav">

                <!-- Setting Banner -->
                <li>
                    <a href="<?= base_url('admin/banner') ?>"
                        class="<?= ($this->uri->segment(2) == 'banner') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Setting Banner</span>
                    </a>
                </li>

                <!-- Status Sistem -->
                <li>
                    <a href="<?= base_url('admin/pengaturan') ?>"
                        class="<?= ($this->uri->segment(2) == 'pengaturan') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Status Sistem</span>
                    </a>
                </li>
                <!-- Status Sistem -->
                <li>
                    <a href="<?= base_url('admin/logdata') ?>"
                        class="<?= ($this->uri->segment(2) == 'logdata') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Log Activity</span>
                    </a>
                </li>


                <!-- Setting Aplikasi -->
                <li>
                    <a href="<?= base_url('admin/aplikasi') ?>"
                        class="<?= ($this->uri->segment(2) == 'aplikasi') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Setting Aplikasi</span>
                    </a>
                </li>

                <!-- Pembaruan -->
                <li>
                    <a href="<?= base_url('admin/pembaruan') ?>"
                        class="<?= ($this->uri->segment(2) == 'pembaruan') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Pembaruan</span>
                    </a>
                </li>

                <!-- Developer -->
                <li>
                    <a href="<?= base_url('admin/about') ?>"
                        class="<?= ($this->uri->segment(2) == 'about') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Developer
                        </span>
                    </a>
                </li>
            </ul>
        </li><!-- End Sistem Nav -->
        <?php endif; ?>


        <?php if (in_array($this->session->userdata('level'), ['Pelayanan','Operator','admin', 'super_admin'])) : ?>
        <!-- User -->
        <li class="nav-heading">USER</li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'login' || $this->uri->segment(2) == 'user') ? '' : 'collapsed' ?>"
                data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-circle"></i><span>Akun User</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-nav"
                class="nav-content collapse <?= ($this->uri->segment(2) == 'login' || $this->uri->segment(2) == 'user') ? 'show' : '' ?>"
                data-bs-parent="#sidebar-nav">
                <!-- Menu Profil User -->
                <li>
                    <a href="<?= base_url('admin/user') ?>"
                        class="<?= ($this->uri->segment(2) == 'user') ? 'active' : '' ?>">
                        <i class="bi bi-person"></i><span>Data User</span>
                    </a>
                </li>
                <!-- Menu Logout -->
                <li>
                    <a href="<?= base_url('admin/login/logout') ?>"
                        class="<?= ($this->uri->segment(2) == 'login' && $this->uri->segment(3) == 'logout') ? 'active' : '' ?>">
                        <i class="bi bi-box-arrow-left"></i><span>Logout</span>
                    </a>
                </li>
            </ul>
        </li><!-- End User Nav -->
        <?php endif; ?>

    </ul>
</aside><!-- End Sidebar -->
