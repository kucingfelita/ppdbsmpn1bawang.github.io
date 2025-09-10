<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/koneksi.php';

$id_siswa = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = '';

if ($id_siswa == 0) {
    header("Location: data_pendaftar.php");
    exit();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status_pendaftaran'];
    // Admin can only set these three statuses
    if (in_array($new_status, ['Menunggu Verifikasi', 'Terverifikasi', 'Tidak Terverifikasi'])) {
        $stmt = $koneksi->prepare("UPDATE calon_siswa SET status_pendaftaran = ? WHERE id_siswa = ?");
        $stmt->bind_param("si", $new_status, $id_siswa);
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Status berhasil diperbarui.</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal memperbarui status.</div>';
        }
        $stmt->close();
    } else {
        $message = '<div class="alert alert-warning">Status tidak valid.</div>';
    }
}

// Fetch student data
$stmt = $koneksi->prepare("SELECT * FROM calon_siswa WHERE id_siswa = ?");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_assoc();
$stmt->close();

if (!$siswa) {
    die("Siswa tidak ditemukan.");
}

function getStatusBadge($status) {
    $badge_map = [
        'Menunggu Verifikasi' => 'bg-warning',
        'Terverifikasi' => 'bg-primary',
        'Tidak Terverifikasi' => 'bg-secondary',
        'Lulus' => 'bg-success',
        'Tidak Lulus' => 'bg-danger',
    ];
    $badge_class = $badge_map[$status] ?? 'bg-dark';
    return "<span class='badge $badge_class'>" . htmlspecialchars($status) . "</span>";
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pendaftar - PPDB Online</title>
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
    <div class="navbar-nav"><div class="nav-item text-nowrap"><a class="nav-link px-3" href="logout.php">Sign out</a></div></div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="data_pendaftar.php"><i class="bi bi-people"></i> Data Pendaftar</a></li>
                    <li class="nav-item"><a class="nav-link" href="kuota.php"><i class="bi bi-pie-chart"></i> Kelola Kuota</a></li>
                    <li class="nav-item"><a class="nav-link" href="pengumuman.php"><i class="bi bi-megaphone"></i> Pengumuman</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Detail Calon Siswa</h1>
                <a href="data_pendaftar.php" class="btn btn-outline-secondary">Kembali</a>
            </div>

            <?php echo $message; ?>

            <div class="row">
                <!-- Left Column: Student Data -->
                <div class="col-md-8">
                    <div class="custom-card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Data Siswa
                            <?php echo getStatusBadge($siswa['status_pendaftaran']); ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>No. Pendaftaran:</strong><br> <?php echo htmlspecialchars($siswa['no_pendaftaran']); ?></li>
                                        <li class="list-group-item"><strong>Nama Lengkap:</strong><br> <?php echo htmlspecialchars($siswa['nama_lengkap']); ?></li>
                                        <li class="list-group-item"><strong>NISN:</strong><br> <?php echo htmlspecialchars($siswa['nisn']); ?></li>
                                        <li class="list-group-item"><strong>TTL:</strong><br> <?php echo htmlspecialchars($siswa['tempat_lahir'] . ', ' . date("d-m-Y", strtotime($siswa['tanggal_lahir']))); ?></li>
                                        <li class="list-group-item"><strong>Jenis Kelamin:</strong><br> <?php echo htmlspecialchars($siswa['jenis_kelamin']); ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Sekolah Asal:</strong><br> <?php echo htmlspecialchars($siswa['sekolah_asal']); ?></li>
                                        <li class="list-group-item"><strong>Alamat:</strong><br> <?php echo htmlspecialchars($siswa['alamat']); ?></li>
                                        <li class="list-group-item"><strong>Tanggal Daftar:</strong><br> <?php echo htmlspecialchars($siswa['tanggal_daftar']); ?></li>
                                        <li class="list-group-item"><strong>Jalur Pendaftaran:</strong><br> <span class="fw-bold"><?php echo htmlspecialchars($siswa['jalur_pendaftaran']); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="custom-card">
                        <div class="card-header">Data Pendukung Jalur</div>
                        <div class="card-body">
                            <?php if ($siswa['jalur_pendaftaran'] == 'Akademik' || $siswa['jalur_pendaftaran'] == 'Non-Akademik'): ?>
                                <p><strong>Nilai Rata-rata:</strong> <?php echo number_format($siswa['nilai_rata_rata'], 2); ?></p>
                            <?php endif; ?>
                            <?php if ($siswa['jalur_pendaftaran'] == 'Non-Akademik'): ?>
                                <p><strong>Piagam:</strong> <?php echo htmlspecialchars($siswa['piagam_level']); ?></p>
                            <?php endif; ?>
                             <?php if ($siswa['jalur_pendaftaran'] == 'Afirmasi'): ?>
                                <p><strong>Jarak Rumah:</strong> <?php echo htmlspecialchars($siswa['jarak_rumah']); ?> meter</p>
                                <p><strong>Penyandang Disabilitas:</strong> <?php echo $siswa['is_disabilitas'] ? 'Ya' : 'Tidak'; ?></p>
                            <?php endif; ?>
                            <p><strong>Skor Akhir (untuk perankingan):</strong> <?php echo number_format($siswa['skor_akhir'], 2); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Documents & Status Update -->
                <div class="col-md-4">
                    <div class="custom-card mb-3">
                        <div class="card-header">Dokumen Siswa</div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">KK: <a href="../uploads/<?php echo htmlspecialchars($siswa['file_kk']); ?>" target="_blank" class="btn btn-sm btn-outline-dark">Lihat</a></li>
                                <li class="mb-2">Akta: <a href="../uploads/<?php echo htmlspecialchars($siswa['file_akte']); ?>" target="_blank" class="btn btn-sm btn-outline-dark">Lihat</a></li>
                                <li class="mb-2">Surat Lulus: <a href="../uploads/<?php echo htmlspecialchars($siswa['file_surat_kelulusan']); ?>" target="_blank" class="btn btn-sm btn-outline-dark">Lihat</a></li>
                                <?php if ($siswa['file_prestasi']): ?>
                                <li class="mb-2">Prestasi: <a href="../uploads/<?php echo htmlspecialchars($siswa['file_prestasi']); ?>" target="_blank" class="btn btn-sm btn-outline-dark">Lihat</a></li>
                                <?php endif; ?>
                                <?php if ($siswa['file_kip']): ?>
                                <li class="mb-2">KIP/KKS: <a href="../uploads/<?php echo htmlspecialchars($siswa['file_kip']); ?>" target="_blank" class="btn btn-sm btn-outline-dark">Lihat</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="custom-card">
                        <div class="card-header">Verifikasi Berkas</div>
                        <div class="card-body">
                            <p>Periksa semua data dan dokumen sebelum melakukan verifikasi.</p>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="status_pendaftaran" class="form-label">Status Verifikasi</label>
                                    <select name="status_pendaftaran" id="status_pendaftaran" class="form-select">
                                        <option value="Menunggu Verifikasi" <?php echo ($siswa['status_pendaftaran'] == 'Menunggu Verifikasi') ? 'selected' : ''; ?>>Menunggu Verifikasi</option>
                                        <option value="Terverifikasi" <?php echo ($siswa['status_pendaftaran'] == 'Terverifikasi') ? 'selected' : ''; ?>>Terverifikasi (Berkas Valid)</option>
                                        <option value="Tidak Terverifikasi" <?php echo ($siswa['status_pendaftaran'] == 'Tidak Terverifikasi') ? 'selected' : ''; ?>>Tidak Terverifikasi (Berkas Ditolak)</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="btn btn-main">Update Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>