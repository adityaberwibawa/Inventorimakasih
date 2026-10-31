<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var array<string, mixed> $barang */
/** @var array<int, array<string, mixed>> $kategori */
?>
<div class="mb-6">
    <a href="<?= url('/barang') ?>" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
        &larr; Kembali ke daftar barang
    </a>
    <h1 class="mt-2 text-2xl font-bold text-slate-900">Edit Barang</h1>
    <p class="mt-1 text-sm text-slate-500">Perbarui data barang atau stok.</p>
</div>

<div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    <p class="mb-5 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
        Kode
        <span class="font-mono font-semibold text-slate-800"><?= kode_barang($barang['id']) ?></span>
        &middot; Dibuat pada <?= e(date('d/m/Y H:i', strtotime((string) $barang['created_at']))) ?>
    </p>

    <?php
    $formAction = url('/barang/update');
    $submitLabel = 'Simpan Perubahan';
    require APP_PATH . '/Views/barang/_form.php';
    ?>
</div>
