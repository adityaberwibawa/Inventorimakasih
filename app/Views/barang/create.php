<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var array<int, array<string, mixed>> $kategori */
?>
<div class="mb-6">
    <a href="<?= url('/barang') ?>" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
        &larr; Kembali ke daftar barang
    </a>
    <h1 class="mt-2 text-2xl font-bold text-slate-900">Tambah Barang</h1>
    <p class="mt-1 text-sm text-slate-500">Isi data barang. Kolom bertanda * wajib diisi.</p>
</div>

<div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <?php
    $formAction = url('/barang/simpan');
    $submitLabel = 'Simpan Barang';
    require APP_PATH . '/Views/barang/_form.php';
    ?>
</div>
