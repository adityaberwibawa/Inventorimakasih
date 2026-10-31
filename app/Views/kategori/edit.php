<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var array<string, mixed> $kategori */
?>
<div class="mb-6">
    <a href="<?= url('/kategori') ?>" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
        &larr; Kembali ke daftar kategori
    </a>
    <h1 class="mt-2 text-2xl font-bold text-slate-900">Edit Kategori</h1>
    <p class="mt-1 text-sm text-slate-500">Perbarui nama atau deskripsi kategori.</p>
</div>

<div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <p class="mb-5 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
        Dibuat pada <?= e(date('d/m/Y H:i', strtotime((string) $kategori['created_at']))) ?>
    </p>

    <?php
    $formAction = url('/kategori/update');
    $submitLabel = 'Simpan Perubahan';
    require APP_PATH . '/Views/kategori/_form.php';
    ?>
</div>
