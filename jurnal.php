<?php 
require_once 'config/koneksi.php';

$pengumuman_settings = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman_settings['is_active'];
$jurnal_is_active = $pengumuman_settings['jurnal_is_active'];

// Fetch quota data
$kuota_data = [];
if ($jurnal_is_active) {
    $result = $koneksi->query("SELECT * FROM kuota");
    while ($row = $result->fetch_assoc()) {
        $kuota_data[$row['jalur']] = $row['jumlah'];
    }

    // Fetch total pendaftar per jalur
    $total_pendaftar = [];
    $result_total = $koneksi->query("SELECT jalur_pendaftaran, COUNT(*) as total FROM calon_siswa WHERE status_pendaftaran = 'Terverifikasi' GROUP BY jalur_pendaftaran");
    while ($row = $result_total->fetch_assoc()) {
        $total_pendaftar[$row['jalur_pendaftaran']] = $row['total'];
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jurnal Pendaftaran - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar.php">Daftar Sekarang</a></li>
                    <?php if ($jurnal_is_active): ?>
                    <li class="nav-item"><a class="nav-link active" href="jurnal.php">Jurnal Pendaftaran</a></li>
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

    <!-- Main Content -->
    <div class="container my-5">
        <div class="text-center mb-5">
            <h2>Jurnal Pendaftaran</h2>
        </div>

        <?php if ($jurnal_is_active): ?>
            <p class="text-center lead">Pilih jalur pendaftaran untuk melihat peringkat sementara pendaftar yang telah terverifikasi.</p>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="custom-card text-center h-100">
                        <div class="card-body p-4">
                            <i class="bi bi-award fs-1 text-main"></i>
                            <h5 class="card-title mt-3">Prestasi Akademik</h5>
                            <p>Kuota: <span class="fw-bold"><?php echo $kuota_data['Akademik'] ?? 'N/A'; ?></span> | Pendaftar Terverifikasi: <span class="fw-bold"><?php echo $total_pendaftar['Akademik'] ?? 0; ?></span></p>
                            <a href="jurnal_akademik.php" class="btn btn-main">Lihat Jurnal</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-card text-center h-100">
                        <div class="card-body p-4">
                            <i class="bi bi-trophy fs-1 text-main"></i>
                            <h5 class="card-title mt-3">Prestasi Non-Akademik</h5>
                            <p>Kuota: <span class="fw-bold"><?php echo $kuota_data['Non-Akademik'] ?? 'N/A'; ?></span> | Pendaftar Terverifikasi: <span class="fw-bold"><?php echo $total_pendaftar['Non-Akademik'] ?? 0; ?></span></p>
                            <a href="jurnal_non_akademik.php" class="btn btn-main">Lihat Jurnal</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-card text-center h-100">
                        <div class="card-body p-4">
                            <i class="bi bi-heart-pulse fs-1 text-main"></i>
                            <h5 class="card-title mt-3">Afirmasi</h5>
                            <p>Kuota: <span class="fw-bold"><?php echo $kuota_data['Afirmasi'] ?? 'N/A'; ?></span> | Pendaftar Terverifikasi: <span class="fw-bold"><?php echo $total_pendaftar['Afirmasi'] ?? 0; ?></span></p>
                            <a href="jurnal_afirmasi.php" class="btn btn-main">Lihat Jurnal</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-info text-center">
                        <h4 class="alert-heading">Informasi</h4>
                        <p>Jurnal pendaftaran akan tersedia dan ditampilkan selama periode seleksi dan perankingan.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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