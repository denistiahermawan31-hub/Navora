<?php
// NAVORA — app/views/layouts/header.php
// Shared document head and opening shell for both layouts:
//   $nvLayout = 'public'  → landing page
//   $nvLayout = 'app'     → authenticated application
//
// Optional variables (all defaulted below):
//   $pageTitle, $pageDescription, $nvPageTitle, $nvLayout, $nvActiveNav,
//   $nvCurrentUser, $nvFlash, $csrfToken.

require_once __DIR__ . '/main.php';

$nvLayout = isset($nvLayout) && in_array($nvLayout, ['public', 'auth'], true) ? (string) $nvLayout : 'app';
$nvActiveNav = isset($nvActiveNav) ? (string) $nvActiveNav : '';

$nvPageTitle = trim((string) ($nvPageTitle ?? ($pageTitle ?? 'NAVORA')));
if ($nvPageTitle === '') {
    $nvPageTitle = 'NAVORA';
}
$nvDocumentTitle = trim((string) ($nvDocumentTitle ?? ''));
if ($nvDocumentTitle === '') {
    $nvDocumentTitle = $nvPageTitle === 'NAVORA' ? 'NAVORA' : $nvPageTitle . ' — NAVORA';
}

$nvPageDescription = trim((string) ($nvPageDescription ?? ($pageDescription ?? '')));
if ($nvPageDescription === '') {
    $nvPageDescription = 'NAVORA membantu mencatat tabungan untuk satu tujuan yang jelas: target harga, catatan setoran, dan progres yang mudah dibaca.';
}

$nvCurrentUser = $nvCurrentUser ?? ($currentUser ?? ($user ?? null));
$nvFlash = $nvFlash ?? ($flash ?? null);
$nvCsrf = $nvCsrf ?? ($csrfToken ?? ($csrf ?? null));

if ($nvActiveNav === '' && $nvLayout === 'app') {
    $currentPath = nv_current_path();
    if (strpos($currentPath, '/goals') === 0) {
        $nvActiveNav = 'goals';
    } elseif (strpos($currentPath, '/arsip') === 0) {
        $nvActiveNav = 'archive';
    } elseif (strpos($currentPath, '/dashboard') === 0) {
        $nvActiveNav = 'dashboard';
    }
}

$nvIsPublic = $nvLayout === 'public';
$nvBodyClass = $nvIsPublic ? 'nv-public' : ($nvLayout === 'auth' ? 'nv-auth-page' : 'nv-app');
?>
<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= nv_e($nvPageDescription) ?>">
    <meta name="color-scheme" content="light">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='9' fill='none' stroke='%232563eb' stroke-width='1.75'/%3E%3Ccircle cx='12' cy='12' r='2.5' fill='%232563eb'/%3E%3C/svg%3E">
    <title><?= nv_e($nvDocumentTitle) ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="<?= nv_e($nvBodyClass) ?>">
<a class="nv-skip-link" href="#nv-main">Lewati ke konten utama</a>
<?php if ($nvIsPublic): ?>
    <header class="nv-site-header" aria-label="Header utama">
        <div class="nv-container nv-site-header__inner">
            <a class="nv-brand" href="/" aria-label="NAVORA — Beranda">
                <?= nv_logo_mark(24) ?>
                <span class="nv-wordmark__text">NAVORA</span>
            </a>

            <nav class="nv-site-nav" aria-label="Navigasi halaman">
                <a href="#tentang">Tentang</a>
                <a href="#fitur">Fitur</a>
                <a href="#cara-kerja">Cara Kerja</a>
            </nav>

            <div class="nv-site-cta">
                <a class="nv-btn nv-btn--secondary" href="/masuk">Masuk</a>
                <a class="nv-btn nv-btn--primary" href="/daftar">Daftar</a>
                <button
                    type="button"
                    class="nv-iconbtn nv-menu-toggle"
                    data-nv-drawer-open="nv-public-drawer"
                    aria-controls="nv-public-drawer"
                    aria-expanded="false"
                    aria-label="Buka menu"
                ><?= nv_icon('menu', 22) ?></button>
            </div>
        </div>
    </header>

    <div class="nv-drawer-backdrop" data-nv-drawer-close="nv-public-drawer" hidden></div>
    <aside class="nv-drawer" id="nv-public-drawer" role="dialog" aria-modal="true" aria-labelledby="nv-public-drawer-title" hidden>
        <div class="nv-drawer__head">
            <span class="nv-drawer__title" id="nv-public-drawer-title">Menu</span>
            <button type="button" class="nv-iconbtn nv-iconbtn--sm" data-nv-drawer-close="nv-public-drawer" aria-label="Tutup menu">
                <?= nv_icon('close', 20) ?>
            </button>
        </div>
        <nav class="nv-nav nv-nav--drawer" aria-label="Navigasi halaman">
            <a class="nv-nav__item" href="#tentang">Tentang</a>
            <a class="nv-nav__item" href="#fitur">Fitur</a>
            <a class="nv-nav__item" href="#cara-kerja">Cara Kerja</a>
        </nav>
        <div class="nv-drawer__divider" aria-hidden="true"></div>
        <div class="nv-drawer__cta">
            <a class="nv-btn nv-btn--secondary nv-btn--full" href="/masuk">Masuk</a>
            <a class="nv-btn nv-btn--primary nv-btn--full" href="/daftar">Daftar</a>
        </div>
    </aside>

    <main id="nv-main" tabindex="-1">
<?php elseif ($nvLayout === 'auth'): ?>
    <div class="nv-auth">
        <header class="nv-auth__header">
            <a class="nv-brand" href="/" aria-label="NAVORA — Beranda">
                <?= nv_logo_mark(24) ?>
                <span class="nv-wordmark__text">NAVORA</span>
            </a>
        </header>
        <main id="nv-main" class="nv-auth__main" tabindex="-1">
<?php else: ?>
    <div class="nv-shell">
        <?php require __DIR__ . '/sidebar.php'; ?>

        <div class="nv-main">
            <header class="nv-topbar">
                <div class="nv-topbar__left">
                    <button
                        type="button"
                        class="nv-iconbtn nv-topbar__menu"
                        data-nv-drawer-open="nv-nav-drawer"
                        aria-controls="nv-nav-drawer"
                        aria-expanded="false"
                        aria-label="Buka menu navigasi"
                    ><?= nv_icon('menu', 22) ?></button>
                    <span class="nv-topbar__title"><?= nv_e($nvPageTitle) ?></span>
                </div>
                <div class="nv-topbar__actions">
                    <a class="nv-iconbtn" href="/dashboard" aria-label="Ke dashboard">
                        <?= nv_icon('dashboard', 20) ?>
                    </a>
                </div>
            </header>

            <main id="nv-main" class="nv-content" tabindex="-1">
                <?= nv_flash($nvFlash) ?>
<?php endif; ?>
