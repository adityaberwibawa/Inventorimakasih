<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
?>
<div class="mb-6">
    <a href="<?= url('/kategori') ?>" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
        &larr; Kembali ke daftar kategori
    </a>
    <h1 class="mt-2 text-2xl font-bold text-slate-900">Tambah Kategori</h1>
    <p class="mt-1 text-sm text-slate-500">Isi nama kategori. Deskripsi bersifat opsional.</p>
</div>

<div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <?php
    $formAction = url('/kategori/simpan');
    $submitLabel = 'Simpan Kategori';
    require APP_PATH . '/Views/kategori/_form.php';
    ?>
</div>
