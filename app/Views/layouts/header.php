<?php
$title = $title ?? 'Inventorimakasih';
$active = $active ?? '';
$flashes = get_flashes();

$flashStyles = [
    'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    'error' => 'border-rose-200 bg-rose-50 text-rose-800',
    'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
    'info' => 'border-sky-200 bg-sky-50 text-sky-800',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> &mdash; Inventorimakasih</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
            <a href="<?= url('/') ?>" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white shadow-sm">
                    I
                </span>
                <span>
                    <span class="block text-base font-bold leading-tight text-slate-900">Inventorimakasih</span>
                    <span class="block text-xs text-slate-500">Sistem Manajemen Inventaris</span>
                </span>
            </a>

            <nav class="flex flex-wrap items-center gap-1 text-sm font-medium" aria-label="Navigasi utama">
                <a href="<?= url('/barang') ?>"
                   class="rounded-lg px-3 py-2 transition <?= $active === 'barang' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                    Barang
                </a>
                <a href="<?= url('/kategori') ?>"
                   class="rounded-lg px-3 py-2 transition <?= $active === 'kategori' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                    Kategori
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-4 py-8">
        <?php foreach ($flashes as $flash): ?>
            <?php
            $flashType = $flash['type'] ?? 'info';
            $flashStyle = $flashStyles[$flashType] ?? $flashStyles['info'];
            ?>
            <div class="mb-4 flex items-start justify-between gap-4 rounded-xl border px-4 py-3 text-sm shadow-sm <?= $flashStyle ?>" data-flash role="alert">
                <span><?= e($flash['message'] ?? '') ?></span>
                <button type="button" class="shrink-0 text-lg leading-none opacity-60 transition hover:opacity-100" data-flash-close aria-label="Tutup pesan">
                    &times;
                </button>
            </div>
        <?php endforeach; ?>
