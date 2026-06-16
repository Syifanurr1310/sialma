# SIALMA — Sistem Informasi Layanan Administrasi Mahasiswa

Aplikasi web berbasis **PHP + MySQL** untuk mengelola layanan administrasi mahasiswa
(pengajuan surat keterangan, izin penelitian, legalisasi dokumen, dst.) sesuai
dokumen SRS *"Perancangan Sistem Informasi Layanan Administrasi Mahasiswa Berbasis Web"*
(Syifa Nur Ramadhani — 2413025019, Universitas Lampung).

## Fitur Utama

### Mahasiswa
- Login & registrasi mandiri
- Dashboard ringkasan pengajuan
- Ajukan layanan + unggah dokumen (multi-file)
- Lihat status pengajuan real-time
- Riwayat pengajuan + detail
- Notifikasi otomatis
- Profil & ganti password

### Admin Akademik
- Dashboard statistik
- Kelola pengajuan (verifikasi, tolak, tandai selesai)
- Catatan admin
- Manajemen jenis layanan
- Data pengguna

### Pihak Berwenang (Dosen/Fakultas)
- Antrian persetujuan
- Setujui / Tolak pengajuan dengan catatan
- Laporan: per status, per layanan, per bulan

## Instalasi (XAMPP / Laragon)

1. Copy seluruh folder `sialma/` ke `htdocs/`.
2. Buka **phpMyAdmin** → buat database / import file `database/sialma.sql`.
3. Sesuaikan kredensial DB pada `config/database.php` bila perlu
   (default: host `localhost`, user `root`, pass kosong, db `sialma_db`).
4. Pastikan folder `uploads/` writable (chmod 0777 di Linux).
5. Akses `http://localhost/sialma/`.

## Akun Demo

| Role       | Username    | Password      |
|------------|-------------|---------------|
| Mahasiswa  | `mahasiswa` | `password123` |
| Admin      | `admin`     | `password123` |
| Dosen      | `dosen`     | `password123` |

## Struktur Folder

```
sialma/
├── index.php
├── config/database.php
├── includes/        (auth.php, header.php, footer.php)
├── assets/          (css, js, img)
├── auth/            (login, register, logout)
├── mahasiswa/       (dashboard, ajukan, riwayat, detail, notifikasi, profil)
├── admin/           (dashboard, pengajuan, detail, layanan, pengguna)
├── dosen/           (dashboard, persetujuan, detail, laporan)
├── uploads/         (file dokumen mahasiswa)
└── database/sialma.sql
```

## Palet Warna
`#FFB894 · #FB9590 · #DC586D · #A33757 · #852E4E · #4C1D3D`

## Teknologi
- PHP 7.4+ / 8.x (mysqli, password_hash bcrypt)
- MySQL / MariaDB
- HTML5 + CSS3 (custom design system) + JavaScript vanilla
