<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/koneksi.php';

// Filtering logic
$where_clauses = [];
$filter_jalur = isset($_GET['jalur']) ? $_GET['jalur'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

if (!empty($filter_jalur)) {
    $where_clauses[] = "jalur_pendaftaran = '" . mysqli_real_escape_string($koneksi, $filter_jalur) . "'";
}
if (!empty($filter_status)) {
    $where_clauses[] = "status_pendaftaran = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
}

$sql = "SELECT * FROM calon_siswa";
if (count($where_clauses) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY tanggal_daftar DESC";

$query = mysqli_query($koneksi, $sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pendaftar - PPDB Online</title>
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
                <h1 class="h2">Data Pendaftar</h1>
            </div>

            <div class="custom-card mb-4">
                <div class="card-body">
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-4">
                            <select name="jalur" class="form-select">
                                <option value="">Semua Jalur</option>
                                <option value="Akademik" <?php echo ($filter_jalur == 'Akademik') ? 'selected' : ''; ?>>Akademik</option>
                                <option value="Non-Akademik" <?php echo ($filter_jalur == 'Non-Akademik') ? 'selected' : ''; ?>>Non-Akademik</option>
                                <option value="Afirmasi" <?php echo ($filter_jalur == 'Afirmasi') ? 'selected' : ''; ?>>Afirmasi</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Menunggu Verifikasi" <?php echo ($filter_status == 'Menunggu Verifikasi') ? 'selected' : ''; ?>>Menunggu Verifikasi</option>
                                <option value="Diterima" <?php echo ($filter_status == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
                                <option value="Ditolak" <?php echo ($filter_status == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-main">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="custom-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No. Pendaftaran</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">NISN</th>
                                    <th scope="col">Jalur</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($siswa = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($siswa['no_pendaftaran']); ?></td>
                                    <td><?php echo htmlspecialchars($siswa['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($siswa['nisn']); ?></td>
                                    <td><?php echo htmlspecialchars($siswa['jalur_pendaftaran']); ?></td>
                                    <td>
                                        <?php 
                                            $status = $siswa['status_pendaftaran'];
                                            $badge_map = [
                                                'Menunggu Verifikasi' => 'bg-warning',
                                                'Terverifikasi' => 'bg-primary',
                                                'Tidak Terverifikasi' => 'bg-secondary',
                                                'Lulus' => 'bg-success',
                                                'Tidak Lulus' => 'bg-danger',
                                            ];
                                            $badge_class = $badge_map[$status] ?? 'bg-dark';
                                            echo "<span class='badge $badge_class'>" . htmlspecialchars($status) . "</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <a href="detail_siswa.php?id=<?php echo $siswa['id_siswa']; ?>" class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
