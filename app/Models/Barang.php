<?php

declare(strict_types=1);

namespace App\Models;

final class Barang extends Model
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function allWithKategori(): array
    {
        return $this->fetchAll(
            'SELECT b.id, b.nama, b.kategori_id, b.stok, b.harga, b.deskripsi, b.created_at,
                    k.nama AS kategori_nama
             FROM barang b
             INNER JOIN kategori k ON k.id = b.kategori_id
             ORDER BY b.created_at DESC, b.id DESC'
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findWithKategori(int $id): ?array
    {
        return $this->fetch(
            'SELECT b.id, b.nama, b.kategori_id, b.stok, b.harga, b.deskripsi, b.created_at,
                    k.nama AS kategori_nama
             FROM barang b
             INNER JOIN kategori k ON k.id = b.kategori_id
             WHERE b.id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    /**
     * @param array{nama: string, kategori_id: int, stok: int, harga: string, deskripsi: string} $data
     */
    public function create(array $data): bool
    {
        return $this->execute(
            'INSERT INTO barang (nama, kategori_id, stok, harga, deskripsi)
             VALUES (:nama, :kategori_id, :stok, :harga, :deskripsi)',
            [
                'nama' => $data['nama'],
                'kategori_id' => $data['kategori_id'],
                'stok' => $data['stok'],
                'harga' => $data['harga'],
                'deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            ]
        );
    }

    /**
     * @param array{nama: string, kategori_id: int, stok: int, harga: string, deskripsi: string} $data
     */
    public function update(int $id, array $data): bool
    {
        return $this->execute(
            'UPDATE barang
             SET nama = :nama,
                 kategori_id = :kategori_id,
                 stok = :stok,
                 harga = :harga,
                 deskripsi = :deskripsi
             WHERE id = :id',
            [
                'id' => $id,
                'nama' => $data['nama'],
                'kategori_id' => $data['kategori_id'],
                'stok' => $data['stok'],
                'harga' => $data['harga'],
                'deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            ]
        );
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM barang WHERE id = :id', ['id' => $id]);
    }
}
