<?php
// NAVORA — app/views/dashboard/index.php
// Dashboard (WF-01 desktop / WF-11 mobile).
//
// Proposed view contract (Owner confirmation required):
//   $summary  array|object|null  activeGoals, totalSaved, totalRemaining
//   $goals    array              active goal rows (DATABASE-DESIGN.md columns)
//   $recent   array              latest savings transactions
//   $loading  bool               render skeletons instead of figures
//   $error    string|null        page-level error message
//
// No value is fabricated. When the contract is absent the page renders an
// honest empty state instead of invented financial figures (WF-01 D-02).

require_once __DIR__ . '/../layouts/main.php';

use NAVORA\Models\Goal;

$nvLayout = 'app';
$nvActiveNav = 'dashboard';
$nvPageTitle = 'Dashboard';

$nvCsrf = $nvCsrf ?? ($csrfToken ?? null);

$summary = $summary ?? ($dashboardSummary ?? null);
$goals = $goals ?? [];
$goals = is_array($goals) ? $goals : [];
$recent = $recent ?? [];
$recent = is_array($recent) ? $recent : [];
$loading = !empty($loading);
$error = isset($error) && is_string($error) ? trim($error) : '';

$summaryData = Goal::summarize($summary, $goals);
$hasGoals = $summaryData['activeGoals'] > 0 || $goals !== [];

$nearlyReached = $nearlyReached ?? null;
if (!is_array($nearlyReached)) {
    $nearlyReached = [];
    foreach ($goals as $row) {
        if (!is_array($row) && !is_object($row)) {
            continue;
        }
        $presented = Goal::present($row);
        if ($presented['nearlyReached']) {
            $nearlyReached[] = $presented;
        }
    }
    usort($nearlyReached, static function (array $a, array $b): int {
        return $b['progressRaw'] <=> $a['progressRaw'];
    });
    $nearlyReached = array_slice($nearlyReached, 0, 3);
}

$addGoalUrl = '/goals/create';

require __DIR__ . '/../layouts/header.php';
?>
<div class="nv-page-head">
    <div class="nv-page-head__text">
        <h1 class="nv-page-head__title">Dashboard</h1>
        <p class="nv-page-head__desc">Ringkasan tabungan Anda</p>
    </div>
    <div class="nv-page-head__actions">
        <?php if ($hasGoals || $loading): ?>
            <a class="nv-btn nv-btn--primary" href="<?= nv_e($addGoalUrl) ?>">
                <?= nv_icon('plus', 18) ?><span>Tambah Goal</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($error !== ''): ?>
    <?= nv_alert('danger', 'Dashboard tidak dapat dimuat', $error, false,
        '<a class="nv-btn nv-btn--secondary" href="/dashboard">Coba lagi</a>') ?>
<?php elseif ($loading): ?>
    <?= nv_skeleton_summary() ?>
    <section class="nv-section" aria-labelledby="nv-dashboard-goals-title">
        <div class="nv-section__head">
            <h2 class="nv-section__title" id="nv-dashboard-goals-title">Goal Anda</h2>
        </div>
        <?= nv_skeleton_cards(3) ?>
    </section>
<?php elseif (!$hasGoals): ?>
    <?= nv_empty_state(
        'target',
        'Belum ada goal',
        'Tentukan tujuan tabungan pertama Anda, lalu catat setoran secara berkala agar progresnya terlihat.',
        'Tambah Goal',
        $addGoalUrl
    ) ?>
<?php else: ?>
    <section class="nv-summary" aria-label="Ringkasan tabungan">
        <div class="nv-summary__item">
            <span class="nv-summary__label">Goal Aktif</span>
            <span class="nv-summary__value"><span class="nv-amount nv-amount--md"><?= (int) $summaryData['activeGoals'] ?></span></span>
            <span class="nv-summary__meta">Tidak termasuk goal yang diarsipkan</span>
        </div>
        <div class="nv-summary__item">
            <span class="nv-summary__label">Total Tabungan</span>
            <span class="nv-summary__value"><?= nv_money((int) $summaryData['totalSaved'], 'md') ?></span>
            <span class="nv-summary__meta">Dari seluruh goal aktif</span>
        </div>
        <div class="nv-summary__item">
            <span class="nv-summary__label">Total Sisa</span>
            <span class="nv-summary__value"><?= nv_money((int) $summaryData['totalRemaining'], 'md') ?></span>
            <span class="nv-summary__meta">Sisa menuju target</span>
        </div>
    </section>

    <?php if ($nearlyReached !== []): ?>
        <section class="nv-section" aria-labelledby="nv-nearly-title">
            <div class="nv-section__head">
                <h2 class="nv-section__title" id="nv-nearly-title">Hampir tercapai</h2>
                <a class="nv-section__link" href="/goals">Lihat semua goal <?= nv_icon('arrow-right', 16) ?></a>
            </div>
            <div class="nv-goal-grid">
                <?php foreach ($nearlyReached as $goalRow): ?>
                    <?= nv_goal_card($goalRow, ['compact' => true]) ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="nv-section" aria-labelledby="nv-recent-title">
        <div class="nv-section__head">
            <h2 class="nv-section__title" id="nv-recent-title">Tabungan terbaru</h2>
        </div>
        <div class="nv-panel nv-panel--flush">
            <div class="nv-panel__body">
                <?php if ($recent === []): ?>
                    <p class="nv-panel__empty nv-muted nv-text-sm">Belum ada tabungan tercatat.</p>
                <?php else: ?>
                    <?= nv_activity_list($recent) ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?= nv_confirm_dialog($nvCsrf) ?>
<?php
require __DIR__ . '/../layouts/footer.php';
