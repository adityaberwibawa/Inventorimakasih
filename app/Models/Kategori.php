<?php

declare(strict_types=1);

namespace App\Models;

final class Kategori extends Model
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT k.id, k.nama, k.deskripsi, k.created_at,
                    (SELECT COUNT(*) FROM barang b WHERE b.kategori_id = k.id) AS jumlah_barang
             FROM kategori k
             ORDER BY k.nama ASC'
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->fetch(
            'SELECT id, nama, deskripsi, created_at
             FROM kategori
             WHERE id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    /**
     * @param array{nama: string, deskripsi: string} $data
     */
    public function create(array $data): bool
    {
        return $this->execute(
            'INSERT INTO kategori (nama, deskripsi)
             VALUES (:nama, :deskripsi)',
            [
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            ]
        );
    }

    /**
     * @param array{nama: string, deskripsi: string} $data
     */
    public function update(int $id, array $data): bool
    {
        return $this->execute(
            'UPDATE kategori
             SET nama = :nama, deskripsi = :deskripsi
             WHERE id = :id',
            [
                'id' => $id,
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            ]
        );
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM kategori WHERE id = :id', ['id' => $id]);
    }
}
