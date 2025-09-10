-- Perubahan ini menambahkan kolom untuk mengontrol visibilitas Jurnal Pendaftaran.
-- Jalankan query ini di database `ppdbonline` Anda.

ALTER TABLE `pengumuman` 
ADD `jurnal_is_active` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = Sembunyi, 1 = Tampil' AFTER `is_active`;
