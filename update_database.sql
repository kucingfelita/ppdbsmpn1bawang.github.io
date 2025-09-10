-- Perubahan ini akan memodifikasi tabel `calon_siswa` dan membuat tabel `kuota`.
-- Jalankan query ini di database `ppdbonline` Anda melalui phpMyAdmin atau alat serupa.

-- 1. Modify `calon_siswa` table
ALTER TABLE `calon_siswa`
ADD `tempat_lahir` VARCHAR(100) NOT NULL AFTER `nisn`,
ADD `tanggal_lahir` DATE NOT NULL AFTER `tempat_lahir`,
ADD `jenis_kelamin` ENUM('Laki-laki','Perempuan') NOT NULL AFTER `tanggal_lahir`,
ADD `file_kk` VARCHAR(255) DEFAULT NULL AFTER `nilai_rapor`,
ADD `file_akte` VARCHAR(255) DEFAULT NULL AFTER `file_kk`,
ADD `file_surat_kelulusan` VARCHAR(255) DEFAULT NULL AFTER `file_akte`,
ADD `piagam_level` ENUM('Internasional','Nasional','Provinsi','Kabupaten','Kecamatan','Tidak Ada') DEFAULT 'Tidak Ada' AFTER `jalur_pendaftaran`,
ADD `skor_akhir` DECIMAL(10,2) DEFAULT 0.00 AFTER `piagam_level`,
ADD `jarak_rumah` INT(11) DEFAULT NULL COMMENT 'Jarak rumah ke sekolah dalam meter' AFTER `skor_akhir`,
ADD `is_disabilitas` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = Tidak, 1 = Ya' AFTER `jarak_rumah`;

-- Ubah kolom nilai_rapor menjadi nilai_rata_rata untuk kejelasan
ALTER TABLE `calon_siswa` CHANGE `nilai_rapor` `nilai_rata_rata` DECIMAL(5,2) DEFAULT NULL;


-- 2. Create `kuota` table
CREATE TABLE `kuota` (
  `id_kuota` int(11) NOT NULL AUTO_INCREMENT,
  `jalur` ENUM('Akademik','Non-Akademik','Afirmasi') NOT NULL,
  `jumlah` int(11) NOT NULL,
  PRIMARY KEY (`id_kuota`),
  UNIQUE KEY `jalur` (`jalur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Insert default quota values
INSERT INTO `kuota` (`jalur`, `jumlah`) VALUES
('Akademik', 50),
('Non-Akademik', 30),
('Afirmasi', 20);

