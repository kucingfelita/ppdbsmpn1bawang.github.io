<?php
require_once 'config/koneksi.php';
$pengumuman_settings = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman_settings['is_active'];
$jurnal_is_active = $pengumuman_settings['jurnal_is_active'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPDB Online SMP Negeri 1 Bawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/cropped-cropped-BAHAN-WEB-1.png" type="image/png">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/cropped-cropped-BAHAN-WEB-1.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                SMPN 1 Bawang
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar.php">Daftar Sekarang</a></li>
                    <?php if ($jurnal_is_active): ?>
                        <li class="nav-item"><a class="nav-link" href="jurnal.php">Jurnal Pendaftaran</a></li>
                    <?php endif; ?>
                    <?php if ($is_active): ?>
                        <li class="nav-item"><a class="nav-link" href="hasil_seleksi.php">Hasil Seleksi</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="cek_status.php">Cek Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login.php">Login Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ... rest of the file remains the same ... -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4">Penerimaan Peserta Didik Baru</h1>
            <p class="lead">SMP Negeri 1 Bawang - Tahun Ajaran 2025/2026</p>
            <a href="daftar.php" class="btn btn-light btn-lg mt-3">Daftar Sekarang!</a>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="text-center mb-5">
            <h2>Selamat Datang di PPDB Online</h2>
            <p class="lead">Ikuti langkah-langkah di bawah ini untuk mendaftar.</p>
        </div>
        <div class="row g-4">
            <!-- Tata Cara Pendaftaran -->
            <div class="col-md-6">
                <div class="custom-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tata Cara Pendaftaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item border-0">Akses halaman PPDB Online SMPN 1 Bawang.</li>
                            <li class="list-group-item border-0">Klik "Daftar Sekarang" pada halaman utama.</li>
                            <li class="list-group-item border-0">Isi formulir dengan data yang benar dan valid.</li>
                            <li class="list-group-item border-0">Pilih jalur pendaftaran dan lengkapi dokumen.</li>
                            <li class="list-group-item border-0">Simpan nomor pendaftaran Anda setelah submit.</li>
                            <li class="list-group-item border-0">Cek status pendaftaran di menu "Cek Status".</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Jadwal Penting -->
            <div class="col-md-6">
                <div class="custom-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Jadwal Penting</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Pendaftaran Online <span class="badge bg-primary rounded-pill">1 - 15 Juli 2025</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Verifikasi Berkas <span class="badge bg-primary rounded-pill">1 - 17 Juli 2025</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Seleksi & Perankingan <span class="badge bg-primary rounded-pill">18 - 19 Juli 2025</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Pengumuman Hasil <span class="badge bg-success rounded-pill">20 Juli 2025</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Daftar Ulang <span class="badge bg-primary rounded-pill">21 - 23 Juli 2025</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2025 - PPDB Online SMP Negeri 1 Bawang</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>