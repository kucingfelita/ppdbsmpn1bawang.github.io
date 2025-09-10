<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/koneksi.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $akademik = (int)$_POST['akademik'];
    $non_akademik = (int)$_POST['non_akademik'];
    $afirmasi = (int)$_POST['afirmasi'];

    $stmt_akademik = $koneksi->prepare("UPDATE kuota SET jumlah = ? WHERE jalur = 'Akademik'");
    $stmt_akademik->bind_param("i", $akademik);
    $stmt_akademik->execute();

    $stmt_non = $koneksi->prepare("UPDATE kuota SET jumlah = ? WHERE jalur = 'Non-Akademik'");
    $stmt_non->bind_param("i", $non_akademik);
    $stmt_non->execute();

    $stmt_afirmasi = $koneksi->prepare("UPDATE kuota SET jumlah = ? WHERE jalur = 'Afirmasi'");
    $stmt_afirmasi->bind_param("i", $afirmasi);
    $stmt_afirmasi->execute();

    $success_message = "Kuota berhasil diperbarui!";
}

// Fetch current quotas
$kuota_data = [];
$result = $koneksi->query("SELECT * FROM kuota");
while ($row = $result->fetch_assoc()) {
    $kuota_data[$row['jalur']] = $row['jumlah'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kuota - PPDB Online</title>
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
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_pendaftar.php"><i class="bi bi-people"></i> Data Pendaftar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kuota.php"><i class="bi bi-pie-chart"></i> Kelola Kuota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pengumuman.php"><i class="bi bi-megaphone"></i> Pengumuman</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Kelola Kuota Pendaftaran</h1>
            </div>

            <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="custom-card">
                        <div class="card-body">
                            <form method="POST" action="kuota.php">
                                <div class="mb-3">
                                    <label for="akademik" class="form-label">Kuota Jalur Prestasi Akademik</label>
                                    <input type="number" class="form-control" id="akademik" name="akademik" value="<?php echo htmlspecialchars($kuota_data['Akademik'] ?? 0); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="non_akademik" class="form-label">Kuota Jalur Prestasi Non-Akademik</label>
                                    <input type="number" class="form-control" id="non_akademik" name="non_akademik" value="<?php echo htmlspecialchars($kuota_data['Non-Akademik'] ?? 0); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="afirmasi" class="form-label">Kuota Jalur Afirmasi</label>
                                    <input type="number" class="form-control" id="afirmasi" name="afirmasi" value="<?php echo htmlspecialchars($kuota_data['Afirmasi'] ?? 0); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
