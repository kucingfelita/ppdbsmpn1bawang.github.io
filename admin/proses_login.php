<?php
session_start();
require_once '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $koneksi->prepare("SELECT id_admin, username, password, nama_lengkap FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            
            // Password di SQL adalah hash dari 'password123'
            // $2y$10$3.4.19.2O9.G3.aH7YqGuej4u24g3j/RAcT/N.j/d.a.K6.d.e
            if (password_verify($password, $admin['password'])) {
                // Login berhasil, set session
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];

                header("Location: index.php");
                exit();
            } else {
                // Password salah
                header("Location: login.php?error=1");
                exit();
            }
        } else {
            // Username tidak ditemukan
            header("Location: login.php?error=1");
            exit();
        }
        $stmt->close();
    } else {
        header("Location: login.php?error=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

$koneksi->close();
?>
