<?php
/** @var array<int, array<string, mixed>> $kategori */
?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Daftar Kategori</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola kategori yang digunakan untuk mengelompokkan barang.</p>
    </div>
    <a href="<?= url('/kategori/tambah') ?>"
       class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        + Tambah Kategori
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th scope="col" class="px-4 py-3">No</th>
                    <th scope="col" class="px-4 py-3">Nama Kategori</th>
                    <th scope="col" class="px-4 py-3">Deskripsi</th>
                    <th scope="col" class="px-4 py-3 text-center">Jumlah Barang</th>
                    <th scope="col" class="px-4 py-3">Dibuat</th>
                    <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if ($kategori === []): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <p class="font-medium text-slate-700">Belum ada kategori.</p>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan kategori pertama untuk mulai mengelompokkan barang.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kategori as $index => $item): ?>
                        <tr class="align-top transition hover:bg-slate-50">
                            <td class="px-4 py-4 text-slate-500"><?= $index + 1 ?></td>
                            <td class="px-4 py-4 font-semibold text-slate-900"><?= e($item['nama']) ?></td>
                            <td class="max-w-xs px-4 py-4 text-slate-600">
                                <?php if (($item['deskripsi'] ?? '') !== '' && $item['deskripsi'] !== null): ?>
                                    <?= e($item['deskripsi']) ?>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex min-w-8 justify-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                    <?= (int) $item['jumlah_barang'] ?>
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-500">
                                <?= e(date('d/m/Y', strtotime((string) $item['created_at']))) ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('/kategori/edit/' . (int) $item['id']) ?>"
                                       class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                        Edit
                                    </a>
                                    <form action="<?= url('/kategori/hapus') ?>" method="post"
                                          onsubmit="return confirm('Hapus kategori ini? Tindakan ini tidak dapat dibatalkan.');">
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
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
