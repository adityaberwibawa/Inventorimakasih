USE inventorimakasih;

-- Data tambahan untuk demo pagination, filter, dan badge stok.
-- Import file ini satu kali setelah database/Inventorimakasih.sql.

INSERT INTO barang (nama, kategori_id, stok, harga, deskripsi) VALUES
('Keyboard Mechanical', 1, 7, 650000.00, 'Keyboard mechanical untuk workstation.'),
('Webcam Full HD', 1, 0, 450000.00, 'Webcam 1080p untuk meeting online.'),
('SSD External 1TB', 1, 2, 1250000.00, 'Penyimpanan eksternal portabel.'),
('Rak Buku Kayu', 2, 4, 850000.00, 'Rak buku tiga tingkat.'),
('Lemari Arsip', 2, 1, 1750000.00, 'Lemari arsip dua pintu.'),
('Spidol Whiteboard', 3, 24, 12000.00, 'Spidol board marker hitam.'),
('Stapler Besar', 3, 5, 45000.00, 'Stapler untuk dokumen tebal.'),
('Tempat Sampah', 4, 6, 75000.00, 'Tempat sampah plastik 20 liter.'),
('Pel Lantai', 4, 3, 35000.00, 'Pel lantai dengan gagang.'),
('Kotak Makan', 5, 12, 25000.00, 'Kotak makan plastik food grade.'),
('Rak Piring', 5, 0, 95000.00, 'Rak piring stainless.'),
('Matras Yoga', 6, 4, 150000.00, 'Matras yoga anti-slip.'),
('Bola Basket', 6, 2, 250000.00, 'Bola basket ukuran standar.');
