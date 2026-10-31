<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Barang;
use App\Models\Kategori;

final class BarangController extends Controller
{
    private Barang $barang;
    private Kategori $kategori;

    public function __construct()
    {
        $this->barang = new Barang();
        $this->kategori = new Kategori();
    }

    public function index(): void
    {
        $filters = $this->filters();
        $result = $this->barang->paginate($filters, $this->currentPage());

        $this->view('barang/index', [
            'title' => 'Daftar Barang',
            'active' => 'barang',
            'barang' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
            'kategori' => $this->kategori->all(),
            'stats' => $this->barang->stats(),
            'lowStockThreshold' => Barang::STOK_MENIPIS,
        ]);
    }

    public function create(): void
    {
        $this->view('barang/create', [
            'title' => 'Tambah Barang',
            'active' => 'barang',
            'errors' => [],
            'old' => $this->emptyInput(),
            'kategori' => $this->kategori->all(),
        ]);
    }

    public function store(): void
    {
        $data = $this->collectInput();
        $errors = $this->validate($data);

        if ($errors !== []) {
            http_response_code(422);

            $this->view('barang/create', [
                'title' => 'Tambah Barang',
                'active' => 'barang',
                'errors' => $errors,
                'old' => $data,
                'kategori' => $this->kategori->all(),
            ]);

            return;
        }

        $this->barang->create($this->normalize($data));

        flash('success', 'Barang "' . $data['nama'] . '" berhasil ditambahkan.');
        $this->flashLowStock($data['nama'], (int) $data['stok']);
        $this->redirect('/barang');
    }

    public function edit(string $id): void
    {
        $barang = $this->findOrFail($this->parseId($id));

        $this->view('barang/edit', [
            'title' => 'Edit Barang',
            'active' => 'barang',
            'errors' => [],
            'barang' => $barang,
            'old' => $this->oldFromBarang($barang),
            'kategori' => $this->kategori->all(),
        ]);
    }

    public function update(): void
    {
        $id = $this->parseId($this->postString('id'));
        $barang = $this->findOrFail($id);
        $data = $this->collectInput();
        $errors = $this->validate($data);

        if ($errors !== []) {
            http_response_code(422);

            $this->view('barang/edit', [
                'title' => 'Edit Barang',
                'active' => 'barang',
                'errors' => $errors,
                'barang' => $barang,
                'old' => array_merge(['id' => (string) $id], $data),
                'kategori' => $this->kategori->all(),
            ]);

            return;
        }

        $this->barang->update($id, $this->normalize($data));

        flash('success', 'Barang "' . $data['nama'] . '" berhasil diperbarui.');
        $this->flashLowStock($data['nama'], (int) $data['stok']);
        $this->redirect('/barang');
    }

    public function destroy(): void
    {
        $id = $this->parseId($this->postString('id'));
        $barang = $this->findOrFail($id);

        $this->barang->delete($id);

        flash('success', 'Barang "' . $barang['nama'] . '" berhasil dihapus.');
        $this->redirect('/barang');
    }

    public function export(): void
    {
        $rows = $this->barang->allForExport($this->filters());
        $filename = 'barang-' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'wb');

        if ($output === false) {
            return;
        }

        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['ID', 'Kode', 'Nama', 'Kategori', 'Stok', 'Harga', 'Nilai', 'Deskripsi', 'Dibuat'], ';');

        foreach ($rows as $row) {
            fputcsv($output, [
                (int) $row['id'],
                sprintf('BRG-%04d', (int) $row['id']),
                $this->csvValue($row['nama']),
                $this->csvValue($row['kategori_nama']),
                (int) $row['stok'],
                (float) $row['harga'],
                (float) $row['stok'] * (float) $row['harga'],
                $this->csvValue($row['deskripsi'] ?? ''),
                (string) $row['created_at'],
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * @return array{nama: string, kategori_id: string, stok: string, harga: string, deskripsi: string}
     */
    private function collectInput(): array
    {
        return [
            'nama' => $this->postString('nama'),
            'kategori_id' => $this->postString('kategori_id'),
            'stok' => $this->postString('stok'),
            'harga' => $this->postString('harga'),
            'deskripsi' => $this->postString('deskripsi'),
        ];
    }

    /**
     * @return array{nama: string, kategori_id: string, stok: string, harga: string, deskripsi: string}
     */
    private function emptyInput(): array
    {
        return [
            'nama' => '',
            'kategori_id' => '',
            'stok' => '0',
            'harga' => '0',
            'deskripsi' => '',
        ];
    }

    /**
     * @param array<string, mixed> $barang
     * @return array{nama: string, kategori_id: string, stok: string, harga: string, deskripsi: string, id: string}
     */
    private function oldFromBarang(array $barang): array
    {
        return [
            'id' => (string) $barang['id'],
            'nama' => (string) $barang['nama'],
            'kategori_id' => (string) $barang['kategori_id'],
            'stok' => (string) $barang['stok'],
            'harga' => (string) $barang['harga'],
            'deskripsi' => (string) ($barang['deskripsi'] ?? ''),
        ];
    }

    /**
     * @param array{nama: string, kategori_id: string, stok: string, harga: string, deskripsi: string} $data
     * @return array{nama: string, kategori_id: int, stok: int, harga: string, deskripsi: string}
     */
    private function normalize(array $data): array
    {
        return [
            'nama' => $data['nama'],
            'kategori_id' => (int) $data['kategori_id'],
            'stok' => (int) $data['stok'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'],
        ];
    }

    /**
     * @param array{nama: string, kategori_id: string, stok: string, harga: string, deskripsi: string} $data
     * @return array<string, string>
     */
    private function validate(array $data): array
    {
        $errors = [];

        $nama = $data['nama'];

        if ($nama === '') {
            $errors['nama'] = 'Nama barang wajib diisi.';
        } elseif (mb_strlen($nama) > 150) {
            $errors['nama'] = 'Nama barang maksimal 150 karakter.';
        }

        $kategoriId = filter_var($data['kategori_id'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($kategoriId === false) {
            $errors['kategori_id'] = 'Pilih kategori yang valid.';
        } elseif ($this->kategori->find($kategoriId) === null) {
            $errors['kategori_id'] = 'Kategori yang dipilih tidak ditemukan.';
        }

        $stok = filter_var($data['stok'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 1000000],
        ]);

        if ($stok === false) {
            $errors['stok'] = 'Stok harus bilangan bulat antara 0 dan 1.000.000.';
        }

        $harga = filter_var($data['harga'], FILTER_VALIDATE_FLOAT);

        if ($harga === false || $harga < 0) {
            $errors['harga'] = 'Harga harus berupa angka 0 atau lebih.';
        } elseif ($harga > 9999999999.99) {
            $errors['harga'] = 'Harga maksimal Rp 9.999.999.999,99.';
        }

        if (mb_strlen($data['deskripsi']) > 5000) {
            $errors['deskripsi'] = 'Deskripsi maksimal 5.000 karakter.';
        }

        return $errors;
    }

    private function flashLowStock(string $nama, int $stok): void
    {
        if ($stok === 0) {
            flash('warning', 'Stok barang "' . $nama . '" habis. Segera lakukan restock.');
            return;
        }

        if ($stok <= Barang::STOK_MENIPIS) {
            flash(
                'warning',
                'Stok barang "' . $nama . '" menipis (' . $stok . '). Pertimbangkan untuk restock.'
            );
        }
    }

    private function parseId(string $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($id === false) {
            $this->notFound('Barang yang Anda cari tidak ditemukan.');
        }

        return $id;
    }

    /**
     * @return array<string, mixed>
     */
    private function findOrFail(int $id): array
    {
        $barang = $this->barang->findWithKategori($id);

        if ($barang === null) {
            $this->notFound('Barang yang Anda cari tidak ditemukan.');
        }

        return $barang;
    }

    /**
     * @return array{q: string, kategori: int|null, status: string, sort: string, dir: string}
     */
    private function filters(): array
    {
        $kategori = filter_var($this->queryString('kategori'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        $status = $this->queryString('status');
        $sort = $this->queryString('sort', 'created_at');
        $dir = strtoupper($this->queryString('dir', 'DESC'));

        return [
            'q' => $this->queryString('q'),
            'kategori' => $kategori === false ? null : $kategori,
            'status' => in_array($status, ['habis', 'menipis', 'aman', 'restock'], true) ? $status : '',
            'sort' => in_array($sort, ['created_at', 'nama', 'stok', 'harga'], true) ? $sort : 'created_at',
            'dir' => $dir === 'ASC' ? 'ASC' : 'DESC',
        ];
    }

    private function currentPage(): int
    {
        $page = filter_var($this->queryString('page', '1'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        return $page === false ? 1 : $page;
    }

    private function queryString(string $key, string $default = ''): string
    {
        $value = $_GET[$key] ?? $default;

        return is_string($value) ? trim($value) : $default;
    }

    private function csvValue(mixed $value): string
    {
        $text = (string) $value;

        return preg_match('/^[=+\-@]/', $text) === 1 ? "'" . $text : $text;
    }
}
