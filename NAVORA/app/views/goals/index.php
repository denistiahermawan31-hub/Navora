<?php
// NAVORA — app/views/goals/index.php
// Goals list (WF-02 / WF-09).
//
// Proposed view contract (Owner confirmation required):
//   $goals      array             active goal rows
//   $pagination array|object|null current, total, baseUrl, query
//   $searchTerm string            current search query (name `q`)
//   $sort       string            current sort key (name `sort`)
//   $total      int|null          total active goals across pages
//   $loading    bool              render skeletons
//   $error      string|null       page-level error message

require_once __DIR__ . '/../layouts/main.php';

$nvLayout = 'app';
$nvActiveNav = 'goals';
$nvPageTitle = 'Goals';

$nvCsrf = $nvCsrf ?? ($csrfToken ?? null);

$goals = $goals ?? [];
$goals = is_array($goals) ? $goals : [];
$pagination = $pagination ?? null;
$searchTerm = isset($searchTerm) ? trim((string) $searchTerm) : '';
$sort = isset($sort) ? (string) $sort : 'recent';
$total = isset($total) ? (int) $total : count($goals);
$loading = !empty($loading);
$error = isset($error) && is_string($error) ? trim($error) : '';

$hasQuery = $searchTerm !== '';
$isEmpty = $goals === [];
$addGoalUrl = '/goals/create';

$sortOptions = [
    'recent' => 'Terbaru',
    'target_date' => 'Tanggal target',
    'progress' => 'Progres',
];

require __DIR__ . '/../layouts/header.php';
?>
<div class="nv-page-head">
    <div class="nv-page-head__text">
        <h1 class="nv-page-head__title">Goals<?= $total > 0 ? ' (' . (int) $total . ')' : '' ?></h1>
        <p class="nv-page-head__desc">Semua tujuan tabungan aktif Anda.</p>
    </div>
    <div class="nv-page-head__actions">
        <?php if (!$isEmpty): ?>
            <a class="nv-btn nv-btn--primary" href="<?= nv_e($addGoalUrl) ?>">
                <?= nv_icon('plus', 18) ?><span>Tambah Goal</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($error !== ''): ?>
    <?= nv_alert('danger', 'Goals tidak dapat dimuat', $error, false,
        '<a class="nv-btn nv-btn--secondary" href="/goals">Coba lagi</a>') ?>
<?php elseif ($loading): ?>
    <?= nv_skeleton_cards(6) ?>
<?php elseif ($isEmpty && !$hasQuery): ?>
    <?= nv_empty_state(
        'target',
        'Belum ada goal',
        'Tentukan tujuan tabungan pertama Anda, lalu catat setoran secara berkala agar progresnya terlihat.',
        'Tambah Goal',
        $addGoalUrl
    ) ?>
<?php else: ?>
    <form class="nv-filterbar" role="search" action="/goals" method="get">
        <div class="nv-search">
            <label class="nv-sr-only" for="nv-search">Cari goal</label>
            <span class="nv-search__icon" aria-hidden="true"><?= nv_icon('search', 18) ?></span>
            <input
                class="nv-input"
                id="nv-search"
                type="search"
                name="q"
                value="<?= nv_e($searchTerm) ?>"
                placeholder="Cari goal…"
                autocomplete="off"
            >
        </div>
        <div class="nv-filterbar__sort">
            <label class="nv-sr-only" for="nv-sort">Urutkan</label>
            <select class="nv-input" id="nv-sort" name="sort" data-nv-autosubmit>
                <?php foreach ($sortOptions as $value => $label): ?>
                    <option value="<?= nv_e($value) ?>"<?= $sort === $value ? ' selected' : '' ?>><?= nv_e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="nv-btn nv-btn--secondary">Terapkan</button>
        </div>
    </form>

    <?php if ($isEmpty): ?>
        <?= nv_empty_state(
            'search',
            'Tidak ada goal yang cocok',
            'Coba kata kunci lain atau kembali ke seluruh daftar goal Anda.',
            'Hapus pencarian',
            '/goals'
        ) ?>
    <?php else: ?>
        <div class="nv-goal-grid">
            <?php foreach ($goals as $row): ?>
                <?php if (is_array($row) || is_object($row)): ?>
                    <?= nv_goal_card($row) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?= nv_pagination($pagination, '/goals', ['q' => $searchTerm, 'sort' => $sort]) ?>
    <?php endif; ?>
<?php endif; ?>

<?= nv_confirm_dialog($nvCsrf) ?>
<?php
require __DIR__ . '/../layouts/footer.php';
