<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/koneksi.php';

// Fetch statistics
$total_pendaftar = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa")->fetch_assoc()['total'];
$jalur_akademik = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa WHERE jalur_pendaftaran = 'Akademik'")->fetch_assoc()['total'];
$jalur_non_akademik = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa WHERE jalur_pendaftaran = 'Non-Akademik'")->fetch_assoc()['total'];
$jalur_afirmasi = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa WHERE jalur_pendaftaran = 'Afirmasi'")->fetch_assoc()['total'];
$status_diterima = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa WHERE status_pendaftaran = 'Diterima'")->fetch_assoc()['total'];
$status_ditolak = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa WHERE status_pendaftaran = 'Ditolak'")->fetch_assoc()['total'];
$status_verifikasi = $koneksi->query("SELECT COUNT(*) AS total FROM calon_siswa WHERE status_pendaftaran = 'Menunggu Verifikasi'")->fetch_assoc()['total'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/cropped-cropped-BAHAN-WEB-1.png" type="image/png">
</head>
<body>

<header class="navbar sticky-top flex-md-nowrap p-0 admin-header">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="index.php">
        <img src="../assets/cropped-cropped-BAHAN-WEB-1.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
        PPDB Admin
    </a>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="logout.php">Sign out</a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="bi bi-house-door"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_pendaftar.php"><i class="bi bi-people"></i> Data Pendaftar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kuota.php"><i class="bi bi-pie-chart"></i> Kelola Kuota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pengumuman.php"><i class="bi bi-megaphone"></i> Pengumuman</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="fs-6">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_nama']); ?></strong></span>
                </div>
            </div>

            <h4>Statistik Pendaftar</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="custom-card text-bg-primary mb-3">
                        <div class="card-header">Total Pendaftar</div>
                        <div class="card-body">
                            <h5 class="card-title display-4"><?php echo $total_pendaftar; ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <h4>Rincian Jalur Pendaftaran</h4>
            <div class="row">
                <div class="col-md-4"><div class="custom-card text-bg-info mb-3"><div class="card-body"><h5 class="card-title">Akademik</h5><p class="card-text fs-4"><?php echo $jalur_akademik; ?> pendaftar</p></div></div></div>
                <div class="col-md-4"><div class="custom-card text-bg-info mb-3"><div class="card-body"><h5 class="card-title">Non-Akademik</h5><p class="card-text fs-4"><?php echo $jalur_non_akademik; ?> pendaftar</p></div></div></div>
                <div class="col-md-4"><div class="custom-card text-bg-info mb-3"><div class="card-body"><h5 class="card-title">Afirmasi</h5><p class="card-text fs-4"><?php echo $jalur_afirmasi; ?> pendaftar</p></div></div></div>
            </div>

            <h4>Status Pendaftaran</h4>
            <div class="row">
                <div class="col-md-4"><div class="custom-card text-bg-success mb-3"><div class="card-body"><h5 class="card-title">Diterima</h5><p class="card-text fs-4"><?php echo $status_diterima; ?> siswa</p></div></div></div>
                <div class="col-md-4"><div class="custom-card text-bg-danger mb-3"><div class="card-body"><h5 class="card-title">Ditolak</h5><p class="card-text fs-4"><?php echo $status_ditolak; ?> siswa</p></div></div></div>
                <div class="col-md-4"><div class="custom-card text-bg-warning mb-3"><div class="card-body"><h5 class="card-title">Menunggu Verifikasi</h5><p class="card-text fs-4"><?php echo $status_verifikasi; ?> siswa</p></div></div></div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
