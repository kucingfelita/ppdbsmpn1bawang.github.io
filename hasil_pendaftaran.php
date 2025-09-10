<?php
session_start();
if (!isset($_SESSION['sukses_daftar']) || !isset($_SESSION['no_pendaftaran'])) {
    header("Location: index.php");
    exit();
}

$no_pendaftaran = $_SESSION['no_pendaftaran'];

// Unset session variables to prevent re-access
unset($_SESSION['sukses_daftar']);
unset($_SESSION['no_pendaftaran']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Berhasil - PPDB Online</title>
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar.php">Daftar Sekarang</a></li>
                    <li class="nav-item"><a class="nav-link" href="jurnal.php">Jurnal Pendaftaran</a></li>
                    <li class="nav-item"><a class="nav-link" href="cek_status.php">Cek Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login.php">Login Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="custom-card">
                    <div class="card-body p-5">
                        <h1 class="text-success"><i class="bi bi-check-circle-fill"></i> Pendaftaran Berhasil!</h1>
                        <p class="lead mt-3">Terima kasih telah melakukan pendaftaran. Data Anda telah berhasil kami simpan.</p>
                        <hr>
                        <p>Mohon simpan dan catat Nomor Pendaftaran Anda di bawah ini. Nomor ini digunakan untuk mengecek status pengumuman kelulusan.</p>
                        <div class="alert alert-info fs-4 fw-bold">
                            <?php echo htmlspecialchars($no_pendaftaran); ?>
                        </div>
                        <a href="index.php" class="btn btn-main mt-3">Kembali ke Beranda</a>
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
