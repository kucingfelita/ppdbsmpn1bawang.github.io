<?php
session_start();
require_once 'config/koneksi.php';
$pengumuman_settings = $koneksi->query("SELECT is_active, jurnal_is_active FROM pengumuman WHERE id_pengumuman = 1")->fetch_assoc();
$is_active = $pengumuman_settings['is_active'];
$jurnal_is_active = $pengumuman_settings['jurnal_is_active'];

$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

$form_data = [];
if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

function get_value($field_name) {
    global $form_data;
    return htmlspecialchars($form_data[$field_name] ?? '');
}

function is_checked($field_name, $value) {
    global $form_data;
    return isset($form_data[$field_name]) && $form_data[$field_name] == $value ? 'checked' : '';
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Pendaftaran - PPDB Online</title>
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
                    <li class="nav-item"><a class="nav-link active" href="daftar.php">Daftar Sekarang</a></li>
                    <?php if ($jurnal_is_active): ?>
                        <li class="nav-item"><a class="nav-link" href="jurnal.php">Jurnal Pendaftaran</a></li>
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

    <!-- ... rest of the file is the same ... -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="custom-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Formulir Pendaftaran Siswa Baru</h5>
                    </div>
                    <div class="card-body p-4">

                        <!-- Placeholder for validation errors -->
                        <div id="validation-summary" class="alert alert-danger mb-4" style="display: none;"></div>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger mb-4">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <form id="registration-form" action="proses_pendaftaran.php" method="POST" enctype="multipart/form-data" novalidate>
                            
                            <h6 class="form-section-title">Data Diri Siswa</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo get_value('nama_lengkap'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nisn" class="form-label">NISN</label>
                                    <input type="text" class="form-control" id="nisn" name="nisn" value="<?php echo get_value('nisn'); ?>" required pattern="\d{10}" title="NISN harus terdiri dari 10 digit angka">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo get_value('tempat_lahir'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo get_value('tanggal_lahir'); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="Laki-laki" <?php echo is_checked('jenis_kelamin', 'Laki-laki'); ?> required>
                                    <label class="form-check-label" for="laki_laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan" <?php echo is_checked('jenis_kelamin', 'Perempuan'); ?>>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo get_value('alamat'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="sekolah_asal" class="form-label">Sekolah Asal</label>
                                <input type="text" class="form-control" id="sekolah_asal" name="sekolah_asal" value="<?php echo get_value('sekolah_asal'); ?>" required>
                            </div>

                            <hr class="my-4">

                            <h6 class="form-section-title">Berkas Pendaftaran (Wajib diisi semua)</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="file_kk" class="form-label">Kartu Keluarga (PDF/JPG)</label>
                                    <input class="form-control" type="file" id="file_kk" name="file_kk" accept=".pdf,.jpg,.jpeg" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="file_akte" class="form-label">Akta Kelahiran (PDF/JPG)</label>
                                    <input class="form-control" type="file" id="file_akte" name="file_akte" accept=".pdf,.jpg,.jpeg" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="file_surat_kelulusan" class="form-label">Surat Kelulusan (PDF/JPG)</label>
                                    <input class="form-control" type="file" id="file_surat_kelulusan" name="file_surat_kelulusan" accept=".pdf,.jpg,.jpeg" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="form-section-title">Jalur Pendaftaran</h6>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jalur_pendaftaran" id="jalur_akademik" value="Akademik" <?php echo is_checked('jalur_pendaftaran', 'Akademik'); ?> required onchange="toggleJalurFields()">
                                    <label class="form-check-label" for="jalur_akademik">Prestasi Akademik</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jalur_pendaftaran" id="jalur_non_akademik" value="Non-Akademik" <?php echo is_checked('jalur_pendaftaran', 'Non-Akademik'); ?> onchange="toggleJalurFields()">
                                    <label class="form-check-label" for="jalur_non_akademik">Prestasi Non-Akademik</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jalur_pendaftaran" id="jalur_afirmasi" value="Afirmasi" <?php echo is_checked('jalur_pendaftaran', 'Afirmasi'); ?> onchange="toggleJalurFields()">
                                    <label class="form-check-label" for="jalur_afirmasi">Afirmasi</label>
                                </div>
                            </div>

                            <!-- Conditional Fields -->
                            <div class="p-3 rounded bg-light" id="conditional-fields-wrapper" style="display: none;">

                                <!-- Prestasi Akademik -->
                                <div id="field_akademik" style="display: none;">
                                    <p class="fw-bold">Input Nilai Rapor (Pengetahuan)</p>
                                    <div class="row">
                                        <div class="col mb-2"><input type="number" step="0.01" class="form-control nilai-semester" name="nilai_sem1" placeholder="Sem 1" value="<?php echo get_value('nilai_sem1'); ?>"></div>
                                        <div class="col mb-2"><input type="number" step="0.01" class="form-control nilai-semester" name="nilai_sem2" placeholder="Sem 2" value="<?php echo get_value('nilai_sem2'); ?>"></div>
                                        <div class="col mb-2"><input type="number" step="0.01" class="form-control nilai-semester" name="nilai_sem3" placeholder="Sem 3" value="<?php echo get_value('nilai_sem3'); ?>"></div>
                                        <div class="col mb-2"><input type="number" step="0.01" class="form-control nilai-semester" name="nilai_sem4" placeholder="Sem 4" value="<?php echo get_value('nilai_sem4'); ?>"></div>
                                        <div class="col mb-2"><input type="number" step="0.01" class="form-control nilai-semester" name="nilai_sem5" placeholder="Sem 5" value="<?php echo get_value('nilai_sem5'); ?>"></div>
                                    </div>
                                    <div class="mt-2">
                                        <label for="nilai_rata_rata" class="form-label">Rata-rata Nilai</label>
                                        <input type="text" class="form-control" id="nilai_rata_rata" name="nilai_rata_rata" value="<?php echo get_value('nilai_rata_rata'); ?>" readonly>
                                    </div>
                                </div>

                                <!-- Prestasi Non-Akademik -->
                                <div id="field_non_akademik" style="display: none;">
                                    <div class="mb-3">
                                        <label for="piagam_level" class="form-label">Tingkat Piagam Prestasi</label>
                                        <select class="form-select" name="piagam_level">
                                            <option value="Tidak Ada" <?php echo (get_value('piagam_level') == 'Tidak Ada') ? 'selected' : ''; ?>>Tidak Memiliki Piagam</option>
                                            <option value="Kecamatan" <?php echo (get_value('piagam_level') == 'Kecamatan') ? 'selected' : ''; ?>>Kecamatan (+1 poin)</option>
                                            <option value="Kabupaten" <?php echo (get_value('piagam_level') == 'Kabupaten') ? 'selected' : ''; ?>>Kabupaten (+2 poin)</option>
                                            <option value="Provinsi" <?php echo (get_value('piagam_level') == 'Provinsi') ? 'selected' : ''; ?>>Provinsi (+3 poin)</option>
                                            <option value="Nasional" <?php echo (get_value('piagam_level') == 'Nasional') ? 'selected' : ''; ?>>Nasional (+4 poin)</option>
                                            <option value="Internasional" <?php echo (get_value('piagam_level') == 'Internasional') ? 'selected' : ''; ?>>Internasional (+5 poin)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file_prestasi" class="form-label">Upload Scan Sertifikat/Piagam (PDF, max 2MB)</label>
                                        <input class="form-control" type="file" id="file_prestasi" name="file_prestasi" accept=".pdf">
                                    </div>
                                </div>

                                <!-- Afirmasi -->
                                <div id="field_afirmasi" style="display: none;">
                                    <div class="mb-3">
                                        <label for="jarak_rumah" class="form-label">Jarak Rumah ke Sekolah (dalam meter)</label>
                                        <input type="number" class="form-control" id="jarak_rumah" name="jarak_rumah" placeholder="Contoh: 500" value="<?php echo get_value('jarak_rumah'); ?>">
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="is_disabilitas" name="is_disabilitas" value="1" <?php echo is_checked('is_disabilitas', '1'); ?>>
                                        <label class="form-check-label" for="is_disabilitas">Siswa Penyandang Disabilitas</label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file_kip" class="form-label">Upload Scan Kartu KIP/KKS/PKH (PDF, max 2MB)</label>
                                        <input class="form-control" type="file" id="file_kip" name="file_kip" accept=".pdf">
                                        <div class="form-text">Wajib diisi untuk jalur Afirmasi.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-main btn-lg">Daftar Sekarang</button>
                            </div>
                        </form>
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
    <script>
        // ... [validation and other JS is the same] ...
    </script>
</body>
</html>