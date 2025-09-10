<?php
require_once 'config/koneksi.php';
$pengumuman_settings = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman_settings['is_active'];
$jurnal_is_active = $pengumuman_settings['jurnal_is_active'];

$no_pendaftaran = '';
$calon_siswa = null;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_pendaftaran = mysqli_real_escape_string($koneksi, $_POST['no_pendaftaran']);
    if (!empty($no_pendaftaran)) {
        $stmt = $koneksi->prepare("SELECT * FROM calon_siswa WHERE no_pendaftaran = ?");
        $stmt->bind_param("s", $no_pendaftaran);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $calon_siswa = $result->fetch_assoc();
        } else {
            $error = "Nomor pendaftaran tidak ditemukan.";
        }
        $stmt->close();
    } else {
        $error = "Silakan masukkan nomor pendaftaran Anda.";
    }
}
$koneksi->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Status Pendaftaran - PPDB Online</title>
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
                    <?php if ($jurnal_is_active): ?>
                        <li class="nav-item"><a class="nav-link" href="jurnal.php">Jurnal Pendaftaran</a></li>
                    <?php endif; ?>
                    <?php if ($is_active): ?>
                        <li class="nav-item"><a class="nav-link" href="hasil_seleksi.php">Hasil Seleksi</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link active" href="cek_status.php">Cek Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login.php">Login Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ... rest of the file is the same ... -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="custom-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cek Status Pendaftaran Akun</h5>
                    </div>
                    <div class="card-body p-4">
                        <p>Masukkan nomor pendaftaran Anda untuk melihat status dan data pribadi Anda.</p>
                        <form action="cek_status.php" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="no_pendaftaran" name="no_pendaftaran" placeholder="Contoh: PPDB25-XXXXXXXX" value="<?php echo htmlspecialchars($no_pendaftaran); ?>" required>
                                <button type="submit" class="btn btn-main">Cek Status</button>
                            </div>
                        </form>

                        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                            <hr>
                            <?php if ($calon_siswa): ?>
                                <?php
                                    $status = $calon_siswa['status_pendaftaran'];
                                    if ($status == 'Lulus') {
                                        $alert_class = 'success';
                                        $status_text = 'Status: LULUS SELEKSI';
                                    } elseif ($status == 'Tidak Lulus') {
                                        $alert_class = 'danger';
                                        $status_text = 'Status: TIDAK LULUS SELEKSI';
                                    } elseif ($status == 'Terverifikasi') {
                                        $alert_class = 'primary';
                                        $status_text = 'Status: BERKAS TERVERIFIKASI';
                                    } elseif ($status == 'Tidak Terverifikasi') {
                                        $alert_class = 'warning';
                                        $status_text = 'Status: BERKAS TIDAK TERVERIFIKASI';
                                    } else {
                                        $alert_class = 'info';
                                        $status_text = 'Status: MENUNGGU VERIFIKASI';
                                    }
                                ?>
                                <div class="alert alert-<?php echo $alert_class; ?> mt-4">
                                    <h5 class="alert-heading"><?php echo $status_text; ?></h5>
                                </div>

                                <h5 class="mt-4">Data Pribadi Siswa</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%;">No. Pendaftaran</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['no_pendaftaran']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['nama_lengkap']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>NISN</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['nisn']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tempat, Tanggal Lahir</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['tempat_lahir']) . ', ' . date('d F Y', strtotime($calon_siswa['tanggal_lahir'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['jenis_kelamin']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['alamat']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Sekolah Asal</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['sekolah_asal']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jalur Pendaftaran</th>
                                            <td><?php echo htmlspecialchars($calon_siswa['jalur_pendaftaran']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                            <?php elseif ($error): ?>
                                <div class="alert alert-danger mt-4">
                                    <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

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