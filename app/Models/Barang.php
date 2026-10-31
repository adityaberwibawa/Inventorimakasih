<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Barang extends Model
{
    public const STOK_MENIPIS = 5;
    public const PER_PAGE = 10;

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

    /**
     * @param array{q: string, kategori: int|null, status: string, sort: string, dir: string} $filters
     * @return array{
     *     items: array<int, array<string, mixed>>,
     *     total: int,
     *     page: int,
     *     perPage: int,
     *     totalPages: int
     * }
     */
    public function paginate(array $filters, int $page, int $perPage = self::PER_PAGE): array
    {
        [$whereSql, $params] = $this->buildFilter($filters);

        $countRow = $this->fetch(
            'SELECT COUNT(*) AS total
             FROM barang b
             INNER JOIN kategori k ON k.id = b.kategori_id' . $whereSql,
            $params
        );

        $total = (int) ($countRow['total'] ?? 0);
        $perPage = max(1, $perPage);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, $page), $totalPages);
        $offset = ($page - 1) * $perPage;

        $sql = 'SELECT b.id, b.nama, b.kategori_id, b.stok, b.harga, b.deskripsi, b.created_at,
                       k.nama AS kategori_nama
                FROM barang b
                INNER JOIN kategori k ON k.id = b.kategori_id'
            . $whereSql
            . ' ORDER BY ' . $this->orderByClause($filters)
            . ' LIMIT :limit OFFSET :offset';

        $statement = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue(
                ':' . $key,
                $value,
                is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }

        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'items' => $statement->fetchAll(),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ];
    }

    /**
     * @return array{total_barang: int, total_kategori: int, nilai_stok: float, perlu_restock: int}
     */
    public function stats(): array
    {
        $row = $this->fetch(
            'SELECT
                (SELECT COUNT(*) FROM barang) AS total_barang,
                (SELECT COUNT(*) FROM kategori) AS total_kategori,
                (SELECT COALESCE(SUM(stok * harga), 0) FROM barang) AS nilai_stok,
                (SELECT COUNT(*) FROM barang WHERE stok <= :threshold) AS perlu_restock',
            ['threshold' => self::STOK_MENIPIS]
        );

        return [
            'total_barang' => (int) ($row['total_barang'] ?? 0),
            'total_kategori' => (int) ($row['total_kategori'] ?? 0),
            'nilai_stok' => (float) ($row['nilai_stok'] ?? 0),
            'perlu_restock' => (int) ($row['perlu_restock'] ?? 0),
        ];
    }

    /**
     * @param array{q: string, kategori: int|null, status: string, sort: string, dir: string} $filters
     * @return array<int, array<string, mixed>>
     */
    public function allForExport(array $filters): array
    {
        [$whereSql, $params] = $this->buildFilter($filters);

        return $this->fetchAll(
            'SELECT b.id, b.nama, b.kategori_id, b.stok, b.harga, b.deskripsi, b.created_at,
                    k.nama AS kategori_nama
             FROM barang b
             INNER JOIN kategori k ON k.id = b.kategori_id'
            . $whereSql
            . ' ORDER BY ' . $this->orderByClause($filters),
            $params
        );
    }

    /**
     * @param array{q: string, kategori: int|null, status: string, sort: string, dir: string} $filters
     * @return array{0: string, 1: array<string, mixed>}
     */
    private function buildFilter(array $filters): array
    {
        $where = [];
        $params = [];

        if ($filters['q'] !== '') {
            // Escape karakter wildcard LIKE agar dicari sebagai teks biasa.
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $filters['q']);
            $like = '%' . $escaped . '%';
            $where[] = '(b.nama LIKE :q_nama OR b.deskripsi LIKE :q_deskripsi OR k.nama LIKE :q_kategori)';
            $params['q_nama'] = $like;
            $params['q_deskripsi'] = $like;
            $params['q_kategori'] = $like;
        }

        if ($filters['kategori'] !== null) {
            $where[] = 'b.kategori_id = :kategori_id';
            $params['kategori_id'] = $filters['kategori'];
        }

        switch ($filters['status']) {
            case 'habis':
                $where[] = 'b.stok = 0';
                break;
            case 'menipis':
                $where[] = 'b.stok BETWEEN 1 AND ' . self::STOK_MENIPIS;
                break;
            case 'aman':
                $where[] = 'b.stok > ' . self::STOK_MENIPIS;
                break;
            case 'restock':
                $where[] = 'b.stok <= ' . self::STOK_MENIPIS;
                break;
        }

        return [
            $where === [] ? '' : ' WHERE ' . implode(' AND ', $where),
            $params,
        ];
    }

    /**
     * Bangun klausa ORDER BY dari filter yang sudah melalui whitelist di controller.
     *
     * @param array{q: string, kategori: int|null, status: string, sort: string, dir: string} $filters
     */
    private function orderByClause(array $filters): string
    {
        $sortMap = [
            'created_at' => 'b.created_at',
            'nama' => 'b.nama',
            'stok' => 'b.stok',
            'harga' => 'b.harga',
        ];
        $orderBy = $sortMap[$filters['sort']] ?? 'b.created_at';
        $direction = $filters['dir'] === 'ASC' ? 'ASC' : 'DESC';

        return $orderBy . ' ' . $direction . ', b.id DESC';
    }
}
