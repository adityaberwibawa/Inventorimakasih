<?php
/** @var int $code */
/** @var string $title */
/** @var string $message */
?>
<div class="mx-auto max-w-xl rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">
    <p class="text-5xl font-black text-indigo-600"><?= (int) $code ?></p>
    <h1 class="mt-3 text-xl font-bold text-slate-900">
        <?= $title ?? ($code === 404 ? 'Halaman Tidak Ditemukan' : 'Terjadi Kesalahan') ?>
    </h1>
    <p class="mt-2 text-sm leading-relaxed text-slate-600"><?= e($message) ?></p>

    <div class="mt-6 flex flex-col justify-center gap-2 sm:flex-row">
        <button type="button" onclick="history.back()"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Kembali
        </button>
        <a href="<?= url('/kategori') ?>"
           class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
            Ke Daftar Kategori
        </a>
    </div>
</div>
