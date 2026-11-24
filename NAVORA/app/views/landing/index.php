<?php
// NAVORA — app/views/landing/index.php
// Public landing page (WF-18 desktop / WF-19 mobile).
//
// Copy, section order, and the headline "Menabung jadi lebih terarah." are
// preserved from the approved wireframe. No statistics, testimonials, user
// counts, or demo financial figures appear anywhere on this page.

$nvLayout = 'public';
$nvPageTitle = 'NAVORA';
$nvDocumentTitle = 'NAVORA — Menabung jadi lebih terarah';
$nvPageDescription = 'NAVORA membantu mencatat tabungan untuk satu tujuan yang jelas: target harga, catatan setoran, dan progres yang mudah dibaca.';

require __DIR__ . '/../layouts/header.php';
?>
<section class="nv-hero" id="awal">
    <div class="nv-container nv-hero__grid">
        <div class="nv-hero__copy">
            <h1 class="nv-hero__title">Menabung jadi lebih terarah.</h1>
            <p class="nv-hero__lead">Buat tujuan tabungan, catat setoran dan penarikan, lalu pantau progres sampai tercapai.</p>
            <div class="nv-hero__actions">
                <a class="nv-btn nv-btn--primary" href="/daftar">Daftar</a>
                <a class="nv-btn nv-btn--secondary" href="/masuk">Masuk</a>
            </div>
        </div>

        <div class="nv-hero__visual" aria-hidden="true">
            <svg viewBox="0 0 320 320" fill="none" focusable="false">
                <circle class="nv-hero-art__ring-1" cx="160" cy="148" r="92" stroke-width="1.75"/>
                <circle class="nv-hero-art__ring-2" cx="160" cy="148" r="70" stroke-width="1.75"/>
                <circle class="nv-hero-art__ring-3" cx="160" cy="148" r="48" stroke-width="1.75"/>
                <circle class="nv-hero-art__dot" cx="160" cy="148" r="8"/>

                <path class="nv-hero-art__arc" d="M160 28a120 120 0 0 1 84.9 35.1" stroke-width="4" stroke-linecap="round"/>

                <rect class="nv-hero-art__track" x="86" y="262" width="148" height="12" rx="6"/>
                <rect class="nv-hero-art__bar" x="86" y="262" width="96" height="12" rx="6"/>

                <g class="nv-hero-art__coins" stroke-width="1.75">
                    <circle cx="58" cy="92" r="15"/>
                    <circle cx="86" cy="74" r="15"/>
                    <circle cx="112" cy="92" r="15"/>
                </g>

                <path class="nv-hero-art__arrow" d="M252 116l14 18-14 18" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path class="nv-hero-art__arrow" d="M266 134h-26" stroke-width="1.75" stroke-linecap="round"/>
            </svg>
        </div>
    </div>
</section>

<section class="nv-landing-section" id="tentang">
    <div class="nv-container">
        <div class="nv-landing-head">
            <h2 class="nv-landing-head__title">Tentang NAVORA</h2>
            <p class="nv-landing-head__text">
                NAVORA membantu mencatat tabungan untuk satu tujuan yang jelas: target harga, catatan setoran, dan progres yang mudah dibaca.
            </p>
        </div>
    </div>
</section>

<section class="nv-landing-section nv-landing-section--tint" id="fitur">
    <div class="nv-container">
        <div class="nv-landing-head">
            <h2 class="nv-landing-head__title">Fitur Utama</h2>
            <p class="nv-landing-head__text">Hal-hal yang Anda butuhkan untuk menabung dengan arah yang jelas.</p>
        </div>

        <div class="nv-feature-grid">
            <article class="nv-feature">
                <span class="nv-feature__icon" aria-hidden="true"><?= nv_icon('target', 22) ?></span>
                <div>
                    <h3 class="nv-feature__title">Target harga &amp; tanggal opsional</h3>
                    <p class="nv-feature__text">Atur target nilai yang ingin dicapai dan tanggal tujuan bila diperlukan.</p>
                </div>
            </article>

            <article class="nv-feature">
                <span class="nv-feature__icon" aria-hidden="true"><?= nv_icon('wallet', 22) ?></span>
                <div>
                    <h3 class="nv-feature__title">Setoran &amp; penarikan</h3>
                    <p class="nv-feature__text">Catat setoran uang baru atau penarikan sesuai kebutuhan.</p>
                </div>
            </article>

            <article class="nv-feature">
                <span class="nv-feature__icon" aria-hidden="true"><?= nv_icon('trend-up', 22) ?></span>
                <div>
                    <h3 class="nv-feature__title">Progres saldo</h3>
                    <p class="nv-feature__text">Lihat seberapa jauh progres Anda dari target tabungan.</p>
                </div>
            </article>

            <article class="nv-feature">
                <span class="nv-feature__icon" aria-hidden="true"><?= nv_icon('history', 22) ?></span>
                <div>
                    <h3 class="nv-feature__title">Riwayat tabungan</h3>
                    <p class="nv-feature__text">Lihat semua setoran dan penarikan yang pernah dilakukan.</p>
                </div>
            </article>

            <article class="nv-feature">
                <span class="nv-feature__icon" aria-hidden="true"><?= nv_icon('archive', 22) ?></span>
                <div>
                    <h3 class="nv-feature__title">Arsip &amp; pulihkan</h3>
                    <p class="nv-feature__text">Simpan goal ke arsip dan pulihkan kapan saja.</p>
                </div>
            </article>

            <article class="nv-feature">
                <span class="nv-feature__icon" aria-hidden="true"><?= nv_icon('user', 22) ?></span>
                <div>
                    <h3 class="nv-feature__title">Akun pribadi</h3>
                    <p class="nv-feature__text">Data terpisah per pengguna; hanya Anda yang dapat melihatnya.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="nv-landing-section" id="cara-kerja">
    <div class="nv-container">
        <div class="nv-landing-head">
            <h2 class="nv-landing-head__title">Cara Kerja</h2>
            <p class="nv-landing-head__text">Empat langkah singkat sebelum Anda mulai mencatat tabungan.</p>
        </div>

        <ol class="nv-steps">
            <li class="nv-step">
                <span class="nv-step__num" aria-hidden="true">1</span>
                <h3 class="nv-step__title">Daftar</h3>
                <p class="nv-step__text">Buat akun dengan nama, email, dan kata sandi.</p>
            </li>
            <li class="nv-step">
                <span class="nv-step__num" aria-hidden="true">2</span>
                <h3 class="nv-step__title">Cek Email</h3>
                <p class="nv-step__text">Buka tautan verifikasi yang dikirim ke email Anda.</p>
            </li>
            <li class="nv-step">
                <span class="nv-step__num" aria-hidden="true">3</span>
                <h3 class="nv-step__title">Verifikasi</h3>
                <p class="nv-step__text">Aktifkan akun dan lengkapi profil Anda.</p>
            </li>
            <li class="nv-step">
                <span class="nv-step__num" aria-hidden="true">4</span>
                <h3 class="nv-step__title">Dashboard</h3>
                <p class="nv-step__text">Mulai atur tujuan tabungan Anda.</p>
            </li>
        </ol>
    </div>
</section>

<section class="nv-cta-band">
    <div class="nv-container">
        <div class="nv-cta-band__inner">
            <h2 class="nv-cta-band__title">Mulai atur tujuan tabunganmu hari ini.</h2>
            <div class="nv-cta-band__actions">
                <a class="nv-btn nv-btn--primary" href="/daftar">Daftar</a>
            </div>
        </div>
    </div>
</section>
<?php
require __DIR__ . '/../layouts/footer.php';
