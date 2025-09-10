<?php
require_once 'config/koneksi.php';

$pengumuman_settings = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman_settings['is_active'];
$jurnal_is_active = $pengumuman_settings['jurnal_is_active'];

$hasil_seleksi = [];
if ($is_active) {
    // Ambil juga NISN dari database
    $result = $koneksi->query("SELECT nama_lengkap, nisn, sekolah_asal, jalur_pendaftaran FROM calon_siswa WHERE status_pendaftaran = 'Lulus' ORDER BY jalur_pendaftaran, nama_lengkap ASC");
    while ($row = $result->fetch_assoc()) {
        $hasil_seleksi[$row['jalur_pendaftaran']][] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Seleksi - PPDB Online</title>
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
                        <li class="nav-item"><a class="nav-link" href="jurnal.php">Jurnal Pendaftaran</a></li>
                    <?php endif; ?>
                    <?php if ($is_active): ?>
                        <li class="nav-item"><a class="nav-link active" href="hasil_seleksi.php">Hasil Seleksi</a></li>
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
            <h2>Pengumuman Hasil Seleksi PPDB Online</h2>
            <p class="lead">Tahun Ajaran 2025/2026</p>
        </div>

        <?php if ($is_active && !empty($hasil_seleksi)): ?>
            <?php foreach ($hasil_seleksi as $jalur => $siswa_list): ?>
                <h4 class="mt-5 mb-3">Jalur <?php echo htmlspecialchars($jalur); ?></h4>
                <div class="custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama Lengkap</th>
                                        <th scope="col">NISN</th>
                                        <th scope="col">Sekolah Asal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $nomor = 1;
                                    foreach ($siswa_list as $siswa): 
                                    ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo $nomor++; ?></td>
                                        <td><?php echo htmlspecialchars($siswa['nama_lengkap']); ?></td>
                                        <td><?php echo htmlspecialchars($siswa['nisn']); ?></td>
                                        <td><?php echo htmlspecialchars($siswa['sekolah_asal']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-info text-center">
                        <h4 class="alert-heading">Informasi</h4>
                        <p>Hasil seleksi akan ditampilkan di halaman ini setelah pengumuman resmi dirilis oleh panitia.</p>
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