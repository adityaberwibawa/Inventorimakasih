<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var string $formAction */
/** @var string $submitLabel */

$namaError = $errors['nama'] ?? null;
$deskripsiError = $errors['deskripsi'] ?? null;
?>
<form action="<?= e($formAction) ?>" method="post" class="space-y-5" novalidate>
    <?php if (isset($old['id'])): ?>
        <input type="hidden" name="id" value="<?= (int) $old['id'] ?>">
    <?php endif; ?>

    <div>
        <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Nama Kategori <span class="text-rose-500" aria-hidden="true">*</span>
        </label>
        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= e($old['nama'] ?? '') ?>"
            maxlength="100"
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
        <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Deskripsi <span class="font-normal text-slate-400">(opsional)</span>
        </label>
        <textarea
            id="deskripsi"
            name="deskripsi"
            rows="4"
            maxlength="5000"
            class="w-full rounded-lg border px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:ring-2 <?= $deskripsiError !== null ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-100' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-100' ?>"
            <?= $deskripsiError !== null ? 'aria-invalid="true" aria-describedby="deskripsi-error"' : '' ?>
        ><?= e($old['deskripsi'] ?? '') ?></textarea>
        <?php if ($deskripsiError !== null): ?>
            <p id="deskripsi-error" class="mt-1.5 text-sm text-rose-600"><?= e($deskripsiError) ?></p>
        <?php endif; ?>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
        <a href="<?= url('/kategori') ?>"
           class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <?= e($submitLabel) ?>
        </button>
    </div>
</form>
