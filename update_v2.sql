-- Perubahan ini akan memodifikasi ENUM pada kolom `status_pendaftaran`.
-- Jalankan query ini di database `ppdbonline` Anda.

ALTER TABLE `calon_siswa` 
CHANGE `status_pendaftaran` `status_pendaftaran` 
ENUM('Menunggu Verifikasi','Terverifikasi','Tidak Terverifikasi','Lulus','Tidak Lulus') 
NOT NULL DEFAULT 'Menunggu Verifikasi';
