<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var array<int, array<string, mixed>> $kategori */
/** @var string $formAction */
/** @var string $submitLabel */

$namaError = $errors['nama'] ?? null;
$kategoriError = $errors['kategori_id'] ?? null;
$stokError = $errors['stok'] ?? null;
$hargaError = $errors['harga'] ?? null;
$deskripsiError = $errors['deskripsi'] ?? null;
$tanpaKategori = $kategori === [];
?>
<?php if ($tanpaKategori): ?>
    <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        Belum ada kategori.
        <a href="<?= url('/kategori/tambah') ?>" class="font-semibold underline underline-offset-2">Tambahkan kategori</a>
        terlebih dahulu sebelum membuat barang.
    </div>
<?php endif; ?>

<form action="<?= e($formAction) ?>" method="post" class="space-y-5" novalidate data-submit-loading>
    <?= csrf_input() ?>
    <?php if (isset($old['id'])): ?>
        <input type="hidden" name="id" value="<?= (int) $old['id'] ?>">
    <?php endif; ?>

    <div>
        <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Nama Barang <span class="text-rose-500" aria-hidden="true">*</span>
        </label>
        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= e($old['nama'] ?? '') ?>"
            maxlength="150"
            required
            autofocus
            class="w-full rounded-lg border px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:ring-2 <?= $namaError !== null ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-100' ?>"
            <?= $namaError !== null ? 'aria-invalid="true" aria-describedby="nama-error"' : '' ?>
        >
        <?php if ($namaError !== null): ?>
            <p id="nama-error" class="mt-1.5 text-sm text-rose-600"><?= e($namaError) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="kategori_id" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Kategori <span class="text-rose-500" aria-hidden="true">*</span>
        </label>
        <select
            id="kategori_id"
            name="kategori_id"
            required
            class="w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:ring-2 <?= $kategoriError !== null ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-100' ?>"
            <?= $tanpaKategori ? 'disabled' : '' ?>
            <?= $kategoriError !== null ? 'aria-invalid="true" aria-describedby="kategori-error"' : '' ?>
        >
            <option value="">Pilih kategori</option>
            <?php foreach ($kategori as $item): ?>
                <option value="<?= (int) $item['id'] ?>"
                    <?= (string) ($old['kategori_id'] ?? '') === (string) $item['id'] ? 'selected' : '' ?>>
                    <?= e($item['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if ($kategoriError !== null): ?>
            <p id="kategori-error" class="mt-1.5 text-sm text-rose-600"><?= e($kategoriError) ?></p>
        <?php endif; ?>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="stok" class="mb-1.5 block text-sm font-semibold text-slate-700">
                Stok <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <div class="flex items-center gap-2">
                <button type="button"
                        data-stok-minus
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-white text-lg font-semibold text-slate-600 transition hover:bg-slate-50"
                        aria-label="Kurangi stok">
                    &minus;
                </button>
                <input
                    type="number"
                    id="stok"
                    name="stok"
                    value="<?= e($old['stok'] ?? '0') ?>"
                    min="0"
                    max="1000000"
                    step="1"
                    required
                    class="h-11 min-w-0 flex-1 rounded-lg border px-3.5 text-center text-sm shadow-sm outline-none transition focus:ring-2 <?= $stokError !== null ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-100' ?>"
                    <?= $stokError !== null ? 'aria-invalid="true" aria-describedby="stok-error"' : '' ?>
                >
                <button type="button"
                        data-stok-plus
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-white text-lg font-semibold text-slate-600 transition hover:bg-slate-50"
                        aria-label="Tambah stok">
                    +
                </button>
            </div>
            <?php if ($stokError !== null): ?>
                <p id="stok-error" class="mt-1.5 text-sm text-rose-600"><?= e($stokError) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="harga" class="mb-1.5 block text-sm font-semibold text-slate-700">
                Harga <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">Rp</span>
                <input
                    type="number"
                    id="harga"
                    name="harga"
                    value="<?= e($old['harga'] ?? '0') ?>"
                    min="0"
                    max="9999999999.99"
                    step="0.01"
                    required
                    class="w-full rounded-lg border px-3.5 py-2.5 pl-10 text-sm shadow-sm outline-none transition focus:ring-2 <?= $hargaError !== null ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-100' ?>"
                    <?= $hargaError !== null ? 'aria-invalid="true" aria-describedby="harga-error"' : '' ?>
                >
            </div>
            <?php if ($hargaError !== null): ?>
                <p id="harga-error" class="mt-1.5 text-sm text-rose-600"><?= e($hargaError) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Deskripsi <span class="font-normal text-slate-400">(opsional)</span>
        </label>
        <textarea
            id="deskripsi"
            name="deskripsi"
            rows="4"
            maxlength="5000"
            data-char-count
            class="w-full rounded-lg border px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:ring-2 <?= $deskripsiError !== null ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-100' ?>"
            <?= $deskripsiError !== null ? 'aria-invalid="true" aria-describedby="deskripsi-error"' : '' ?>
        ><?= e($old['deskripsi'] ?? '') ?></textarea>
        <div class="mt-1.5 flex items-center justify-between gap-3">
            <?php if ($deskripsiError !== null): ?>
                <p id="deskripsi-error" class="text-sm text-rose-600"><?= e($deskripsiError) ?></p>
            <?php else: ?>
                <span></span>
            <?php endif; ?>
            <p class="text-xs text-slate-400"><span data-char-output><?= mb_strlen((string) ($old['deskripsi'] ?? '')) ?></span>/5000</p>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
        <a href="<?= url('/barang') ?>"
           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                <?= $tanpaKategori ? 'disabled' : '' ?>>
            <?= e($submitLabel) ?>
        </button>
    </div>
</form>
