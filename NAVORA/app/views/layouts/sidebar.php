<?php
// NAVORA — app/views/layouts/sidebar.php
// Desktop sidebar + mobile drawer navigation (CMP-02 / CMP-03).
// Included by header.php only for the authenticated app shell.

$nvActiveNav = $nvActiveNav ?? '';

$navItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => '/dashboard', 'icon' => 'dashboard'],
    ['key' => 'goals', 'label' => 'Goals', 'href' => '/goals', 'icon' => 'target'],
    ['key' => 'archive', 'label' => 'Arsip', 'href' => '/arsip', 'icon' => 'archive'],
];

$userName = '';
$userEmail = '';
if (is_array($nvCurrentUser ?? null)) {
    $userName = trim((string) ($nvCurrentUser['name'] ?? ''));
    $userEmail = trim((string) ($nvCurrentUser['email'] ?? ''));
} elseif (is_object($nvCurrentUser ?? null)) {
    $userName = trim((string) ($nvCurrentUser->name ?? ''));
    $userEmail = trim((string) ($nvCurrentUser->email ?? ''));
}

$accountTitle = $userName !== '' ? $userName : 'Akun pribadi';
$accountSubtitle = $userEmail !== '' ? $userEmail : 'Data tabungan Anda';
$initials = '';
if ($userName !== '') {
    $parts = preg_split('/\s+/', $userName) ?: [];
    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }
        $initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
        if (strlen($initials) >= 2) {
            break;
        }
    }
    $initials = function_exists('mb_strtoupper') ? mb_strtoupper($initials) : strtoupper($initials);
}

$renderNav = static function (array $items, string $active): string {
    $html = '';
    foreach ($items as $item) {
        $isActive = $active === $item['key'];
        $html .= '<a class="nv-nav__item' . ($isActive ? ' is-active' : '') . '" href="' . nv_e($item['href']) . '"'
            . ($isActive ? ' aria-current="page"' : '') . '>'
            . nv_icon($item['icon'], 20, 'nv-nav__icon')
            . '<span class="nv-nav__label">' . nv_e($item['label']) . '</span>'
            . '</a>';
    }
    return $html;
};

$accountHtml = '<div class="nv-account">'
    . '<span class="nv-account__avatar" aria-hidden="true">'
    . ($initials !== '' ? nv_e($initials) : nv_icon('user', 18))
    . '</span>'
    . '<span class="nv-account__text">'
    . '<span class="nv-account__name">' . nv_e($accountTitle) . '</span>'
    . '<span class="nv-account__meta">' . nv_e($accountSubtitle) . '</span>'
    . '</span></div>';

$logoutHtml = '<form class="nv-account__logout" method="post" action="/keluar">'
    . nv_csrf_field($nvCsrf ?? null)
    . '<button type="submit" class="nv-btn nv-btn--ghost nv-btn--full">'
    . nv_icon('logout', 18) . '<span>Keluar</span>'
    . '</button></form>';
?>
<aside class="nv-sidebar" aria-label="Navigasi utama">
    <div class="nv-sidebar__top">
        <a class="nv-brand" href="/dashboard" aria-label="NAVORA — Dashboard">
            <?= nv_logo_mark(22) ?>
            <span class="nv-wordmark__text">NAVORA</span>
        </a>
        <nav class="nv-nav" aria-label="Navigasi aplikasi">
            <?= $renderNav($navItems, $nvActiveNav) ?>
        </nav>
    </div>
    <div class="nv-sidebar__bottom">
        <?= $accountHtml ?>
        <?= $logoutHtml ?>
    </div>
</aside>

<div class="nv-drawer-backdrop" data-nv-drawer-close="nv-nav-drawer" hidden></div>
<aside class="nv-drawer" id="nv-nav-drawer" role="dialog" aria-modal="true" aria-labelledby="nv-drawer-title" hidden>
    <div class="nv-drawer__head">
        <span class="nv-drawer__title" id="nv-drawer-title">Navigasi</span>
        <button type="button" class="nv-iconbtn nv-iconbtn--sm" data-nv-drawer-close aria-label="Tutup menu">
            <?= nv_icon('close', 20) ?>
        </button>
    </div>
    <nav class="nv-nav nv-nav--drawer" aria-label="Navigasi aplikasi">
        <?= $renderNav($navItems, $nvActiveNav) ?>
    </nav>
    <div class="nv-drawer__bottom">
        <?= $accountHtml ?>
        <?= $logoutHtml ?>
    </div>
</aside>
