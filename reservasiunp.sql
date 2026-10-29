CREATE DATABASE IF NOT EXISTS reservasiunp;
USE reservasiunp;

-- ========================
-- TABEL USERS (admin & mahasiswa)
-- ========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INSERT ADMIN DEFAULT
INSERT INTO users (nama, email, password, role)
VALUES ('Admin UNP', 'admin@unp.ac.id', MD5('admin123'), 'admin');

-- ========================
-- TABEL AULA
-- ========================
CREATE TABLE aula (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_aula VARCHAR(200) NOT NULL,
    harga_weekend INT DEFAULT 0
);

INSERT INTO aula (nama_aula, harga_weekend) VALUES
('AUDITORIUM UNP', 600000),
('AULA FMIPA UNP', 500000),
('RUANG SENAT LANTAI 3 GEDUNG BAGONJONG', 700000),
('AULA FIK', 450000),
('RUANG SERBA GUNA FAKULTAS TEKNIK', 550000),
('AULA FAKULTAS PARIWISATA DAN PERHOTELAN', 600000),
('AUDITORIUM PROF KAMALUDIN FEB', 500000),
('AULA FPK UNP', 400000),
('AULA GEDUNG MKU UNP', 450000),
('AULA FAKULTAS ILMU PENDIDIKAN UNP', 500000),
('AULA FIS UNP', 600000),
('AULA FBS UNP', 450000);

-- ========================
-- TABEL RESERVASI
-- ========================
CREATE TABLE reservasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    aula_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    dokumen VARCHAR(255),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (aula_id) REFERENCES aula(id) ON DELETE CASCADE
);
