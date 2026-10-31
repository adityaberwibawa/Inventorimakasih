<?php
/** @var array<int, array<string, mixed>> $barang */
/** @var array{items: array<int, array<string, mixed>>, total: int, page: int, perPage: int, totalPages: int} $pagination */
/** @var array{q: string, kategori: int|null, status: string, sort: string, dir: string} $filters */
/** @var array<int, array<string, mixed>> $kategori */
/** @var array{total_barang: int, total_kategori: int, nilai_stok: float, perlu_restock: int} $stats */
/** @var int $lowStockThreshold */

$hasFilter = $filters['q'] !== ''
    || $filters['kategori'] !== null
    || $filters['status'] !== ''
    || $filters['sort'] !== 'created_at'
    || $filters['dir'] !== 'DESC';

$statusBadge = static function (int $stok) use ($lowStockThreshold): array {
    if ($stok === 0) {
        return ['label' => 'Habis', 'class' => 'bg-rose-50 text-rose-700 ring-rose-200'];
    }

    if ($stok <= $lowStockThreshold) {
        return ['label' => 'Menipis', 'class' => 'bg-amber-50 text-amber-700 ring-amber-200'];
    }

    return ['label' => 'Aman', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'];
};

$pageUrl = static function (int $page) use ($filters): string {
    return url('/barang') . '?' . http_build_query(array_merge($filters, ['page' => $page]));
};

$exportUrl = url('/barang/export') . '?' . http_build_query($filters);
$resetUrl = url('/barang');
$restockUrl = url('/barang') . '?' . http_build_query(['status' => 'restock']);
$startNumber = ($pagination['page'] - 1) * $pagination['perPage'] + 1;
$endNumber = $startNumber + count($barang) - 1;
?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Daftar Barang</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau stok, nilai inventaris, dan barang yang perlu di-restock.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="<?= e($exportUrl) ?>"
           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            Export CSV
        </a>
        <a href="<?= url('/barang/tambah') ?>"
           class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            + Tambah Barang
        </a>
    </div>
</div>

<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Barang</p>
        <p class="mt-2 text-2xl font-bold text-slate-900"><?= (int) $stats['total_barang'] ?></p>
        <p class="mt-1 text-xs text-slate-500">Item terdaftar</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Kategori</p>
        <p class="mt-2 text-2xl font-bold text-slate-900"><?= (int) $stats['total_kategori'] ?></p>
        <p class="mt-1 text-xs text-slate-500">Kategori aktif</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nilai Stok</p>
        <p class="mt-2 text-2xl font-bold text-slate-900"><?= rupiah($stats['nilai_stok']) ?></p>
        <p class="mt-1 text-xs text-slate-500">Stok &times; harga</p>
    </div>
    <a href="<?= e($restockUrl) ?>"
       class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm transition hover:border-amber-300 hover:bg-amber-100">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Perlu Restock</p>
        <p class="mt-2 text-2xl font-bold text-amber-900"><?= (int) $stats['perlu_restock'] ?></p>
        <p class="mt-1 text-xs text-amber-700">Stok &le; <?= (int) $lowStockThreshold ?></p>
    </a>
</div>

<?php if ($stats['perlu_restock'] > 0): ?>
    <div class="mb-6 flex flex-col gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 sm:flex-row sm:items-center sm:justify-between">
        <p>
            <span class="font-semibold"><?= (int) $stats['perlu_restock'] ?> barang</span>
            memiliki stok menipis atau habis.
        </p>
        <a href="<?= e($restockUrl) ?>" class="font-semibold underline underline-offset-2">Lihat barang</a>
    </div>
<?php endif; ?>

<form action="<?= url('/barang') ?>" method="get" class="mb-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="grid gap-3 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <label for="q" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Cari barang</label>
            <input
                type="search"
                id="q"
                name="q"
                value="<?= e($filters['q']) ?>"
                placeholder="Nama, deskripsi, atau kategori..."
                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >
        </div>

        <div class="lg:col-span-3">
            <label for="kategori" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</label>
            <select
                id="kategori"
                name="kategori"
                data-auto-submit
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >
                <option value="">Semua kategori</option>
                <?php foreach ($kategori as $item): ?>
                    <option value="<?= (int) $item['id'] ?>" <?= $filters['kategori'] === (int) $item['id'] ? 'selected' : '' ?>>
                        <?= e($item['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="lg:col-span-2">
            <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status stok</label>
            <select
                id="status"
                name="status"
                data-auto-submit
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >
                <option value="">Semua status</option>
                <option value="habis" <?= $filters['status'] === 'habis' ? 'selected' : '' ?>>Habis (0)</option>
                <option value="menipis" <?= $filters['status'] === 'menipis' ? 'selected' : '' ?>>Menipis (1&ndash;<?= (int) $lowStockThreshold ?>)</option>
                <option value="aman" <?= $filters['status'] === 'aman' ? 'selected' : '' ?>>Aman (&gt; <?= (int) $lowStockThreshold ?>)</option>
                <option value="restock" <?= $filters['status'] === 'restock' ? 'selected' : '' ?>>Perlu restock (&le; <?= (int) $lowStockThreshold ?>)</option>
            </select>
        </div>

        <div class="lg:col-span-2">
            <label for="sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Urutkan</label>
            <select
                id="sort"
                name="sort"
                data-auto-submit
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >
                <option value="created_at" <?= $filters['sort'] === 'created_at' ? 'selected' : '' ?>>Terbaru</option>
                <option value="nama" <?= $filters['sort'] === 'nama' ? 'selected' : '' ?>>Nama</option>
                <option value="stok" <?= $filters['sort'] === 'stok' ? 'selected' : '' ?>>Stok</option>
                <option value="harga" <?= $filters['sort'] === 'harga' ? 'selected' : '' ?>>Harga</option>
            </select>
        </div>

        <div class="lg:col-span-1">
            <label for="dir" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Arah</label>
            <select
                id="dir"
                name="dir"
                data-auto-submit
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >
                <option value="DESC" <?= $filters['dir'] === 'DESC' ? 'selected' : '' ?>>Turun</option>
                <option value="ASC" <?= $filters['dir'] === 'ASC' ? 'selected' : '' ?>>Naik</option>
            </select>
        </div>
    </div>

    <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-slate-500">
            <?php if ($pagination['total'] === 0): ?>
                Tidak ada barang yang cocok.
            <?php else: ?>
                Menampilkan <?= (int) $startNumber ?>&ndash;<?= (int) $endNumber ?> dari <?= (int) $pagination['total'] ?> barang.
            <?php endif; ?>
        </p>
        <div class="flex gap-2">
            <?php if ($hasFilter): ?>
                <a href="<?= e($resetUrl) ?>"
                   class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Reset
                </a>
            <?php endif; ?>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-900">
                Terapkan
            </button>
        </div>
    </div>
</form>

<?php if ($barang === []): ?>
    <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
        <p class="text-base font-semibold text-slate-800">
            <?= $hasFilter ? 'Tidak ada barang yang cocok.' : 'Belum ada barang.' ?>
        </p>
        <p class="mt-1 text-sm text-slate-500">
            <?= $hasFilter
                ? 'Coba ubah kata kunci atau reset filter untuk melihat semua barang.'
                : 'Tambahkan barang pertama untuk mulai mengelola inventaris.' ?>
        </p>
        <div class="mt-5 flex justify-center gap-2">
            <?php if ($hasFilter): ?>
                <a href="<?= e($resetUrl) ?>"
                   class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Reset filter
                </a>
            <?php else: ?>
                <a href="<?= url('/barang/tambah') ?>"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    + Tambah Barang
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[900px] divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-4 py-3">No</th>
                        <th scope="col" class="px-4 py-3">Kode</th>
                        <th scope="col" class="px-4 py-3">Barang</th>
                        <th scope="col" class="px-4 py-3">Kategori</th>
                        <th scope="col" class="px-4 py-3">Stok</th>
                        <th scope="col" class="px-4 py-3">Harga</th>
                        <th scope="col" class="px-4 py-3">Nilai</th>
                        <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($barang as $index => $item): ?>
                        <?php
                        $stok = (int) $item['stok'];
                        $badge = $statusBadge($stok);
                        ?>
                        <tr class="align-top transition hover:bg-slate-50">
                            <td class="px-4 py-4 text-slate-500"><?= (int) $startNumber + $index ?></td>
                            <td class="px-4 py-4">
                                <span class="rounded-md bg-slate-100 px-2 py-1 font-mono text-xs font-semibold text-slate-600">
                                    BRG-<?= str_pad((string) (int) $item['id'], 4, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900"><?= e($item['nama']) ?></p>
                                <?php if (($item['deskripsi'] ?? '') !== '' && $item['deskripsi'] !== null): ?>
                                    <p class="mt-1 max-w-xs truncate text-xs text-slate-500" title="<?= e($item['deskripsi']) ?>">
                                        <?= e($item['deskripsi']) ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600"><?= e($item['kategori_nama']) ?></td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset <?= $badge['class'] ?>">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    <?= $stok ?> &middot; <?= e($badge['label']) ?>
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-600"><?= rupiah($item['harga']) ?></td>
                            <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-800">
                                <?= rupiah($stok * (float) $item['harga']) ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('/barang/edit/' . (int) $item['id']) ?>"
                                       class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                        Edit
                                    </a>
                                    <form action="<?= url('/barang/hapus') ?>" method="post"
                                          onsubmit="return confirm('Hapus barang ini? Tindakan ini tidak dapat dibatalkan.');">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                        <button type="submit"
                                                class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="divide-y divide-slate-100 md:hidden">
            <?php foreach ($barang as $item): ?>
                <?php
                $stok = (int) $item['stok'];
                $badge = $statusBadge($stok);
                ?>
                <article class="space-y-3 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-mono text-[11px] font-semibold text-slate-400">
                                BRG-<?= str_pad((string) (int) $item['id'], 4, '0', STR_PAD_LEFT) ?>
                            </p>
                            <h2 class="mt-0.5 truncate font-semibold text-slate-900"><?= e($item['nama']) ?></h2>
                            <p class="mt-0.5 text-xs text-slate-500"><?= e($item['kategori_nama']) ?></p>
                        </div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset <?= $badge['class'] ?>">
                            <?= $stok ?> &middot; <?= e($badge['label']) ?>
                        </span>
                    </div>

                    <?php if (($item['deskripsi'] ?? '') !== '' && $item['deskripsi'] !== null): ?>
                        <p class="line-clamp-2 text-xs text-slate-500"><?= e($item['deskripsi']) ?></p>
                    <?php endif; ?>

                    <dl class="grid grid-cols-2 gap-2 rounded-lg bg-slate-50 px-3 py-2 text-xs">
                        <div>
                            <dt class="text-slate-400">Harga</dt>
                            <dd class="mt-0.5 font-semibold text-slate-700"><?= rupiah($item['harga']) ?></dd>
                        </div>
                        <div>
                            <dt class="text-slate-400">Nilai stok</dt>
                            <dd class="mt-0.5 font-semibold text-slate-700"><?= rupiah($stok * (float) $item['harga']) ?></dd>
                        </div>
                    </dl>

                    <div class="flex gap-2">
                        <a href="<?= url('/barang/edit/' . (int) $item['id']) ?>"
                           class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                            Edit
                        </a>
                        <form action="<?= url('/barang/hapus') ?>" method="post" class="flex-1"
                              onsubmit="return confirm('Hapus barang ini? Tindakan ini tidak dapat dibatalkan.');">
                            <?= csrf_input() ?>
                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                            <button type="submit"
                                    class="w-full rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                Hapus
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($pagination['totalPages'] > 1): ?>
        <nav class="mt-4 flex flex-col items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm sm:flex-row"
             aria-label="Navigasi halaman">
            <p class="text-slate-500">
                Halaman <span class="font-semibold text-slate-800"><?= (int) $pagination['page'] ?></span>
                dari <?= (int) $pagination['totalPages'] ?>
            </p>

            <div class="flex flex-wrap items-center gap-1">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="<?= e($pageUrl($pagination['page'] - 1)) ?>"
                       class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        Sebelumnya
                    </a>
                <?php endif; ?>

                <?php
                $startPage = max(1, $pagination['page'] - 2);
                $endPage = min($pagination['totalPages'], $pagination['page'] + 2);
                ?>
                <?php for ($page = $startPage; $page <= $endPage; $page++): ?>
                    <a href="<?= e($pageUrl($page)) ?>"
                       class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border px-2 text-xs font-semibold transition <?= $page === $pagination['page'] ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-200 text-slate-600 hover:bg-slate-50' ?>"
                       <?= $page === $pagination['page'] ? 'aria-current="page"' : '' ?>>
                        <?= $page ?>
                    </a>
                <?php endfor; ?>

                <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                    <a href="<?= e($pageUrl($pagination['page'] + 1)) ?>"
                       class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                        Berikutnya
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php endif; ?>
