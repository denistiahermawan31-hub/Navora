<?php
// NAVORA — app/views/auth/register.php
// Registration screen (WF-17, FR-001).
//
// Presentation only. The POST target `/daftar` is handled by AuthController,
// which currently does not create accounts; the Owner must wire persistence,
// validation, and email verification (DEC-015 / OPEN-08). Password fields are
// never repopulated.

$nvLayout = 'auth';
$nvPageTitle = 'Daftar';
$nvPageDescription = 'Buat akun NAVORA untuk mulai mencatat tujuan tabungan Anda.';

require_once __DIR__ . '/../layouts/main.php';

$nvCsrf = $nvCsrf ?? ($csrfToken ?? null);

$errors = $errors ?? ($nvErrors ?? []);
$errors = is_array($errors) || is_object($errors) ? (array) $errors : [];

$old = $old ?? ($values ?? ($formData ?? []));
$old = is_array($old) || is_object($old) ? (array) $old : [];

$nameValue = nv_input_value($old, 'name', '');
$emailValue = nv_input_value($old, 'email', '');
$hasErrors = $errors !== [];

require __DIR__ . '/../layouts/header.php';
?>
<div class="nv-auth__card">
    <h1 class="nv-auth__title">Buat akun NAVORA</h1>
    <p class="nv-auth__sub">Akun pribadi Anda menyimpan goal dan riwayat tabungan secara terpisah dari pengguna lain.</p>

    <?= nv_error_summary($errors, 'Periksa kembali isian Anda') ?>

    <form class="nv-auth__form" action="/daftar" method="post" data-nv-submit-loading novalidate>
        <?= nv_csrf_field($nvCsrf) ?>

        <?= nv_field([
            'name' => 'name',
            'label' => 'Nama',
            'type' => 'text',
            'required' => true,
            'autocomplete' => 'name',
            'placeholder' => 'Nama Anda',
            'value' => $nameValue,
            'error' => $errors['name'] ?? '',
            'autofocus' => !$hasErrors,
            'attributes' => ['maxlength' => '80'],
        ]) ?>

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
            'attributes' => ['maxlength' => '254', 'spellcheck' => 'false'],
        ]) ?>

        <?= nv_field([
            'name' => 'password',
            'label' => 'Kata sandi',
            'type' => 'password',
            'required' => true,
            'autocomplete' => 'new-password',
            'error' => $errors['password'] ?? '',
            'help' => 'Gunakan kata sandi yang kuat dan tidak dipakai di layanan lain.',
        ]) ?>

        <?= nv_field([
            'name' => 'password_confirmation',
            'label' => 'Ulangi kata sandi',
            'type' => 'password',
            'required' => true,
            'autocomplete' => 'new-password',
            'error' => $errors['password_confirmation'] ?? '',
        ]) ?>

        <p class="nv-help-block">
            <?= nv_icon('mail', 18) ?>
            <span>Setelah mendaftar, kami akan mengirim tautan verifikasi ke email Anda.</span>
        </p>

        <button type="submit" class="nv-btn nv-btn--primary nv-btn--full">
            <span class="nv-btn__label">Daftar</span>
        </button>
    </form>

    <div class="nv-auth__links">
        <span>Sudah punya akun? <a href="/masuk">Masuk</a></span>
    </div>
</div>
<?php
require __DIR__ . '/../layouts/footer.php';
