<?php
// NAVORA — app/views/auth/login.php
// Login screen (WF-17).
//
// Presentation only. The POST target `/masuk` is handled by AuthController,
// which currently does not authenticate; the Owner must wire the backend
// (DEC-015 / OPEN-08). This view never repopulates the password field.

$nvLayout = 'auth';
$nvPageTitle = 'Masuk';
$nvPageDescription = 'Masuk ke akun NAVORA Anda.';

require_once __DIR__ . '/../layouts/main.php';

$nvCsrf = $nvCsrf ?? ($csrfToken ?? null);

$errors = $errors ?? ($nvErrors ?? []);
$errors = is_array($errors) || is_object($errors) ? (array) $errors : [];

$old = $old ?? ($values ?? ($formData ?? []));
$old = is_array($old) || is_object($old) ? $old : [];

$emailValue = nv_input_value($old, 'email', '');
$hasErrors = $errors !== [];

require __DIR__ . '/../layouts/header.php';
?>
<div class="nv-auth__card">
    <h1 class="nv-auth__title">Masuk ke akun Anda</h1>
    <p class="nv-auth__sub">Lanjutkan mencatat tabungan dan memantau progres goal Anda.</p>

    <?= nv_error_summary($errors, 'Periksa kembali isian Anda') ?>

    <form class="nv-auth__form" action="/masuk" method="post" data-nv-submit-loading novalidate>
        <?= nv_csrf_field($nvCsrf) ?>

        <?= nv_field([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'required' => true,
            'autocomplete' => 'email',
            'inputmode' => 'email',
            'placeholder' => 'nama@contoh.com',
            'value' => $emailValue,
            'error' => $errors['email'] ?? '',
            'autofocus' => !$hasErrors,
            'attributes' => ['maxlength' => '254', 'spellcheck' => 'false'],
        ]) ?>

        <?= nv_field([
            'name' => 'password',
            'label' => 'Kata sandi',
            'type' => 'password',
            'required' => true,
            'autocomplete' => 'current-password',
            'error' => $errors['password'] ?? '',
            'autofocus' => $hasErrors && !isset($errors['email']),
        ]) ?>

        <button type="submit" class="nv-btn nv-btn--primary nv-btn--full">
            <span class="nv-btn__label">Masuk</span>
        </button>
    </form>

    <div class="nv-auth__links">
        <?php if (!empty($resetUrl)): ?>
            <a href="<?= nv_e((string) $resetUrl) ?>">Lupa kata sandi?</a>
        <?php endif; ?>
        <span>Belum punya akun? <a href="/daftar">Daftar</a></span>
    </div>
</div>
<?php
require __DIR__ . '/../layouts/footer.php';
