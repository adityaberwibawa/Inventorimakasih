<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Kategori;
use PDOException;

final class KategoriController extends Controller
{
    private Kategori $kategori;

    public function __construct()
    {
        $this->kategori = new Kategori();
    }

    public function index(): void
    {
        $this->view('kategori/index', [
            'title' => 'Daftar Kategori',
            'active' => 'kategori',
            'kategori' => $this->kategori->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('kategori/create', [
            'title' => 'Tambah Kategori',
            'active' => 'kategori',
            'errors' => [],
            'old' => ['nama' => '', 'deskripsi' => ''],
        ]);
    }

    public function store(): void
    {
        $data = [
            'nama' => $this->postString('nama'),
            'deskripsi' => $this->postString('deskripsi'),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $this->view('kategori/create', [
                'title' => 'Tambah Kategori',
                'active' => 'kategori',
                'errors' => $errors,
                'old' => $data,
            ]);

            return;
        }

        $this->kategori->create($data);

        flash('success', 'Kategori "' . $data['nama'] . '" berhasil ditambahkan.');
        $this->redirect('/kategori');
    }

    public function edit(string $id): void
    {
        $kategori = $this->findOrFail($this->parseId($id));

        $this->view('kategori/edit', [
            'title' => 'Edit Kategori',
            'active' => 'kategori',
            'errors' => [],
            'kategori' => $kategori,
            'old' => [
                'id' => (int) $kategori['id'],
                'nama' => $kategori['nama'],
                'deskripsi' => $kategori['deskripsi'] ?? '',
            ],
        ]);
    }

    public function update(): void
    {
        $id = $this->parseId($this->postString('id'));
        $kategori = $this->findOrFail($id);

        $data = [
            'id' => $id,
            'nama' => $this->postString('nama'),
            'deskripsi' => $this->postString('deskripsi'),
        ];

        $errors = $this->validate($data);

        if ($errors !== []) {
            $this->view('kategori/edit', [
                'title' => 'Edit Kategori',
                'active' => 'kategori',
                'errors' => $errors,
                'kategori' => $kategori,
                'old' => $data,
            ]);

            return;
        }

        $this->kategori->update($id, $data);

        flash('success', 'Kategori "' . $data['nama'] . '" berhasil diperbarui.');
        $this->redirect('/kategori');
    }

    public function destroy(): void
    {
        $id = $this->parseId($this->postString('id'));
        $kategori = $this->findOrFail($id);

        try {
            $this->kategori->delete($id);
            flash('success', 'Kategori "' . $kategori['nama'] . '" berhasil dihapus.');
        } catch (PDOException $exception) {
            if ((string) $exception->getCode() === '23000') {
                flash(
                    'error',
                    'Kategori "' . $kategori['nama'] . '" tidak dapat dihapus karena masih memiliki barang.'
                );
            } else {
                throw $exception;
            }
        }

        $this->redirect('/kategori');
    }

    private function parseId(string $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($id === false) {
            $this->notFound('Kategori yang Anda cari tidak ditemukan.');
        }

        return $id;
    }

    /**
     * @return array<string, mixed>
     */
    private function findOrFail(int $id): array
    {
        $kategori = $this->kategori->find($id);

        if ($kategori === null) {
            $this->notFound('Kategori yang Anda cari tidak ditemukan.');
        }

        return $kategori;
    }

    /**
     * @param array<string, string> $data
     * @return array<string, string>
     */
    private function validate(array $data): array
    {
        $errors = [];
        $nama = $data['nama'] ?? '';
        $deskripsi = $data['deskripsi'] ?? '';

        if ($nama === '') {
            $errors['nama'] = 'Nama kategori wajib diisi.';
        } elseif (mb_strlen($nama) > 100) {
            $errors['nama'] = 'Nama kategori maksimal 100 karakter.';
        }

        if (mb_strlen($deskripsi) > 5000) {
            $errors['deskripsi'] = 'Deskripsi maksimal 5.000 karakter.';
        }

        return $errors;
    }
}
