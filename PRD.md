Berikut penyesuaian **PRD + Struktur Folder + Pembagian Tugas** untuk **2 orang**.

---

# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Inventorimakasih – Sistem Manajemen Inventaris Barang  

**Versi:** 1.2  
**Tanggal:** 31 Oktober 2026  
**Jumlah Pengembang:** 2 Orang  
**Matkul:** Pemrograman Web

---

### 1. Ringkasan Produk

**Inventorimakasih** adalah aplikasi web berbasis PHP (MVC) untuk mengelola data kategori dan barang inventaris. Aplikasi memungkinkan pengguna melakukan operasi CRUD lengkap terhadap kategori dan barang, dengan validasi input di sisi controller, menggunakan database MySQL melalui PDO + prepared statement.

---

### 2. Fitur yang Harus Ada

#### Manajemen Kategori
- Lihat daftar kategori
- Tambah kategori
- Edit kategori
- Hapus kategori

#### Manajemen Barang
- Lihat daftar barang (beserta nama kategorinya)
- Tambah barang
- Edit barang
- Hapus barang

#### Fitur Pendukung
- Flash message (sukses / error / warning)
- Validasi input di Controller
- Layout responsif sederhana (Tailwind)

#### Fitur Tambahan (v1.2 — telah diimplementasikan)
- Dashboard inventaris: total barang, total kategori, nilai stok, barang perlu restock
- Pencarian nama/deskripsi/kategori (wildcard `%`/`_` di-escape)
- Filter status stok (habis, menipis, aman, perlu restock) dan filter kategori
- Sort kolom (whitelist) + pagination
- Badge status stok otomatis + peringatan flash stok menipis/habis
- Export CSV sesuai filter aktif (anti formula injection)
- Proteksi CSRF pada semua form POST
- Input harga menerima koma desimal (`15,5` → `15.50`)

---

### 3. Database

```sql
CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    kategori_id INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE RESTRICT
);
```

---

### 4. Rute

| Method | URL                     | Keterangan             |
|--------|-------------------------|------------------------|
| GET    | `/` atau `/barang`      | Daftar barang          |
| GET    | `/barang/tambah`        | Form tambah barang     |
| POST   | `/barang/simpan`        | Proses simpan barang   |
| GET    | `/barang/edit/{id}`     | Form edit barang       |
| POST   | `/barang/update`        | Proses update barang   |
| POST   | `/barang/hapus`         | Proses hapus barang    |
| GET    | `/barang/export`        | Export CSV barang      |
| GET    | `/kategori`             | Daftar kategori        |
| GET    | `/kategori/tambah`      | Form tambah kategori   |
| POST   | `/kategori/simpan`      | Proses simpan kategori |
| GET    | `/kategori/edit/{id}`   | Form edit kategori     |
| POST   | `/kategori/update`      | Proses update kategori |
| POST   | `/kategori/hapus`       | Proses hapus kategori  |

---

### 5. Struktur Folder (Sama)

```
Inventorimakasih/
├── public/
│   ├── index.php
│   ├── router.php
│   ├── .htaccess
│   └── assets/
│       ├── css/style.css
│       └── js/script.js
│
├── app/
│   ├── Controllers/
│   │   ├── Controller.php
│   │   ├── BarangController.php
│   │   └── KategoriController.php
│   ├── Models/
│   │   ├── Model.php
│   │   ├── Barang.php
│   │   └── Kategori.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── errors/
│   │   │   └── error.php
│   │   ├── barang/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   ├── edit.php
│   │   │   └── _form.php
│   │   └── kategori/
│   │       ├── index.php
│   │       ├── create.php
│   │       ├── edit.php
│   │       └── _form.php
│   └── Core/
│       ├── Router.php
│       ├── Database.php
│       └── Helper.php
│
├── config/
│   └── database.php
│
├── database/
│   ├── Inventorimakasih.sql
│   └── seed_demo.sql
│
├── .gitignore
└── README.md
```

---

### 6. Pembagian Tugas (2 Orang)

| Anggota | Role Utama                          | Branch yang dikerjakan                          | Deliverable Utama                                                                 | Review PR |
|---------|-------------------------------------|--------------------------------------------------|------------------------------------------------------------------------------------|-----------|
| **A**   | Foundation + Kategori + Validasi    | `feature/setup-mvc`<br>`feature/kategori-crud`   | Struktur folder, Router, Database, Base Controller/Model, CRUD Kategori, Validasi dasar, Layout dasar | Review PR B |
| **B**   | CRUD Barang + UI + Integrasi        | `feature/barang-crud`<br>`feature/ui-integrasi`  | Model + Controller + View Barang, Flash message, Styling Tailwind, Validasi lengkap, README | Review PR A |

---

### 7. Detail Tugas per Anggota

#### Anggota A – Foundation + Kategori
1. Buat struktur folder lengkap
2. Buat `config/database.php` + class `Database.php` (PDO)
3. Buat `Router.php` dan Base `Controller.php` + `Model.php`
4. Buat Model, Controller, View **Kategori** (CRUD lengkap)
5. Buat file SQL + data dummy
6. Buat layout dasar (`header.php` + `footer.php`)
7. Tambahkan validasi dasar di KategoriController
8. Buat branch & Pull Request

#### Anggota B – Barang + UI + Integrasi
1. Buat Model `Barang.php` (termasuk join ke kategori)
2. Buat `BarangController.php` (CRUD lengkap)
3. Buat View Barang (daftar, form tambah, form edit)
4. Implementasi sistem **flash message**
5. Lengkapi validasi di semua Controller
6. Rapikan tampilan dengan Tailwind 
7. Buat `README.md`
8. Pastikan semua rute terhubung dengan baik
9. Buat branch & Pull Request

---

### 8. Aturan Git & Workflow (Tetap Berlaku)

1. Setiap fitur dikerjakan di **branch sendiri**
2. Setelah selesai → buat **Pull Request** ke `main`
3. Satu orang **wajib review + approve** sebelum merge
4. Commit message jelas, contoh:
   - `feat: add create kategori`
   - `feat: add barang crud`
   - `fix: validate stok cannot be negative`
   - `style: improve form layout`
5. Jangan push langsung ke `main`

---

### 9. Timeline Saran (2 Minggu)

| Hari  | Aktivitas                                      |
|-------|------------------------------------------------|
| 1–2   | Finalisasi PRD, buat repo, assign tugas        |
| 3–6   | Anggota A kerjakan setup + kategori            |
| 5–9   | Anggota B kerjakan barang + UI (paralel)       |
| 10–11 | Saling review PR + integrasi + bug fixing      |
| 12    | Demo internal + polish terakhir                |
| 13    | Demo ke dosen                                  |

---

### 10. Tips Nilai Individu (2 Orang)

- Commit sering dan bermakna
- Review PR teman dengan komentar yang jelas
- Pastikan di history Git terlihat jelas siapa mengerjakan apa
- Jangan saling “numpang” commit

---

### 11. Riwayat Versi

| Versi | Tanggal | Perubahan |
|-------|---------|-----------|
| 1.0 | — | PRD awal, pembagian tugas 2 orang |
| 1.1 | 30 Okt 2026 | Penyesuaian PRD + struktur folder + pembagian tugas untuk 2 orang |
| 1.2 | 31 Okt 2026 | Implementasi selesai & di-merge: PR #1 (setup MVC), PR #2 (kategori CRUD), PR #3 (barang CRUD + UI + export CSV), PR #4 (hardening: CSRF, escape wildcard LIKE, dedup, harga koma desimal, fputcsv eksplisit) |