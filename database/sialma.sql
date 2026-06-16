CREATE DATABASE IF NOT EXISTS sikam_db;
USE sikam_db;

-- Tabel User (Mahasiswa, Admin, Dosen)
CREATE TABLE `user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `npm` VARCHAR(20) DEFAULT NULL,
  `prodi` VARCHAR(100) DEFAULT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('mahasiswa','admin','dosen') NOT NULL DEFAULT 'mahasiswa',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Jenis Layanan
CREATE TABLE `jenis_layanan` (
  `id_layanan` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_layanan` VARCHAR(150) NOT NULL,
  `deskripsi` TEXT,
  `aktif` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Pengajuan
CREATE TABLE `pengajuan` (
  `id_pengajuan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `id_layanan` INT(11) NOT NULL,
  `keperluan` TEXT NOT NULL,
  `status` ENUM('Menunggu Verifikasi','Diverifikasi','Disetujui','Ditolak','Selesai') DEFAULT 'Menunggu Verifikasi',
  `catatan_admin` TEXT,
  `catatan_dosen` TEXT,
  `tanggal_pengajuan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `tanggal_update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengajuan`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE CASCADE,
  FOREIGN KEY (`id_layanan`) REFERENCES `jenis_layanan`(`id_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Dokumen
CREATE TABLE `dokumen` (
  `id_dokumen` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pengajuan` INT(11) NOT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `tanggal_upload` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan`(`id_pengajuan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Notifikasi
CREATE TABLE `notifikasi` (
  `id_notif` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `pesan` TEXT NOT NULL,
  `dibaca` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notif`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DATA SEED
-- Password default: "password123" (hashed)
-- =====================================================
INSERT INTO `user` (`nama`, `username`, `password`, `email`, `npm`, `prodi`, `role`) VALUES
('Syifa Nur Ramadhani', 'mahasiswa', '$2y$12$kvB3dKoAJ1k2jgg1EHx7Jej/lwpghKnI23z4Hmk3KTVMeIJrXmtf6', 'syifa@student.unila.ac.id', '2413025019', 'Pendidikan Teknologi Informasi', 'mahasiswa'),
('Admin Akademik', 'admin', '$2y$12$kvB3dKoAJ1k2jgg1EHx7Jej/lwpghKnI23z4Hmk3KTVMeIJrXmtf6', 'admin@unila.ac.id', NULL, NULL, 'admin'),
('Daniel Rinaldi, S.T., M.Eng.', 'dosen', '$2y$12$kvB3dKoAJ1k2jgg1EHx7Jej/lwpghKnI23z4Hmk3KTVMeIJrXmtf6', 'daniel@unila.ac.id', NULL, NULL, 'dosen');

INSERT INTO `jenis_layanan` (`nama_layanan`, `deskripsi`) VALUES
('Surat Keterangan Aktif Kuliah', 'Surat yang menyatakan mahasiswa aktif dalam perkuliahan pada semester berjalan.'),
('Surat Izin Penelitian', 'Surat untuk keperluan penelitian, observasi, atau pengambilan data di instansi tertentu.'),
('Legalisasi Dokumen', 'Pengesahan/legalisasi salinan ijazah, transkrip, atau dokumen akademik lainnya.'),
('Surat Rekomendasi', 'Surat rekomendasi untuk beasiswa, magang, atau keperluan akademik lain.'),
('Surat Keterangan Lulus', 'Surat keterangan bahwa mahasiswa telah dinyatakan lulus.'),
('Surat Cuti Akademik', 'Pengajuan surat cuti akademik untuk satu semester atau lebih.');

-- Tabel reset password (ditambahkan untuk fitur Lupa Password)
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `token_hash` VARCHAR(64) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_token` (`token_hash`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
