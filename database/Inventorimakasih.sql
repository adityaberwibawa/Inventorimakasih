CREATE DATABASE IF NOT EXISTS inventorimakasih
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE inventorimakasih;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS kategori;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    kategori_id INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_barang_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO kategori (nama, deskripsi) VALUES
('Elektronik', 'Perangkat elektronik dan aksesoris komputer.'),
('Furnitur', 'Meja, kursi, lemari, dan perabot ruangan.'),
('Alat Tulis', 'Perlengkapan tulis-menulis dan kebutuhan kantor.'),
('Peralatan Kebersihan', 'Alat dan bahan untuk menjaga kebersihan ruangan.'),
('Perlengkapan Dapur', 'Peralatan untuk kebutuhan dapur dan pantry.'),
('Olahraga', 'Perlengkapan olahraga dan aktivitas fisik.');

INSERT INTO barang (nama, kategori_id, stok, harga, deskripsi) VALUES
('Laptop Lenovo ThinkPad', 1, 5, 12500000.00, 'Laptop untuk operasional kantor.'),
('Mouse Wireless Logitech', 1, 12, 185000.00, 'Mouse tanpa kabel dengan koneksi USB receiver.'),
('Monitor LED 24 Inch', 1, 4, 1750000.00, 'Monitor full HD untuk meja kerja.'),
('Meja Kerja Kayu', 2, 6, 1250000.00, 'Meja kerja ukuran 120 x 60 cm.'),
('Kursi Ergonomis', 2, 8, 950000.00, 'Kursi kerja dengan sandaran adjustable.'),
('Pulpen Gel Hitam', 3, 50, 3500.00, 'Pulpen gel tinta hitam ukuran 0.5 mm.'),
('Sapu Lantai', 4, 10, 25000.00, 'Sapu lantai dengan gagang panjang.'),
('Panci Stainless 20 cm', 5, 3, 180000.00, 'Panci stainless steel untuk kebutuhan pantry.');
