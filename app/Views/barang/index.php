<?php
/** @var array<int, array<string, mixed>> $barang */
?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Daftar Barang</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola stok, harga, dan kategori barang inventaris.</p>
    </div>
    <a href="<?= url('/barang/tambah') ?>"
       class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        + Tambah Barang
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th scope="col" class="px-4 py-3">No</th>
                    <th scope="col" class="px-4 py-3">Nama Barang</th>
                    <th scope="col" class="px-4 py-3">Kategori</th>
                    <th scope="col" class="px-4 py-3">Stok</th>
                    <th scope="col" class="px-4 py-3">Harga</th>
                    <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if ($barang === []): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <p class="font-medium text-slate-700">Belum ada barang.</p>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan barang pertama untuk mulai mengelola inventaris.</p>
                            <a href="<?= url('/barang/tambah') ?>" class="mt-4 inline-flex text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                + Tambah Barang
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($barang as $index => $item): ?>
                        <tr class="align-top transition hover:bg-slate-50">
                            <td class="px-4 py-4 text-slate-500"><?= $index + 1 ?></td>
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
                                <?php $stok = (int) $item['stok']; ?>
                                <span class="inline-flex min-w-10 justify-center rounded-full px-2.5 py-1 text-xs font-semibold <?= $stok === 0 ? 'bg-rose-50 text-rose-700' : ($stok <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') ?>">
                                    <?= $stok ?>
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-600"><?= rupiah($item['harga']) ?></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('/barang/edit/' . (int) $item['id']) ?>"
                                       class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                        Edit
                                    </a>
                                    <form action="<?= url('/barang/hapus') ?>" method="post"
                                          onsubmit="return confirm('Hapus barang ini? Tindakan ini tidak dapat dibatalkan.');">
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
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
