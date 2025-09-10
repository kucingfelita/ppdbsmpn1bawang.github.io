<?php
require_once 'config/koneksi.php';

$pengumuman_settings = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman_settings['is_active'];
$jurnal_is_active = $pengumuman_settings['jurnal_is_active'];

// Redirect if journal is not active
if (!$jurnal_is_active) {
    header("Location: index.php"); // Redirect to home if journal is globally disabled
    exit();
}

// Fetch quota
$kuota = (int) $koneksi->query("SELECT jumlah FROM kuota WHERE jalur = 'Non-Akademik'")->fetch_assoc()['jumlah'];

// Fetch students
$sql = "SELECT no_pendaftaran, nama_lengkap, sekolah_asal, piagam_level, skor_akhir, status_pendaftaran FROM calon_siswa WHERE jalur_pendaftaran = 'Non-Akademik' AND status_pendaftaran = 'Terverifikasi'";
if ($is_active) {
    header("Location: hasil_seleksi.php");
    exit();
}
$sql .= " ORDER BY skor_akhir DESC, tanggal_daftar ASC";
$result = $koneksi->query($sql);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jurnal Prestasi Non-Akademik - PPDB Online</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="jurnal.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            <h2 class="mb-0 text-center">Jurnal Jalur Prestasi Non-Akademik</h2>
            <div style="width: 80px;"></div>
        </div>
        <p class="text-center lead">Kuota Jalur: <strong><?php echo $kuota; ?> siswa</strong>. Peringkat diurutkan berdasarkan Skor Akhir tertinggi.</p>
        <p class="text-center text-muted">Ini adalah peringkat sementara. Hasil akhir ditentukan setelah semua berkas diverifikasi oleh panitia.</p>

        <div class="custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Peringkat</th>
                                <th scope="col">Nama Lengkap</th>
                                <th scope="col">Sekolah Asal</th>
                                <th scope="col">Piagam</th>
                                <th scope="col">Skor Akhir</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $peringkat = 1;
                            while($siswa = $result->fetch_assoc()): 
                                $nama_samaran = strtoupper(substr($siswa['nama_lengkap'], 0, 3)) . '***';
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo $peringkat; ?></td>
                                <td><?php echo htmlspecialchars($nama_samaran); ?></td>
                                <td><?php echo htmlspecialchars($siswa['sekolah_asal']); ?></td>
                                <td><?php echo htmlspecialchars($siswa['piagam_level']); ?></td>
                                <td><?php echo number_format($siswa['skor_akhir'], 2); ?></td>
                                <td>
                                    <?php 
                                    if ($peringkat <= $kuota) {
                                        echo '<span class="badge bg-primary">Dalam Kuota</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">Di Luar Kuota</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php 
                            $peringkat++;
                            endwhile; 
                            ?>
                        </tbody>
                    </table>
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