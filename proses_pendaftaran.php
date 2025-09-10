<?php
session_start();
require_once 'config/koneksi.php';

// Helper function to handle errors
function handleError($message) {
    $_SESSION['error_message'] = $message;
    $_SESSION['form_data'] = $_POST;
    header('Location: daftar.php');
    exit();
}

function generateNoPendaftaran($koneksi) {
    $prefix = 'PPDB' . date('Y');
    $query = "SELECT MAX(no_pendaftaran) as max_no FROM calon_siswa WHERE no_pendaftaran LIKE '$prefix%'";
    $result = $koneksi->query($query);
    $data = $result->fetch_assoc();
    $max_no = $data['max_no'];
    $urutan = (int) substr($max_no, -4);
    $urutan++;
    return $prefix . sprintf("%04d", $urutan);
}

function uploadFile($file, $allowed_ext, $upload_dir = 'uploads/') {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        // Handle case where file is not uploaded, but might not be an error (e.g., optional file)
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        handleError("Error saat mengupload file: " . $file['name'] . " (Code: " . $file['error'] . ")");
    }

    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        handleError("Tipe file tidak diizinkan untuk: " . $file_name . ". Hanya boleh " . implode(', ', $allowed_ext));
    }

    if ($file_size > 2 * 1024 * 1024) { // 2MB max
        handleError("Ukuran file terlalu besar untuk: " . $file_name . ". Maksimal 2MB.");
    }

    $new_file_name = uniqid('', true) . '.' . $file_ext;
    $destination = $upload_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $destination)) {
        return $new_file_name;
    } else {
        handleError("Gagal memindahkan file yang diupload: " . $file_name);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- Basic data ---
    $required_fields = ['nama_lengkap', 'nisn', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'sekolah_asal', 'jalur_pendaftaran'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            handleError("Kolom '" . str_replace('_', ' ', $field) . "' wajib diisi.");
        }
    }

    $no_pendaftaran = generateNoPendaftaran($koneksi);
    $nama_lengkap = $_POST['nama_lengkap'];
    $nisn = $_POST['nisn'];
    // ... (all other POST variables)

    // --- File uploads ---
    $file_kk = uploadFile($_FILES['file_kk'], ['pdf', 'jpg', 'jpeg']);
    if ($file_kk === null) handleError("File Kartu Keluarga wajib diupload.");

    $file_akte = uploadFile($_FILES['file_akte'], ['pdf', 'jpg', 'jpeg']);
    if ($file_akte === null) handleError("File Akta Kelahiran wajib diupload.");

    $file_surat_kelulusan = uploadFile($_FILES['file_surat_kelulusan'], ['pdf', 'jpg', 'jpeg']);
    if ($file_surat_kelulusan === null) handleError("File Surat Kelulusan wajib diupload.");

    // --- Conditional data ---
    $jalur_pendaftaran = $_POST['jalur_pendaftaran'];
    $nilai_rata_rata = NULL;
    $piagam_level = 'Tidak Ada';
    $skor_akhir = 0;
    $file_prestasi = NULL;
    $jarak_rumah = NULL;
    $is_disabilitas = 0;
    $file_kip = NULL;

    $nilai_sem = array_map('floatval', [$_POST['nilai_sem1'], $_POST['nilai_sem2'], $_POST['nilai_sem3'], $_POST['nilai_sem4'], $_POST['nilai_sem5']]);
    $nilai_sem = array_filter($nilai_sem, function($val) { return $val > 0; });
    if (count($nilai_sem) > 0) {
        $nilai_rata_rata = array_sum($nilai_sem) / count($nilai_sem);
    }

    if ($jalur_pendaftaran == 'Akademik') {
        if ($nilai_rata_rata === null) handleError("Nilai rapor untuk jalur akademik wajib diisi.");
        $skor_akhir = $nilai_rata_rata;
    } elseif ($jalur_pendaftaran == 'Non-Akademik') {
        if ($nilai_rata_rata === null) handleError("Nilai rapor untuk jalur non-akademik wajib diisi.");
        $piagam_level = $_POST['piagam_level'];
        $bonus_poin = ['Kecamatan' => 1, 'Kabupaten' => 2, 'Provinsi' => 3, 'Nasional' => 4, 'Internasional' => 5, 'Tidak Ada' => 0];
        $skor_akhir = $nilai_rata_rata + $bonus_poin[$piagam_level];
        
        if ($piagam_level !== 'Tidak Ada') {
            $file_prestasi = uploadFile($_FILES['file_prestasi'], ['pdf']);
            if ($file_prestasi === null) handleError("File prestasi wajib diupload jika memilih tingkat piagam.");
        }
    } elseif ($jalur_pendaftaran == 'Afirmasi') {
        if (empty($_POST['jarak_rumah'])) handleError("Jarak rumah wajib diisi untuk jalur afirmasi.");
        $jarak_rumah = (int)$_POST['jarak_rumah'];
        $is_disabilitas = isset($_POST['is_disabilitas']) ? 1 : 0;
        $file_kip = uploadFile($_FILES['file_kip'], ['pdf']);
        if ($file_kip === null) handleError("File KIP/KKS/PKH wajib diupload untuk jalur afirmasi.");
    }

    $stmt = $koneksi->prepare(
        "INSERT INTO calon_siswa (no_pendaftaran, nama_lengkap, nisn, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, sekolah_asal, jalur_pendaftaran, piagam_level, skor_akhir, jarak_rumah, is_disabilitas, nilai_rata_rata, file_kk, file_akte, file_surat_kelulusan, file_prestasi, file_kip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        'ssssssssssdiiisssss',
        $no_pendaftaran, $_POST['nama_lengkap'], $_POST['nisn'], $_POST['tempat_lahir'], $_POST['tanggal_lahir'], $_POST['jenis_kelamin'], $_POST['alamat'], $_POST['sekolah_asal'], $jalur_pendaftaran, $piagam_level, $skor_akhir, $jarak_rumah, $is_disabilitas, $nilai_rata_rata, $file_kk, $file_akte, $file_surat_kelulusan, $file_prestasi, $file_kip
    );

    if ($stmt->execute()) {
        unset($_SESSION['form_data']); // Clear form data on success
        $_SESSION['sukses_daftar'] = true;
        $_SESSION['no_pendaftaran'] = $no_pendaftaran;
        header("Location: hasil_pendaftaran.php");
        exit();
    } else {
        handleError("Gagal menyimpan data pendaftaran: " . $stmt->error);
    }

} else {
    header('Location: daftar.php');
    exit();
}
?>