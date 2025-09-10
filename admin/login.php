<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/cropped-cropped-BAHAN-WEB-1.png" type="image/png">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="custom-card" style="max-width: 400px; width: 100%;">
            <div class="card-header text-center">
                <h4 class="card-title mb-0">Admin Login</h4>
                <p class="mb-0 text-muted">PPDB Online SMPN 1 Bawang</p>
            </div>
            <div class="card-body p-4">
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger">Username atau password salah.</div>';
                }
                ?>
                <form action="proses_login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-main">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="../index.php">Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
