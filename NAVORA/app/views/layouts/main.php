<?php
// NAVORA — app/views/layouts/main.php
// Shared layout contract + presentation helpers.
//
// This file does not render the shell by itself. header.php requires it,
// then page views call the nv_* helpers below. Keeping the helpers in one
// place prevents duplicated markup for money, progress, badges, fields,
// empty states, pagination, tables, and dialogs.

require_once __DIR__ . '/../../models/Goal.php';
require_once __DIR__ . '/../../models/SavingsTransaction.php';

use NAVORA\Models\Goal;
use NAVORA\Models\SavingsTransaction;

if (!function_exists('nv_e')) {
    function nv_e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('nv_get')) {
    function nv_get(array|object|null $row, string $key, mixed $default = null): mixed
    {
        if (is_array($row) && array_key_exists($key, $row)) {
            return $row[$key];
        }
        if (is_object($row) && isset($row->{$key})) {
            return $row->{$key};
        }
        return $default;
    }
}

if (!function_exists('nv_input_value')) {
    function nv_input_value(array|object|null $values, string $key, mixed $fallback = ''): string
    {
        $value = nv_get($values, $key, null);
        if ($value === null || is_array($value) || is_object($value)) {
            return (string) $fallback;
        }
        return (string) $value;
    }
}

if (!function_exists('nv_csrf_field')) {
    function nv_csrf_field(?string $token): string
    {
        if ($token === null || trim($token) === '') {
            return '';
        }
        return '<input type="hidden" name="_token" value="' . nv_e($token) . '">';
    }
}

if (!function_exists('nv_current_path')) {
    function nv_current_path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        return is_string($path) && $path !== '' ? $path : '/';
    }
}

if (!function_exists('nv_current_url')) {
    function nv_current_url(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return is_string($uri) && $uri !== '' ? $uri : '/';
    }
}

if (!function_exists('nv_icon')) {
    /**
     * Local inline SVG set (Lucide-compatible geometry, DEC-019 Option A).
     * No icon package, no CDN, no emoji.
     */
    function nv_icon(string $name, int $size = 20, string $class = ''): string
    {
        $icons = [
            'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>',
            'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.25"/>',
            'archive' => '<rect x="3" y="4" width="18" height="5" rx="1"/><path d="M5 9v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9"/><path d="M10 13h4"/>',
            'user' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/>',
            'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
            'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
            'close' => '<path d="M18 6 6 18M6 6l12 12"/>',
            'plus' => '<path d="M12 5v14M5 12h14"/>',
            'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
            'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
            'chevron-left' => '<path d="m15 18-6-6 6-6"/>',
            'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
            'arrow-left' => '<path d="M19 12H5"/><path d="m11 18-6-6 6-6"/>',
            'arrow-right' => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
            'arrow-down-left' => '<path d="M17 7 7 17"/><path d="M17 17H7V7"/>',
            'arrow-up-right' => '<path d="M7 17 17 7"/><path d="M7 7h10v10"/>',
            'more-horizontal' => '<path d="M5 12h.01M12 12h.01M19 12h.01" stroke-width="2.75"/>',
            'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
            'check' => '<path d="m20 6-11 11-5-5"/>',
            'alert-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
            'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/>',
            'wallet' => '<path d="M20 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h15v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5"/><path d="M16 13h2"/>',
            'history' => '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l3 2"/>',
            'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/>',
            'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
            'lock' => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
            'eye' => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
            'eye-off' => '<path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.9 10.9 0 0 1 12 5c6.5 0 10 7 10 7a17.8 17.8 0 0 1-3.2 4.1"/><path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.5 7 10 7a10.9 10.9 0 0 0 4.4-.9"/>',
            'refresh' => '<path d="M21 12a9 9 0 0 1-15 6.7L3 16"/><path d="M3 21v-5h5"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 3v5h-5"/>',
            'trend-up' => '<path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/>',
            'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
            'list' => '<path d="M8 6h13M8 12h13M8 18h13"/><path d="M3 6h.01M3 12h.01M3 18h.01"/>',
            'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'coins' => '<circle cx="8" cy="8" r="5"/><path d="M15 3.8a5 5 0 0 1 0 8.4"/><path d="M8 16a8 8 0 0 0 13 3"/><path d="M21 19v-4h-4"/>',
            'folder' => '<path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/>',
            'shield-check' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
            'spinner' => '<path d="M21 12a9 9 0 1 1-6.2-8.6" />',
        ];

        $body = $icons[$name] ?? '<circle cx="12" cy="12" r="9"/>';
        $classAttr = trim('nv-icon ' . $class);

        return '<svg class="' . nv_e($classAttr) . '" width="' . $size . '" height="' . $size
            . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"'
            . ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
            . $body . '</svg>';
    }
}

if (!function_exists('nv_logo_mark')) {
    function nv_logo_mark(int $size = 24, string $class = ''): string
    {
        return '<svg class="' . nv_e(trim('nv-logo-mark ' . $class)) . '" width="' . $size . '" height="' . $size
            . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"'
            . ' stroke-linecap="round" aria-hidden="true" focusable="false">'
            . '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2.5" fill="currentColor" stroke="none"/>'
            . '</svg>';
    }
}

if (!function_exists('nv_wordmark')) {
    function nv_wordmark(string $class = '', bool $compact = false): string
    {
        return '<span class="' . nv_e(trim('nv-wordmark ' . $class)) . '">'
            . nv_logo_mark($compact ? 20 : 22)
            . ($compact ? '' : '<span class="nv-wordmark__text">NAVORA</span>')
            . '</span>';
    }
}

if (!function_exists('nv_money')) {
    function nv_money(int $amount, string $size = 'md', bool $signed = false, string $class = ''): string
    {
        $text = Goal::formatRupiah($amount);
        if ($signed && $amount > 0) {
            $text = '+' . $text;
        }

        $classes = trim('nv-amount nv-amount--' . $size . ' ' . ($amount < 0 ? 'is-negative' : '') . ' ' . $class);

        return '<span class="' . nv_e($classes) . '">' . nv_e($text) . '</span>';
    }
}

if (!function_exists('nv_percent')) {
    function nv_percent(float $value): string
    {
        return Goal::formatPercent(max(0.0, min(100.0, $value)));
    }
}

if (!function_exists('nv_date')) {
    function nv_date(mixed $value): string
    {
        return Goal::formatDate($value);
    }
}

if (!function_exists('nv_date_long')) {
    function nv_date_long(mixed $value): string
    {
        return Goal::formatDateLong($value);
    }
}

if (!function_exists('nv_badge')) {
    function nv_badge(string $label, string $variant = 'neutral', string $class = ''): string
    {
        $allowed = ['info', 'success', 'warning', 'danger', 'neutral'];
        $variant = in_array($variant, $allowed, true) ? $variant : 'neutral';

        return '<span class="' . nv_e(trim('nv-badge nv-badge--' . $variant . ' ' . $class)) . '">'
            . nv_e($label) . '</span>';
    }
}

if (!function_exists('nv_goal_badge')) {
    function nv_goal_badge(array|object $goal): string
    {
        $goal = Goal::present($goal);

        if ($goal['archived']) {
            return nv_badge('Diarsipkan', 'neutral');
        }
        if ($goal['status'] === Goal::STATUS_COMPLETE) {
            return nv_badge('Tercapai', 'success');
        }
        if ($goal['nearlyReached']) {
            return nv_badge('Hampir', 'warning');
        }

        return nv_badge('Berlangsung', 'info');
    }
}

if (!function_exists('nv_direction_badge')) {
    function nv_direction_badge(array|object $transaction): string
    {
        $transaction = SavingsTransaction::present($transaction);

        return $transaction['isDeposit']
            ? nv_badge('Setoran', 'info')
            : ($transaction['isWithdrawal'] ? nv_badge('Penarikan', 'neutral') : nv_badge('Tidak diketahui', 'neutral'));
    }
}

if (!function_exists('nv_progress')) {
    function nv_progress(float $value, string $label = 'Progres', string $size = 'md', string $tone = 'brand', bool $showValue = true): string
    {
        $value = max(0.0, min(100.0, $value));
        $cssValue = rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.');
        if ($cssValue === '') {
            $cssValue = '0';
        }
        $tone = $tone === 'success' ? 'success' : 'brand';
        $percentage = Goal::formatPercent($value);
        $ariaLabel = $label !== '' ? $label : 'Progres tabungan';

        $head = '';
        if ($showValue || $label !== '') {
            $head = '<div class="nv-progress__head">'
                . ($label !== '' ? '<span class="nv-progress__label">' . nv_e($label) . '</span>' : '<span></span>')
                . ($showValue ? '<span class="nv-progress__value">' . nv_e($percentage) . '</span>' : '')
                . '</div>';
        }

        return '<div class="nv-progress nv-progress--' . nv_e($size) . ' nv-progress--' . nv_e($tone) . '" data-nv-progress>'
            . $head
            . '<div class="nv-progress__track" role="progressbar" aria-label="' . nv_e($ariaLabel)
            . '" aria-valuemin="0" aria-valuemax="100" aria-valuenow="' . nv_e($cssValue)
            . '" aria-valuetext="' . nv_e($percentage) . '" style="--nv-progress:' . nv_e($cssValue) . '%">'
            . '<span class="nv-progress__fill" aria-hidden="true"></span>'
            . '</div>'
            . '</div>';
    }
}

if (!function_exists('nv_alert')) {
    function nv_alert(string $type, string $title, string $message, bool $dismissible = true, string $actionsHtml = ''): string
    {
        $map = [
            'success' => ['success', 'check', 'status'],
            'info' => ['info', 'info', 'status'],
            'warning' => ['warning', 'alert-circle', 'alert'],
            'danger' => ['danger', 'alert-circle', 'alert'],
            'error' => ['danger', 'alert-circle', 'alert'],
        ];
        [$variant, $icon, $role] = $map[$type] ?? $map['info'];

        $html = '<div class="nv-alert nv-alert--' . $variant . '" role="' . $role . '">'
            . '<span class="nv-alert__icon" aria-hidden="true">' . nv_icon($icon, 20) . '</span>'
            . '<div class="nv-alert__body">'
            . ($title !== '' ? '<p class="nv-alert__title">' . nv_e($title) . '</p>' : '')
            . '<p class="nv-alert__message">' . nv_e($message) . '</p>'
            . ($actionsHtml !== '' ? '<div class="nv-alert__actions">' . $actionsHtml . '</div>' : '')
            . '</div>';

        if ($dismissible) {
            $html .= '<button type="button" class="nv-alert__close nv-iconbtn nv-iconbtn--sm" data-nv-dismiss aria-label="Tutup pesan">'
                . nv_icon('close', 16) . '</button>';
        }

        return $html . '</div>';
    }
}

if (!function_exists('nv_flash')) {
    function nv_flash(mixed $flash): string
    {
        if ($flash === null || $flash === '' || $flash === []) {
            return '';
        }

        $message = '';
        $type = 'success';

        if (is_string($flash)) {
            $message = $flash;
        } elseif (is_array($flash)) {
            $message = (string) ($flash['message'] ?? $flash['text'] ?? $flash['msg'] ?? '');
            $type = (string) ($flash['type'] ?? $flash['status'] ?? $flash['variant'] ?? 'success');
        } elseif (is_object($flash)) {
            $message = (string) ($flash->message ?? $flash->text ?? '');
            $type = (string) ($flash->type ?? $flash->status ?? 'success');
        }

        $message = trim($message);
        if ($message === '') {
            return '';
        }

        $type = match (strtolower($type)) {
            'success', 'berhasil' => 'success',
            'error', 'danger', 'gagal' => 'danger',
            'warning', 'peringatan' => 'warning',
            default => 'info',
        };

        return nv_alert($type, '', $message, true);
    }
}

if (!function_exists('nv_error_summary')) {
    function nv_error_summary(array|object|null $errors, string $title = 'Periksa kembali isian Anda'): string
    {
        if ($errors === null) {
            return '';
        }

        $items = [];
        foreach ((array) $errors as $field => $message) {
            if (is_array($message)) {
                $message = implode(' ', array_map('strval', $message));
            } elseif (is_object($message)) {
                $message = (string) $message;
            }
            $message = trim((string) $message);
            if ($message === '') {
                continue;
            }

            $key = is_string($field) ? $field : '';
            if ($key !== '' && !in_array($key, ['_form', 'form', 'auth', 'general'], true)) {
                $items[] = '<li><a href="#nv-field-' . nv_e(preg_replace('/[^a-z0-9_-]/i', '-', $key)) . '">' . nv_e($message) . '</a></li>';
            } else {
                $items[] = '<li>' . nv_e($message) . '</li>';
            }
        }

        if ($items === []) {
            return '';
        }

        return '<div class="nv-alert nv-alert--danger nv-error-summary" role="alert" tabindex="-1" data-nv-error-summary data-nv-autofocus>'
            . '<span class="nv-alert__icon" aria-hidden="true">' . nv_icon('alert-circle', 20) . '</span>'
            . '<div class="nv-alert__body">'
            . '<p class="nv-alert__title">' . nv_e($title) . '</p>'
            . '<ul class="nv-error-summary__list">' . implode('', $items) . '</ul>'
            . '</div></div>';
    }
}

if (!function_exists('nv_empty_state')) {
    function nv_empty_state(
        string $icon,
        string $title,
        string $description,
        ?string $actionLabel = null,
        ?string $actionHref = null,
        ?string $secondaryLabel = null,
        ?string $secondaryHref = null,
        string $actionAttributes = '',
        string $secondaryAttributes = ''
    ): string {
        $html = '<div class="nv-empty">'
            . '<span class="nv-empty__icon" aria-hidden="true">' . nv_icon($icon, 32) . '</span>'
            . '<h2 class="nv-empty__title">' . nv_e($title) . '</h2>'
            . '<p class="nv-empty__text">' . nv_e($description) . '</p>';

        $actions = '';
        if ($actionLabel !== null && $actionHref !== null) {
            $actions .= '<a class="nv-btn nv-btn--primary" href="' . nv_e($actionHref) . '"'
                . ($actionAttributes !== '' ? ' ' . $actionAttributes : '') . '>'
                . nv_e($actionLabel) . '</a>';
        }
        if ($secondaryLabel !== null && $secondaryHref !== null) {
            $actions .= '<a class="nv-btn nv-btn--secondary" href="' . nv_e($secondaryHref) . '"'
                . ($secondaryAttributes !== '' ? ' ' . $secondaryAttributes : '') . '>'
                . nv_e($secondaryLabel) . '</a>';
        }
        if ($actions !== '') {
            $html .= '<div class="nv-empty__actions">' . $actions . '</div>';
        }

        return $html . '</div>';
    }
}

if (!function_exists('nv_breadcrumb')) {
    function nv_breadcrumb(array $items): string
    {
        if ($items === []) {
            return '';
        }

        $parts = [];
        $last = count($items) - 1;
        $index = 0;

        foreach ($items as $label => $href) {
            if (is_int($label)) {
                $label = (string) $href;
                $href = null;
            }

            if ($index === $last || $href === null || $href === '') {
                $parts[] = '<span aria-current="page">' . nv_e($label) . '</span>';
            } else {
                $parts[] = '<a href="' . nv_e($href) . '">' . nv_e($label) . '</a>';
            }
            $index++;
        }

        return '<nav class="nv-breadcrumb" aria-label="Breadcrumb">' . implode('<span class="nv-breadcrumb__sep" aria-hidden="true">/</span>', $parts) . '</nav>';
    }
}

if (!function_exists('nv_page_header')) {
    function nv_page_header(string $title, string $description = '', string $actionsHtml = '', array $breadcrumb = []): string
    {
        return ($breadcrumb !== [] ? nv_breadcrumb($breadcrumb) : '')
            . '<header class="nv-page-head">'
            . '<div class="nv-page-head__text">'
            . '<h1 class="nv-page-head__title">' . nv_e($title) . '</h1>'
            . ($description !== '' ? '<p class="nv-page-head__desc">' . nv_e($description) . '</p>' : '')
            . '</div>'
            . ($actionsHtml !== '' ? '<div class="nv-page-head__actions">' . $actionsHtml . '</div>' : '')
            . '</header>';
    }
}

if (!function_exists('nv_goal_card')) {
    function nv_goal_card(array|object $goal, array $options = []): string
    {
        $goal = Goal::present($goal);
        $titleId = 'nv-goal-title-' . $goal['id'];
        $name = $goal['name'] !== '' ? $goal['name'] : 'Goal';

        $menu = '';
        if (!$goal['archived'] && $goal['id'] > 0) {
            $confirmTitle = 'Arsipkan “' . $name . '”?';
            $confirmMessage = 'Goal ini akan dipindahkan ke Arsip. Seluruh riwayat tabungan tetap disimpan dan goal dapat dipulihkan kapan saja.';

            $menu = '<div class="nv-menu">'
                . '<button type="button" class="nv-iconbtn nv-iconbtn--sm" data-nv-menu aria-haspopup="true" aria-expanded="false" aria-label="Aksi untuk ' . nv_e($name) . '">'
                . nv_icon('more-horizontal', 20) . '</button>'
                . '<div class="nv-menu__list" hidden>'
                . '<a class="nv-menu__item" href="' . nv_e($goal['editUrl']) . '">' . nv_icon('edit', 16) . '<span>Edit</span></a>'
                . '<button type="button" class="nv-menu__item nv-menu__item--danger" data-nv-confirm'
                . ' data-nv-confirm-action="' . nv_e($goal['archiveUrl']) . '"'
                . ' data-nv-confirm-title="' . nv_e($confirmTitle) . '"'
                . ' data-nv-confirm-message="' . nv_e($confirmMessage) . '"'
                . ' data-nv-confirm-label="Arsipkan" data-nv-confirm-variant="danger">'
                . nv_icon('archive', 16) . '<span>Arsipkan</span></button>'
                . '</div></div>';
        }

        $meta = $goal['hasTargetDate']
            ? 'Target ' . $goal['targetDateLabel']
            : 'Tanpa tanggal target';

        $foot = '<div class="nv-goal-card__foot">'
            . '<span class="nv-goal-card__remaining">Sisa ' . nv_money($goal['remaining'], 'sm') . '</span>'
            . '<span class="nv-goal-card__percent">' . nv_e($goal['progressLabel']) . '</span>'
            . '</div>';

        if ($goal['excess'] > 0) {
            $foot .= '<p class="nv-goal-card__note nv-goal-card__note--success">'
                . nv_icon('check', 16) . '<span>Kelebihan ' . nv_money($goal['excess'], 'sm') . '</span></p>';
        }

        if ($goal['isNegative']) {
            $foot .= '<p class="nv-goal-card__note nv-goal-card__note--danger">'
                . nv_icon('alert-circle', 16) . '<span>Saldo negatif</span></p>';
        }

        $compactClass = !empty($options['compact']) ? ' nv-goal-card--compact' : '';

        return '<article class="nv-goal-card' . $compactClass . '" aria-labelledby="' . nv_e($titleId) . '">'
            . '<div class="nv-goal-card__top">'
            . '<div class="nv-goal-card__heading">'
            . '<h3 class="nv-goal-card__title" id="' . nv_e($titleId) . '">'
            . ($goal['url'] !== '' ? '<a href="' . nv_e($goal['url']) . '">' . nv_e($name) . '</a>' : nv_e($name))
            . '</h3>'
            . '<p class="nv-goal-card__meta">' . nv_e($meta) . '</p>'
            . '</div>'
            . '<div class="nv-goal-card__tools">' . nv_goal_badge($goal) . $menu . '</div>'
            . '</div>'
            . '<p class="nv-goal-card__amounts">'
            . nv_money($goal['saved'], 'md')
            . '<span class="nv-goal-card__target">dari ' . nv_money($goal['target'], 'sm') . '</span>'
            . '</p>'
            . nv_progress($goal['progress'], 'Progres', 'sm', $goal['status'] === Goal::STATUS_COMPLETE ? 'success' : 'brand', false)
            . $foot
            . '</article>';
    }
}

if (!function_exists('nv_activity_list')) {
    function nv_activity_list(array $rows): string
    {
        $items = '';

        foreach ($rows as $row) {
            if (!is_array($row) && !is_object($row)) {
                continue;
            }
            $tx = SavingsTransaction::present($row);
            $goal = $tx['goalName'] !== '' ? $tx['goalName'] : 'Goal';
            $goalHtml = $tx['goalId'] > 0
                ? '<a href="/goals/' . $tx['goalId'] . '">' . nv_e($goal) . '</a>'
                : '<span>' . nv_e($goal) . '</span>';

            $items .= '<li class="nv-activity__item">'
                . '<div class="nv-activity__main">'
                . '<p class="nv-activity__goal">' . $goalHtml . '</p>'
                . '<p class="nv-activity__meta">'
                . '<time datetime="' . nv_e((string) $tx['dateRaw']) . '">' . nv_e($tx['dateLabel']) . '</time>'
                . '<span aria-hidden="true">·</span>'
                . '<span>' . nv_e($tx['directionLabel']) . '</span>'
                . ($tx['note'] !== '' ? '<span aria-hidden="true">·</span><span>' . nv_e($tx['note']) . '</span>' : '')
                . '</p>'
                . '</div>'
                . '<div class="nv-activity__side">'
                . nv_money($tx['signedAmount'], 'sm', true)
                . nv_direction_badge($tx)
                . '</div>'
                . '</li>';
        }

        return $items !== '' ? '<ul class="nv-activity">' . $items . '</ul>' : '';
    }
}

if (!function_exists('nv_transaction_table')) {
    function nv_transaction_table(array $rows, string $caption = 'Riwayat tabungan'): string
    {
        if ($rows === []) {
            return '';
        }

        $body = '';
        foreach ($rows as $row) {
            if (!is_array($row) && !is_object($row)) {
                continue;
            }
            $tx = SavingsTransaction::present($row);
            $note = $tx['note'] !== '' ? $tx['note'] : '—';

            $body .= '<tr>'
                . '<td data-label="Tanggal"><time datetime="' . nv_e((string) $tx['dateRaw']) . '">' . nv_e($tx['dateLabel']) . '</time></td>'
                . '<td data-label="Jenis">' . nv_direction_badge($tx) . '</td>'
                . '<td data-label="Nominal" class="nv-table__amount">' . nv_money($tx['amount'], 'sm') . '</td>'
                . '<td data-label="Catatan" class="nv-table__note">' . nv_e($note) . '</td>'
                . '</tr>';
        }

        return '<div class="nv-table-wrap">'
            . '<table class="nv-table">'
            . '<caption class="nv-sr-only">' . nv_e($caption) . '</caption>'
            . '<thead><tr>'
            . '<th scope="col">Tanggal</th><th scope="col">Jenis</th><th scope="col" class="nv-table__amount">Nominal</th><th scope="col">Catatan</th>'
            . '</tr></thead>'
            . '<tbody>' . $body . '</tbody>'
            . '</table></div>';
    }
}

if (!function_exists('nv_page_url')) {
    function nv_page_url(int $page, string $baseUrl = '', array $query = []): string
    {
        if ($baseUrl === '') {
            $baseUrl = nv_current_path();
        }

        unset($query['page']);
        $query['page'] = $page;
        $queryString = http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        if ($queryString === '') {
            return $baseUrl;
        }

        return $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . $queryString;
    }
}

if (!function_exists('nv_pagination')) {
    function nv_pagination(array|object|null $pagination, string $baseUrl = '', array $query = []): string
    {
        if ($pagination === null) {
            return '';
        }

        $current = max(1, (int) nv_get($pagination, 'current', nv_get($pagination, 'page', 1)));
        $total = (int) nv_get($pagination, 'total', nv_get($pagination, 'totalPages', 1));
        if ($total <= 1) {
            return '';
        }
        $current = min($current, $total);

        $baseUrl = (string) nv_get($pagination, 'baseUrl', nv_get($pagination, 'base_url', $baseUrl));
        $query = nv_get($pagination, 'query', nv_get($pagination, 'params', $query));
        if (!is_array($query)) {
            $query = $query === null ? [] : (array) $query;
        }

        $pages = [1];
        for ($page = max(2, $current - 1); $page <= min($total - 1, $current + 1); $page++) {
            $pages[] = $page;
        }
        if ($total > 1) {
            $pages[] = $total;
        }
        $pages = array_values(array_unique($pages));
        sort($pages);

        $prev = $current > 1
            ? '<a class="nv-pagination__link" href="' . nv_e(nv_page_url($current - 1, $baseUrl, $query)) . '" rel="prev" aria-label="Halaman sebelumnya">'
                . nv_icon('chevron-left', 16) . '<span class="nv-pagination__label">Sebelumnya</span></a>'
            : '<span class="nv-pagination__link is-disabled" aria-disabled="true">'
                . nv_icon('chevron-left', 16) . '<span class="nv-pagination__label">Sebelumnya</span></span>';

        $next = $current < $total
            ? '<a class="nv-pagination__link" href="' . nv_e(nv_page_url($current + 1, $baseUrl, $query)) . '" rel="next" aria-label="Halaman berikutnya">'
                . '<span class="nv-pagination__label">Berikutnya</span>' . nv_icon('chevron-right', 16) . '</a>'
            : '<span class="nv-pagination__link is-disabled" aria-disabled="true">'
                . '<span class="nv-pagination__label">Berikutnya</span>' . nv_icon('chevron-right', 16) . '</span>';

        $numbers = '';
        $previous = 0;
        foreach ($pages as $page) {
            if ($previous > 0 && $page - $previous > 1) {
                $numbers .= '<span class="nv-pagination__ellipsis" aria-hidden="true">…</span>';
            }
            if ($page === $current) {
                $numbers .= '<span class="nv-pagination__number is-current" aria-current="page">' . $page . '</span>';
            } else {
                $numbers .= '<a class="nv-pagination__number" href="' . nv_e(nv_page_url($page, $baseUrl, $query)) . '">' . $page . '</a>';
            }
            $previous = $page;
        }

        return '<nav class="nv-pagination" aria-label="Navigasi halaman">'
            . $prev . '<div class="nv-pagination__pages">' . $numbers . '</div>' . $next
            . '<span class="nv-sr-only">Halaman ' . $current . ' dari ' . $total . '</span>'
            . '</nav>';
    }
}

if (!function_exists('nv_field')) {
    function nv_field(array $args): string
    {
        $name = (string) ($args['name'] ?? '');
        $id = (string) ($args['id'] ?? 'nv-field-' . preg_replace('/[^a-z0-9_-]/i', '-', $name));
        $type = (string) ($args['type'] ?? 'text');
        $label = (string) ($args['label'] ?? '');
        $value = $args['value'] ?? '';
        $error = trim((string) ($args['error'] ?? ''));
        $help = trim((string) ($args['help'] ?? ''));
        $required = !empty($args['required']);
        $disabled = !empty($args['disabled']);
        $readonly = !empty($args['readonly']);
        $placeholder = (string) ($args['placeholder'] ?? '');
        $autocomplete = (string) ($args['autocomplete'] ?? '');
        $inputmode = (string) ($args['inputmode'] ?? '');
        $maxlength = $args['maxlength'] ?? null;
        $min = $args['min'] ?? null;
        $max = $args['max'] ?? null;
        $rows = (int) ($args['rows'] ?? 4);
        $prefix = (string) ($args['prefix'] ?? '');
        $money = !empty($args['money']);
        $counter = !empty($args['counter']);
        $autofocus = !empty($args['autofocus']);
        $options = $args['options'] ?? [];
        $attributes = $args['attributes'] ?? [];
        $extraClass = (string) ($args['class'] ?? '');

        $describedBy = [];
        if ($help !== '') {
            $describedBy[] = $id . '-help';
        }
        if ($error !== '') {
            $describedBy[] = $id . '-error';
        }

        $common = [
            'id' => $id,
            'name' => $name,
            'class' => 'nv-input',
        ];
        if ($required) {
            $common['required'] = 'required';
        }
        if ($disabled) {
            $common['disabled'] = 'disabled';
        }
        if ($readonly) {
            $common['readonly'] = 'readonly';
        }
        if ($placeholder !== '') {
            $common['placeholder'] = $placeholder;
        }
        if ($autocomplete !== '') {
            $common['autocomplete'] = $autocomplete;
        }
        if ($inputmode !== '') {
            $common['inputmode'] = $inputmode;
        }
        if ($maxlength !== null) {
            $common['maxlength'] = (string) $maxlength;
        }
        if ($min !== null) {
            $common['min'] = (string) $min;
        }
        if ($max !== null) {
            $common['max'] = (string) $max;
        }
        if ($describedBy !== []) {
            $common['aria-describedby'] = implode(' ', $describedBy);
        }
        if ($error !== '') {
            $common['aria-invalid'] = 'true';
        }
        if ($money) {
            $common['data-nv-money'] = 'true';
        }
        if ($autofocus) {
            $common['data-nv-autofocus'] = 'true';
        }
        foreach ((array) $attributes as $attrName => $attrValue) {
            if (is_string($attrName) && $attrName !== '') {
                $common[$attrName] = (string) $attrValue;
            }
        }

        $attrString = '';
        foreach ($common as $attrName => $attrValue) {
            $attrString .= ' ' . nv_e($attrName) . '="' . nv_e($attrValue) . '"';
        }

        $control = '';
        if ($type === 'textarea') {
            $control = '<textarea' . $attrString . ' rows="' . $rows . '">' . nv_e($value) . '</textarea>';
        } elseif ($type === 'select') {
            $control = '<select' . $attrString . '>';
            foreach ((array) $options as $optionValue => $optionLabel) {
                if (is_array($optionLabel)) {
                    $optionValue = $optionLabel['value'] ?? $optionValue;
                    $optionLabel = $optionLabel['label'] ?? $optionValue;
                }
                $selected = (string) $optionValue === (string) $value ? ' selected' : '';
                $control .= '<option value="' . nv_e($optionValue) . '"' . $selected . '>' . nv_e($optionLabel) . '</option>';
            }
            $control .= '</select>';
        } else {
            $common['type'] = $type;
            $common['value'] = (string) $value;
            if ($type === 'password') {
                $common['type'] = 'password';
            }
            $attrString = '';
            foreach ($common as $attrName => $attrValue) {
                $attrString .= ' ' . nv_e($attrName) . '="' . nv_e($attrValue) . '"';
            }
            $control = '<input' . $attrString . '>';
        }

        if ($type === 'password') {
            $control = '<div class="nv-input-group nv-input-group--password">' . $control
                . '<button type="button" class="nv-input-group__action" data-nv-password-toggle aria-label="Tampilkan kata sandi" aria-pressed="false">'
                . '<span data-nv-eye-show>' . nv_icon('eye', 18) . '</span>'
                . '<span data-nv-eye-hide hidden>' . nv_icon('eye-off', 18) . '</span>'
                . '</button></div>';
        } elseif ($prefix !== '') {
            $control = '<div class="nv-input-group nv-input-group--prefix">'
                . '<span class="nv-input-group__prefix" aria-hidden="true">' . nv_e($prefix) . '</span>'
                . $control . '</div>';
        }

        $labelHtml = $label !== ''
            ? '<label class="nv-field__label" for="' . nv_e($id) . '">' . nv_e($label)
                . ($required ? '<span class="nv-required" aria-hidden="true">*</span><span class="nv-sr-only"> (wajib)</span>' : '')
                . '</label>'
            : '';

        $helpHtml = $help !== ''
            ? '<p class="nv-field__help" id="' . nv_e($id) . '-help">' . nv_e($help) . '</p>'
            : '';

        $previewHtml = $money
            ? '<p class="nv-field__preview" data-nv-money-preview="' . nv_e($name) . '" aria-live="polite"></p>'
            : '';

        $counterHtml = '';
        if ($counter && $maxlength !== null) {
            $counterHtml = '<span class="nv-field__counter" data-nv-counter="' . nv_e($id) . '" aria-hidden="true">'
                . strlen((string) $value) . ' / ' . (int) $maxlength . '</span>';
        }

        $errorHtml = $error !== ''
            ? '<p class="nv-field__error" id="' . nv_e($id) . '-error">' . nv_icon('alert-circle', 16) . '<span>' . nv_e($error) . '</span></p>'
            : '';

        $classes = trim('nv-field ' . ($error !== '' ? 'nv-field--invalid ' : '') . $extraClass);

        return '<div class="' . nv_e($classes) . '">'
            . $labelHtml
            . $control
            . ($counterHtml !== '' ? '<span class="nv-field__counter-wrap">' . $counterHtml . '</span>' : '')
            . $previewHtml
            . $helpHtml
            . $errorHtml
            . '</div>';
    }
}

if (!function_exists('nv_savings_dialog')) {
    function nv_savings_dialog(
        array|object $goal,
        string $action,
        string $today = '',
        array|object|null $values = null,
        array|object|null $errors = null,
        ?string $csrf = null
    ): string {
        $goal = Goal::present($goal);
        if ($goal['id'] <= 0) {
            return '';
        }

        if ($today === '') {
            $today = (new DateTimeImmutable('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d');
        }

        $errorsArray = $errors === null ? [] : (array) $errors;
        $autoOpen = $errorsArray !== [];
        $direction = nv_input_value($values, 'direction', 'deposit');
        if (!in_array($direction, ['deposit', 'withdrawal'], true)) {
            $direction = 'deposit';
        }

        $name = $goal['name'] !== '' ? $goal['name'] : 'Goal';
        $titleId = 'nv-savings-dialog-title';

        $form = '<form action="' . nv_e($action) . '" method="post" data-nv-submit-loading>'
            . nv_csrf_field($csrf)
            . ($autoOpen ? nv_error_summary($errorsArray) : '')
            . '<p class="nv-dialog__context">Saldo saat ini <strong>' . nv_money($goal['saved'], 'sm') . '</strong></p>'
            . '<fieldset class="nv-field nv-field--group">'
            . '<legend class="nv-field__label">Jenis<span class="nv-required" aria-hidden="true">*</span><span class="nv-sr-only"> (wajib)</span></legend>'
            . '<div class="nv-segmented">'
            . '<label class="nv-segmented__option"><input type="radio" name="direction" value="deposit"'
            . ($direction === 'deposit' ? ' checked' : '') . ' required><span class="nv-segmented__body">'
            . nv_icon('arrow-down-left', 18) . '<span>Setoran</span></span></label>'
            . '<label class="nv-segmented__option"><input type="radio" name="direction" value="withdrawal"'
            . ($direction === 'withdrawal' ? ' checked' : '') . '><span class="nv-segmented__body">'
            . nv_icon('arrow-up-right', 18) . '<span>Penarikan</span></span></label>'
            . '</div>'
            . (!empty($errorsArray['direction']) ? '<p class="nv-field__error">' . nv_icon('alert-circle', 16) . '<span>' . nv_e($errorsArray['direction']) . '</span></p>' : '')
            . '<p class="nv-field__help">Penarikan dapat membuat saldo menjadi negatif.</p>'
            . '</fieldset>'
            . nv_field([
                'name' => 'amount',
                'label' => 'Nominal',
                'type' => 'text',
                'prefix' => 'Rp',
                'money' => true,
                'inputmode' => 'numeric',
                'required' => true,
                'autofocus' => true,
                'value' => nv_input_value($values, 'amount', ''),
                'error' => $errorsArray['amount'] ?? '',
                'help' => 'Masukkan nominal penuh tanpa desimal. Contoh: 1500000.',
                'attributes' => ['pattern' => '[0-9]*', 'autocomplete' => 'off'],
            ])
            . nv_field([
                'name' => 'occurred_on',
                'label' => 'Tanggal',
                'type' => 'date',
                'required' => true,
                'value' => nv_input_value($values, 'occurred_on', $today),
                'error' => $errorsArray['occurred_on'] ?? '',
                'help' => 'Tanggal mengikuti zona waktu Asia/Jakarta (WIB).',
            ])
            . nv_field([
                'name' => 'note',
                'label' => 'Catatan (opsional)',
                'type' => 'textarea',
                'rows' => 3,
                'maxlength' => 255,
                'counter' => true,
                'value' => nv_input_value($values, 'note', ''),
                'error' => $errorsArray['note'] ?? '',
                'help' => 'Contoh: bonus, tabungan bulanan, atau keperluan.',
            ])
            . '<div class="nv-dialog__actions">'
            . '<button type="button" class="nv-btn nv-btn--secondary" data-nv-dialog-close>Batal</button>'
            . '<button type="submit" class="nv-btn nv-btn--primary">Simpan</button>'
            . '</div>'
            . '</form>';

        return '<div class="nv-dialog" id="nv-savings-dialog" role="dialog" aria-modal="true" aria-labelledby="' . $titleId . '"'
            . ($autoOpen ? ' data-nv-dialog-autoopen' : '') . ' hidden>'
            . '<div class="nv-dialog__backdrop" data-nv-dialog-close></div>'
            . '<div class="nv-dialog__panel">'
            . '<div class="nv-dialog__head">'
            . '<h2 class="nv-dialog__title" id="' . $titleId . '">Tambah Tabungan — ' . nv_e($name) . '</h2>'
            . '<button type="button" class="nv-iconbtn nv-iconbtn--sm" data-nv-dialog-close aria-label="Tutup dialog">' . nv_icon('close', 18) . '</button>'
            . '</div>'
            . $form
            . '</div></div>';
    }
}

if (!function_exists('nv_confirm_dialog')) {
    function nv_confirm_dialog(?string $csrf = null): string
    {
        return '<div class="nv-dialog" id="nv-confirm-dialog" role="alertdialog" aria-modal="true" aria-labelledby="nv-confirm-title" aria-describedby="nv-confirm-message" hidden>'
            . '<div class="nv-dialog__backdrop" data-nv-dialog-static></div>'
            . '<div class="nv-dialog__panel nv-dialog__panel--sm">'
            . '<div class="nv-dialog__head">'
            . '<h2 class="nv-dialog__title" id="nv-confirm-title">Konfirmasi</h2>'
            . '<button type="button" class="nv-iconbtn nv-iconbtn--sm" data-nv-dialog-close aria-label="Tutup dialog">' . nv_icon('close', 18) . '</button>'
            . '</div>'
            . '<p class="nv-dialog__message" id="nv-confirm-message"></p>'
            . '<form method="post" action="" data-nv-confirm-form>'
            . nv_csrf_field($csrf)
            . '<div class="nv-dialog__actions">'
            . '<button type="button" class="nv-btn nv-btn--secondary" data-nv-dialog-close data-nv-autofocus>Batal</button>'
            . '<button type="submit" class="nv-btn nv-btn--danger" id="nv-confirm-submit">Lanjutkan</button>'
            . '</div>'
            . '</form>'
            . '</div></div>';
    }
}

if (!function_exists('nv_skeleton_cards')) {
    function nv_skeleton_cards(int $count = 6): string
    {
        $cards = '';
        for ($i = 0; $i < $count; $i++) {
            $cards .= '<div class="nv-skeleton-card" aria-hidden="true">'
                . '<span class="nv-skeleton nv-skeleton--title"></span>'
                . '<span class="nv-skeleton nv-skeleton--meta"></span>'
                . '<span class="nv-skeleton nv-skeleton--amount"></span>'
                . '<span class="nv-skeleton nv-skeleton--bar"></span>'
                . '<span class="nv-skeleton nv-skeleton--foot"></span>'
                . '</div>';
        }

        return '<div class="nv-goal-grid" aria-busy="true"><span class="nv-sr-only">Memuat data goal…</span>' . $cards . '</div>';
    }
}

if (!function_exists('nv_skeleton_summary')) {
    function nv_skeleton_summary(): string
    {
        return '<div class="nv-summary" aria-busy="true"><span class="nv-sr-only">Memuat ringkasan tabungan…</span>'
            . '<div class="nv-summary__item"><span class="nv-skeleton nv-skeleton--meta"></span><span class="nv-skeleton nv-skeleton--amount"></span></div>'
            . '<div class="nv-summary__item"><span class="nv-skeleton nv-skeleton--meta"></span><span class="nv-skeleton nv-skeleton--amount"></span></div>'
            . '<div class="nv-summary__item"><span class="nv-skeleton nv-skeleton--meta"></span><span class="nv-skeleton nv-skeleton--amount"></span></div>'
            . '</div>';
    }
}

if (!function_exists('nv_skeleton_table')) {
    function nv_skeleton_table(int $rows = 5): string
    {
        $body = '';
        for ($i = 0; $i < $rows; $i++) {
            $body .= '<div class="nv-skeleton-row" aria-hidden="true">'
                . '<span class="nv-skeleton"></span><span class="nv-skeleton"></span><span class="nv-skeleton"></span><span class="nv-skeleton"></span>'
                . '</div>';
        }

        return '<div class="nv-table-skeleton" aria-busy="true"><span class="nv-sr-only">Memuat riwayat tabungan…</span>' . $body . '</div>';
    }
}
