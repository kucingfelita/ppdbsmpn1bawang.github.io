<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/koneksi.php';

$message = '';

// Handle Jurnal Visibility Toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_jurnal'])) {
    $new_jurnal_status = intval($_POST['new_jurnal_status']);
    $stmt = $koneksi->prepare("UPDATE pengumuman SET jurnal_is_active = ? WHERE id_pengumuman = 1");
    $stmt->bind_param("i", $new_jurnal_status);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Status tampilan jurnal berhasil diperbarui.</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal memperbarui status jurnal.</div>';
    }
    $stmt->close();
}

// Handle Pengumuman Release Toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_pengumuman'])) {
    $new_status = intval($_POST['new_status']);

    if ($new_status == 1) { // Proses merilis pengumuman
        $koneksi->begin_transaction();
        try {
            // 1. Ambil semua kuota
            $kuota_data = [];
            $result_kuota = $koneksi->query("SELECT * FROM kuota");
            while ($row = $result_kuota->fetch_assoc()) {
                $kuota_data[$row['jalur']] = (int)$row['jumlah'];
            }

            // 2. Reset semua status 'Lulus'/'Tidak Lulus' kembali ke 'Terverifikasi' untuk idempotensi
            $koneksi->query("UPDATE calon_siswa SET status_pendaftaran = 'Terverifikasi' WHERE status_pendaftaran = 'Lulus'");
            $koneksi->query("UPDATE calon_siswa SET status_pendaftaran = 'Terverifikasi' WHERE status_pendaftaran = 'Tidak Lulus' AND id_siswa IN (SELECT id_siswa FROM (SELECT id_siswa FROM calon_siswa WHERE status_pendaftaran = 'Terverifikasi') as temp)");

            $passing_ids = [];

            // 3. Proses kelulusan per jalur
            foreach ($kuota_data as $jalur => $kuota) {
                $order_by = '';
                switch ($jalur) {
                    case 'Akademik':
                    case 'Non-Akademik':
                        $order_by = 'skor_akhir DESC, tanggal_daftar ASC';
                        break;
                    case 'Afirmasi':
                        $order_by = 'is_disabilitas DESC, jarak_rumah ASC, tanggal_daftar ASC';
                        break;
                }

                $stmt_jalur = $koneksi->prepare("SELECT id_siswa FROM calon_siswa WHERE jalur_pendaftaran = ? AND status_pendaftaran = 'Terverifikasi' ORDER BY $order_by LIMIT ?");
                $stmt_jalur->bind_param("si", $jalur, $kuota);
                $stmt_jalur->execute();
                $result_jalur = $stmt_jalur->get_result();
                while ($row = $result_jalur->fetch_assoc()) {
                    $passing_ids[] = $row['id_siswa'];
                }
                $stmt_jalur->close();
            }

            // 4. Update status siswa
            if (!empty($passing_ids)) {
                $koneksi->query("UPDATE calon_siswa SET status_pendaftaran = 'Lulus' WHERE id_siswa IN (" . implode(',', $passing_ids) . ")");
            }
            // Siswa yang terverifikasi tapi tidak masuk kuota -> Tidak Lulus
            $koneksi->query("UPDATE calon_siswa SET status_pendaftaran = 'Tidak Lulus' WHERE status_pendaftaran = 'Terverifikasi'");
            // Siswa yang tidak terverifikasi / menunggu -> Tidak Lulus
            $koneksi->query("UPDATE calon_siswa SET status_pendaftaran = 'Tidak Lulus' WHERE status_pendaftaran IN ('Menunggu Verifikasi', 'Tidak Terverifikasi')");

            // 5. Update status pengumuman & sembunyikan jurnal
            $stmt_pengumuman = $koneksi->prepare("UPDATE pengumuman SET is_active = 1, jurnal_is_active = 0 WHERE id_pengumuman = 1");
            $stmt_pengumuman->execute();
            $stmt_pengumuman->close();

            $koneksi->commit();
            $message = '<div class="alert alert-success">Pengumuman berhasil dirilis dan status kelulusan siswa telah diperbarui.</div>';

        } catch (Exception $e) {
            $koneksi->rollback();
            $message = '<div class="alert alert-danger">Terjadi kesalahan saat merilis pengumuman: ' . $e->getMessage() . '</div>';
        }

    } else { // Proses menarik pengumuman
        $stmt = $koneksi->prepare("UPDATE pengumuman SET is_active = ? WHERE id_pengumuman = 1");
        $stmt->bind_param("i", $new_status);
        if ($stmt->execute()) {
            $message = '<div class="alert alert-warning">Pengumuman telah ditarik. Status kelulusan siswa tidak berubah.</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal memperbarui status.</div>';
        }
        $stmt->close();
    }
}

// Fetch current status for both
$pengumuman = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman['is_active'];
$jurnal_is_active = $pengumuman['jurnal_is_active'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pengumuman - PPDB Online</title>
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
                    <li class="nav-item"><a class="nav-link" href="data_pendaftar.php"><i class="bi bi-people"></i> Data Pendaftar</a></li>
                    <li class="nav-item"><a class="nav-link" href="kuota.php"><i class="bi bi-pie-chart"></i> Kelola Kuota</a></li>
                    <li class="nav-item"><a class="nav-link active" href="pengumuman.php"><i class="bi bi-megaphone"></i> Pengumuman</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manajemen Tampilan Publik</h1>
            </div>

            <?php echo $message; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="custom-card mb-4">
                        <div class="card-header">Status Tampilan Jurnal Pendaftaran</div>
                        <div class="card-body text-center">
                            <?php if ($jurnal_is_active): ?>
                                <p class="fs-5">Status saat ini: <strong class="text-success">TAMPIL</strong></p>
                                <p>Menu Jurnal Pendaftaran sedang ditampilkan di halaman publik.</p>
                                <form method="POST" action="">
                                    <input type="hidden" name="new_jurnal_status" value="0">
                                    <button type="submit" name="toggle_jurnal" class="btn btn-warning">Sembunyikan Jurnal</button>
                                </form>
                            <?php else: ?>
                                <p class="fs-5">Status saat ini: <strong class="text-danger">SEMBUNYI</strong></p>
                                <p>Menu Jurnal Pendaftaran tidak tampil di halaman publik.</p>
                                <form method="POST" action="">
                                    <input type="hidden" name="new_jurnal_status" value="1">
                                    <button type="submit" name="toggle_jurnal" class="btn btn-primary">Tampilkan Jurnal</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-card">
                        <div class="card-header">Status Pengumuman Hasil Seleksi</div>
                        <div class="card-body text-center">
                            <?php if ($is_active): ?>
                                <p class="fs-5">Status saat ini: <strong class="text-success">DIRILIS</strong></p>
                                <p>Siswa dapat melihat hasil kelulusan mereka.</p>
                                <form method="POST" action="">
                                    <input type="hidden" name="new_status" value="0">
                                    <button type="submit" name="toggle_pengumuman" class="btn btn-warning">Tarik Pengumuman</button>
                                </form>
                            <?php else: ?>
                                <p class="fs-5">Status saat ini: <strong class="text-danger">DITAHAN</strong></p>
                                <p>Siswa belum dapat melihat hasil kelulusan.</p>
                                <div class="alert alert-info my-3 small"><strong>Penting:</strong> Merilis pengumuman akan mengunci status kelulusan semua siswa. Pastikan semua berkas telah diverifikasi.</div>
                                <form method="POST" action="">
                                    <input type="hidden" name="new_status" value="1">
                                    <button type="submit" name="toggle_pengumuman" class="btn btn-main">Rilis Pengumuman Sekarang</button>
                                </form>
                            <?php endif; ?>
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