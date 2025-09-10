--
-- Database: `ppdbonline`
--

CREATE DATABASE IF NOT EXISTS `ppdbonline` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ppdbonline`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', '''$2y$10$3.4.19.2O9.G3.aH7YqGuej4u24g3j/RAcT/N.j/d.a.K6.d.e''', 'Administrator'); -- password: password123

-- --------------------------------------------------------

--
-- Table structure for table `calon_siswa`
--

CREATE TABLE `calon_siswa` (
  `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `sekolah_asal` varchar(100) NOT NULL,
  `jalur_pendaftaran` enum('Akademik','Non-Akademik','Afirmasi') NOT NULL,
  `nilai_rapor` decimal(5,2) DEFAULT NULL,
  `file_prestasi` varchar(255) DEFAULT NULL,
  `file_kip` varchar(255) DEFAULT NULL,
  `status_pendaftaran` enum('Menunggu Verifikasi','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu Verifikasi',
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `no_pendaftaran` (`no_pendaftaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_pengumuman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `isi`, `tanggal_rilis`, `is_active`) VALUES
(1, 'Pengumuman Hasil Seleksi PPDB 2025', 'Hasil seleksi akan ditampilkan di sini setelah admin merilis pengumuman.', '2025-09-10', 0);
