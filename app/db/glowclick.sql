-- =============================================
-- GlowClick Database — Full Structure
-- =============================================
CREATE DATABASE IF NOT EXISTS glowclick CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE glowclick;

-- USERS
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO users (nama_lengkap, no_hp, username, password, role) VALUES
('Administrator','08000000000','admin','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');

-- LAYANAN
CREATE TABLE IF NOT EXISTS layanan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    harga INT UNSIGNED NOT NULL DEFAULT 0,
    durasi_menit SMALLINT NOT NULL DEFAULT 60,
    is_aktif TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO layanan (nama, kategori, deskripsi, harga, durasi_menit) VALUES
('Botox','Perawatan Wajah','Mengurangi kerutan dan garis halus pada wajah.',1500000,60),
('Skin Booster','Hidrasi Kulit','Meningkatkan hidrasi dan elastisitas kulit.',1200000,60),
('Filler','Estetika','Mengisi area wajah yang kehilangan volume.',2000000,90),
('Exilis','Body Contouring','Mengencangkan kulit dan mengurangi lemak dengan teknologi RF.',1800000,90),
('Facial','Perawatan Dasar','Membersihkan dan merawat kulit wajah secara menyeluruh.',500000,60),
('Laser','Teknologi Terkini','Perawatan kulit menggunakan teknologi laser terkini.',2500000,90),
('Acne Removal','Kulit Bermasalah','Penanganan jerawat dan bekas jerawat secara klinis.',800000,60);

-- DOKTER
CREATE TABLE IF NOT EXISTS dokter (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    spesialisasi VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20) NULL,
    foto VARCHAR(255) NULL,
    is_aktif TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO dokter (nama, spesialisasi, no_hp) VALUES
('dr. Anisa Rahma, Sp.KK','Dermatologi & Kulit','081234567001'),
('dr. Budi Santoso, Sp.BP-RE','Bedah Plastik & Rekonstruksi','081234567002'),
('dr. Citra Dewi, Sp.KK','Laser & Aesthetic Medicine','081234567003'),
('dr. Dian Pertiwi, Sp.GK','Gizi Klinik & Body Contouring','081234567004'),
('dr. Eko Prasetyo, Sp.KK','Dermatologi & Anti-Aging','081234567005');

-- DOKTER_LAYANAN
CREATE TABLE IF NOT EXISTS dokter_layanan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dokter_id INT UNSIGNED NOT NULL,
    layanan_id INT UNSIGNED NOT NULL,
    UNIQUE KEY unique_dl (dokter_id, layanan_id),
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE,
    FOREIGN KEY (layanan_id) REFERENCES layanan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO dokter_layanan (dokter_id, layanan_id) VALUES
(1,5),(1,7),(1,2),(2,3),(2,1),(3,6),(3,1),(3,2),(4,4),(5,1),(5,3),(5,7),(5,5);

-- JADWAL DOKTER
CREATE TABLE IF NOT EXISTS jadwal_dokter (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dokter_id INT UNSIGNED NOT NULL,
    hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    status ENUM('aktif','cuti') NOT NULL DEFAULT 'aktif',
    keterangan VARCHAR(255) NULL,
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai, status) VALUES
(1,'Senin','08:00','17:00','aktif'),(1,'Selasa','08:00','17:00','aktif'),(1,'Rabu','08:00','17:00','aktif'),(1,'Kamis','08:00','17:00','aktif'),(1,'Jumat','08:00','17:00','aktif'),(1,'Sabtu','08:00','17:00','aktif'),(1,'Minggu','08:00','17:00','aktif'),
(2,'Senin','08:00','17:00','aktif'),(2,'Selasa','08:00','17:00','aktif'),(2,'Rabu','08:00','17:00','aktif'),(2,'Kamis','08:00','17:00','aktif'),(2,'Jumat','08:00','17:00','aktif'),(2,'Sabtu','08:00','17:00','aktif'),(2,'Minggu','08:00','17:00','aktif'),
(3,'Senin','08:00','17:00','aktif'),(3,'Selasa','08:00','17:00','aktif'),(3,'Rabu','08:00','17:00','aktif'),(3,'Kamis','08:00','17:00','aktif'),(3,'Jumat','08:00','17:00','aktif'),(3,'Sabtu','08:00','17:00','aktif'),(3,'Minggu','08:00','17:00','aktif'),
(4,'Senin','08:00','17:00','aktif'),(4,'Selasa','08:00','17:00','aktif'),(4,'Rabu','08:00','17:00','aktif'),(4,'Kamis','08:00','17:00','aktif'),(4,'Jumat','08:00','17:00','aktif'),(4,'Sabtu','08:00','17:00','aktif'),(4,'Minggu','08:00','17:00','aktif'),
(5,'Senin','08:00','17:00','aktif'),(5,'Selasa','08:00','17:00','aktif'),(5,'Rabu','08:00','17:00','aktif'),(5,'Kamis','08:00','17:00','aktif'),(5,'Jumat','08:00','17:00','aktif'),(5,'Sabtu','08:00','17:00','aktif'),(5,'Minggu','08:00','17:00','aktif');

-- BOOKING
CREATE TABLE IF NOT EXISTS booking (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    dokter_id INT UNSIGNED NOT NULL,
    layanan_id INT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    jam TIME NOT NULL,
    status ENUM('pending','konfirmasi','selesai','batal') NOT NULL DEFAULT 'pending',
    catatan TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE,
    FOREIGN KEY (layanan_id) REFERENCES layanan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TESTIMONI
CREATE TABLE IF NOT EXISTS testimoni (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kota VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    rating TINYINT NOT NULL DEFAULT 5,
    foto_url VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO testimoni (nama, kota, pesan, rating, foto_url) VALUES
('Sarah Amalia','Jakarta','Booking-nya sangat mudah dan cepat. Kulit saya jadi jauh lebih glowing setelah treatment Skin Booster!',5,'https://ui-avatars.com/api/?name=Sarah+Amalia&background=E8DBB3&color=000&size=100'),
('Dina Rahmawati','Bandung','Saya sudah coba Facial dan Acne Removal di GlowClick, hasilnya memuaskan banget!',5,'https://ui-avatars.com/api/?name=Dina+Rahmawati&background=E8DBB3&color=000&size=100'),
('Maya Putri','Surabaya','Treatment Laser-nya recommended banget! Hasilnya kelihatan dalam 2 minggu.',5,'https://ui-avatars.com/api/?name=Maya+Putri&background=E8DBB3&color=000&size=100');
