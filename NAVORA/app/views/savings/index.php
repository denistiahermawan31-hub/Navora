<?php
// NAVORA — app/views/savings/index.php
// Savings history for one goal (WF-04 / WF-10 / WF-13).
//
// Proposed view contract (Owner confirmation required):
//   $goal       array|object      the goal the history belongs to
//   $rows       array             savings transactions (paginated)
//   $pagination array|object|null current, total, baseUrl, query
//   $values     array|object|null repopulated form values after an error
//   $errors     array|object|null field errors for the add-savings form
//   $today      string            WIB today (Y-m-d); defaults to Asia/Jakarta
//   $action     string            POST target for a new savings transaction
//   $loading    bool              render a skeleton table
//   $error      string|null       page-level error message
//
// Amounts are rendered with the shared money helpers; no calculation is
// duplicated in this view.

require_once __DIR__ . '/../layouts/main.php';

use NAVORA\Models\Goal;

$nvLayout = 'app';
$nvActiveNav = 'goals';

$nvCsrf = $nvCsrf ?? ($csrfToken ?? null);

$goal = $goal ?? null;
$rows = $rows ?? [];
$rows = is_array($rows) ? $rows : [];
$pagination = $pagination ?? null;
$values = $values ?? null;
$errors = $errors ?? [];
$errors = is_array($errors) || is_object($errors) ? (array) $errors : [];
$loading = !empty($loading);
$error = isset($error) && is_string($error) ? trim($error) : '';

$goalData = ($goal !== null && (is_array($goal) || is_object($goal))) ? Goal::present($goal) : null;
$goalName = $goalData !== null && $goalData['name'] !== '' ? $goalData['name'] : 'Goal';
$goalUrl = $goalData !== null && $goalData['id'] > 0 ? '/goals/' . $goalData['id'] : '/goals';
$today = isset($today) && is_string($today) && trim($today) !== ''
    ? trim($today)
    : (new DateTimeImmutable('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d');
$savingsAction = isset($action) && is_string($action) && trim($action) !== ''
    ? $action
    : ($goalData !== null && $goalData['id'] > 0 ? '/goals/' . $goalData['id'] . '/savings' : '');

$nvPageTitle = $goalData !== null ? 'Riwayat Tabungan — ' . $goalName : 'Riwayat Tabungan';

require __DIR__ . '/../layouts/header.php';
?>
<?= $goalData !== null
    ? nv_breadcrumb(['Goals' => '/goals', $goalName => $goalUrl, 'Riwayat Tabungan' => null])
    : nv_breadcrumb(['Goals' => '/goals', 'Riwayat Tabungan' => null]) ?>

<div class="nv-page-head">
    <div class="nv-page-head__text">
        <h1 class="nv-page-head__title"><?= $goalData !== null
            ? 'Riwayat Tabungan — ' . nv_e($goalName)
            : 'Riwayat Tabungan' ?></h1>
        <p class="nv-page-head__desc"><?= $goalData !== null
            ? 'Semua setoran dan penarikan untuk goal ini, terbaru lebih dahulu.'
            : 'Riwayat tabungan ditampilkan per goal.' ?></p>
    </div>
    <div class="nv-page-head__actions">
        <a class="nv-btn nv-btn--secondary" href="<?= nv_e($goalUrl) ?>"><?= nv_icon('arrow-left', 18) ?><span><?= $goalData !== null ? 'Kembali ke goal' : 'Kembali ke Goals' ?></span></a>
        <?php if ($rows !== [] && $goalData !== null && $savingsAction !== '' && !$goalData['archived']): ?>
            <button type="button" class="nv-btn nv-btn--primary" data-nv-dialog-open="nv-savings-dialog">
                <?= nv_icon('plus', 18) ?><span>Tambah Tabungan</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if ($error !== ''): ?>
    <?= nv_alert('danger', 'Riwayat tidak dapat dimuat', $error, false,
        '<a class="nv-btn nv-btn--secondary" href="' . ($goalData !== null ? nv_e($goalUrl) : '/goals') . '">'
        . ($goalData !== null ? 'Kembali ke goal' : 'Kembali ke Goals') . '</a>') ?>
<?php elseif ($goalData === null): ?>
    <?= nv_empty_state(
        'alert-circle',
        'Goal tidak ditemukan',
        'Riwayat tabungan hanya tersedia untuk goal yang Anda miliki.',
        'Kembali ke Goals',
        '/goals'
    ) ?>
<?php elseif ($loading): ?>
    <?= nv_skeleton_table() ?>
<?php elseif ($rows === []): ?>
    <?php if ($goalData['archived']): ?>
        <?= nv_empty_state(
            'history',
            'Belum ada tabungan tercatat',
            'Goal ini diarsipkan dan riwayatnya tidak dapat diubah. Pulihkan goal untuk mencatat tabungan baru.',
            'Kembali ke goal',
            $goalUrl
        ) ?>
    <?php else: ?>
        <?= nv_empty_state(
            'history',
            'Belum ada tabungan tercatat',
            'Catat setoran pertama untuk goal ini agar progres dan sisa target mulai terlihat.',
            'Tambah Tabungan',
            '#nv-savings-dialog',
            null,
            null,
            'data-nv-dialog-open="nv-savings-dialog"'
        ) ?>
    <?php endif; ?>
<?php else: ?>
    <?= nv_transaction_table($rows, 'Riwayat tabungan ' . $goalName) ?>
    <?= nv_pagination($pagination, $goalUrl . '/savings', []) ?>
<?php endif; ?>

<?php if ($error === '' && $goalData !== null && $savingsAction !== '' && !$goalData['archived']): ?>
    <?= nv_savings_dialog($goalData, $savingsAction, $today, $values, $errors, $nvCsrf) ?>
<?php endif; ?>
<?php
require __DIR__ . '/../layouts/footer.php';
