<!-- NAVORA — Public Landing Page -->
<!-- DEC-010: Exact BESTCO blue palette -->
<!-- DEC-019: Plus Jakarta Sans + JetBrains Mono (tabular-nums) -->
<!-- No external images, no framework, vanilla JS only -->

<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['pageTitle'] ?? 'NAVORA') ?></title>
    
    <!-- CSS Custom Properties: DEC-010 palette + DEC-019 typography -->
    <style>
        :root {
            /* DEC-010: Exact 11-step BESTCO primary blue scale */
            --nv-blue-50: #eff6ff;
            --nv-blue-100: #dbeafe;
            --nv-blue-200: #bfdbfe;
            --nv-blue-300: #93c5fd;
            --nv-blue-400: #60a5fa;
            --nv-blue-500: #3b82f6;
            --nv-blue-600: #2563eb;
            --nv-blue-700: #1d4ed8;
            --nv-blue-800: #1e40af;
            --nv-blue-900: #1e3a8a;
            --nv-blue-950: #172554;
            
            /* Neutrals */
            --nv-white: #FFFFFF;
            --nv-gray-50: #FBFCFE;
            --nv-gray-100: #EDF1F5;
            --nv-gray-200: #DDE4EB;
            --nv-gray-300: #C9D3DC;
            --nv-gray-400: #93A2AF;
            --nv-gray-500: #6B7C8C;
            --nv-gray-600: #5A6B7B;
            --nv-gray-700: #3D5566;
            --nv-gray-800: #1F3A4D;
            --nv-gray-900: #0A2540;
            --nv-gray-950: #06182A;
            
            /* Rules from DEC-010/DEC-019 */
            --primary-action: var(--nv-blue-600);
            --primary-action-hover: var(--nv-blue-700);
            --surface-subtle: var(--nv-blue-50);
            --surface-card: var(--nv-white);
            --border-subtle: var(--nv-gray-200);
            --border-control: var(--nv-gray-400);
            --focus-ring: var(--nv-blue-600);
            --text-primary: var(--nv-gray-900);
            --text-secondary: var(--nv-gray-600);
            --text-muted: var(--nv-gray-500);
        }
        
        /* DEC-019 typography: Plus Jakarta Sans */
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', 'system UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: var(--nv-white);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', 'Inter', 'system UI', sans-serif;
            font-weight: 600;
            line-height: 1.2;
            margin-top: 0;
        }
        
        /* Type scale from DEC-019 */
        .display { font-size: 40px; line-height: 48px; }
        .h1 { font-size: 30px; line-height: 38px; }
        .h2 { font-size: 24px; line-height: 32px; }
        .h3 { font-size: 20px; line-height: 28px; }
        .body { font-size: 16px; line-height: 24px; }
        .body-lg { font-size: 18px; line-height: 28px; }
        .body-sm { font-size: 14px; line-height: 20px; }
        .caption { font-size: 12px; line-height: 16px; }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-family: 'Plus Jakarta Sans', 'Inter', 'system UI', sans-serif;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            touch-action: manipulation;
            transition: all 0.2s ease;
            min-height: 44px; /* 44px touch target */
        }
        
        .btn-primary {
            background-color: var(--primary-action);
            color: var(--nv-white);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-action-hover);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--primary-action);
            border: 1px solid var(--primary-action);
        }
        
        .btn-secondary:hover {
            background-color: var(--nv-blue-50);
        }
        
        /* Focus style */
        .btn:focus-visible {
            outline: 2px solid var(--focus-ring);
            outline-offset: 2px;
        }
        
        /* Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        
        /* Desktop header */
        .header-desktop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-subtle);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background-color: var(--nv-white);
            height: 64px;
        }
        
        .header-desktop .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Plus Jakarta Sans', 'Inter', 'system UI', sans-serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--nv-blue-900);
            text-decoration: none;
        }
        
        .header-desktop .nav {
            display: flex;
            gap: 32px;
        }
        
        .header-desktop .nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }
        
        .header-desktop .nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-action);
            transition: width 0.2s ease;
        }
        
        .header-desktop .nav a:hover::after {
            width: 100%;
        }
        
        .header-desktop .cta {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .header-desktop .btn {
            padding: 8px 16px;
        }
        
        /* Hero section */
        .hero {
            padding: 80px 0;
            position: relative;
        }
        
        .hero .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            align-items: center;
        }
        
        .hero .text-content {
            max-width: 560px;
        }
        
        .hero .headline {
            display: block;
            font-size: 40px;
            line-height: 48px;
            font-weight: 600;
            color: var(--nv-blue-900);
            margin-bottom: 16px;
        }
        
        .hero .subhead {
            font-size: 18px;
            line-height: 28px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }
        
        .hero .visual {
            position: relative;
            aspect-ratio: 1;
            background-color: var(--nv-blue-50);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Feature grid */
        .features {
            padding: 60px 0;
        }
        
        .features .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        
        .feature-card {
            background-color: var(--nv-white);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: border-color 0.2s ease;
        }
        
        .feature-card:hover {
            border-color: var(--nv-blue-300);
        }
        
        .feature-card .icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }
        
        .feature-card .icon svg {
            width: 100%;
            height: 100%;
        }
        
        .feature-card .icon path,
        .feature-card .icon circle,
        .feature-card .icon rect {
            stroke: currentColor;
            stroke-width: 1.75;
            fill: none;
        }
        
        .feature-card .title {
            font-size: 14px;
            font-weight: 600;
        }
        
        .feature-card .desc {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        
        /* Cara Kerja */
        .how-it-works {
            padding: 60px 0;
        }
        
        .how-it-works .steps {
            display: flex;
            gap: 32px;
            margin-top: 32px;
        }
        
        .how-it-works .step {
            flex: 1;
            text-align: center;
        }
        
        .how-it-works .step-number {
            width: 32px;
            height: 32px;
            background-color: var(--nv-blue-50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-action);
        }
        
        .how-it-works .step-desc {
            font-size: 12px;
            color: var(--text-secondary);
        }
        
        /* Closing CTA */
        .closing-cta {
            background-color: var(--nv-blue-50);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            margin: 40px 0;
        }
        
        .closing-cta .btn-primary {
            width: 100%;
            padding: 14px 24px;
        }
        
        /* Footer */
        .footer {
            padding: 32px 0;
            border-top: 1px solid var(--border-subtle);
        }
        
        .footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer .brand {
            font-family: 'Plus Jakarta Sans', 'Inter', 'system UI', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--nv-blue-900);
            text-decoration: none;
        }
        
        .footer .copyright {
            font-size: 12px;
            color: var(--text-muted);
        }
        
        /* Mobile drawer */
        .mobile-menu-btn {
            width: 40px;
            height: 40px;
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        
        .mobile-menu-btn span {
            display: block;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            height: 2px;
            width: 24px;
            background-color: var(--nv-blue-900);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn span::before,
        .mobile-menu-btn span::after {
            content: '';
            position: absolute;
            left: 0;
            width: 24px;
            height: 2px;
            background-color: var(--nv-blue-900);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn span::before {
            top: -8px;
        }
        
        .mobile-menu-btn span::after {
            bottom: -8px;
        }
        
        .mobile-menu-btn.active span {
            background-color: transparent;
        }
        
        .mobile-menu-btn.active span::before {
            top: 0;
            transform: rotate(45deg);
        }
        
        .mobile-menu-btn.active span::after {
            bottom: 0;
            transform: rotate(-45deg);
        }
        
        /* Mobile nav drawer */
        .drawer {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 280px;
            background-color: var(--nv-white);
            z-index: 100;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            padding: 80px 24px 24px;
        }
        
        .drawer.open {
            transform: translateX(0);
        }
        
        .drawer .close-btn {
            width: 32px;
            height: 32px;
            position: absolute;
            top: 24px;
            right: 24px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        
        .drawer .close-btn span {
            display: block;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            height: 2px;
            width: 20px;
            background-color: var(--nv-blue-900);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .drawer .close-btn span::before,
        .drawer .close-btn span::after {
            content: '';
            position: absolute;
            left: 0;
            width: 20px;
            height: 2px;
            background-color: var(--nv-blue-900);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .drawer .close-btn span::before {
            top: -6px;
        }
        
        .drawer .close-btn span::after {
            bottom: -6px;
        }
        
        .drawer .close-btn.active span {
            background-color: transparent;
        }
        
        .drawer .close-btn.active span::before {
            top: 0;
            transform: rotate(45deg);
        }
        
        .drawer .close-btn.active span::after {
            bottom: 0;
            transform: rotate(-45deg);
        }
        
        .drawer .nav-list {
            list-style: none;
            padding: 0;
            margin: 24px 0 32px;
        }
        
        .drawer .nav-list li {
            margin-bottom: 16px;
        }
        
        .drawer .nav-list a {
            color: var(--text-primary);
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .drawer .nav-list a:hover {
            color: var(--primary-action);
        }
        
        .drawer .nav-list .divider {
            height: 1px;
            background-color: var(--border-subtle);
            margin: 16px 0;
        }
        
        .drawer .cta-group {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid var(--border-subtle);
        }
        
        /* Responsive */
        @media (max-width: 1023px) {
            .header-desktop {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero .container {
                grid-template-columns: 1fr;
            }
            
            .hero .visual {
                order: -1;
                margin-bottom: 24px;
            }
            
            .features .grid {
                grid-template-columns: 1fr;
            }
            
            .footer .container {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
        
        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" aria-label="Buka menu" id="mobileMenuBtn" onclick="toggleDrawer()">
        <span></span>
    </button>
    
    <!-- Drawer Navigation -->
    <div class="drawer" id="drawer" aria-hidden="true">
        <button class="close-btn" aria-label="Tutup menu" onclick="closeDrawer()" style="position: fixed; top: 20px; right: 20px;">
            <span></span>
        </button>
        
        <ul class="nav-list">
            <li><a href="#tentang">Tentang NAVORA</a></li>
            <li><a href="#fitur">Fitur</a></li>
            <li><a href="#cara-kerja">Cara Kerja</a></li>
            <hr class="divider">
            <li><a href="/masuk">Masuk</a></li>
            <li><a href="/daftar">Daftar</a></li>
        </ul>
        
        <div class="cta-group">
            <button class="btn btn-primary" onclick="window.location.href='/daftar'">Daftar</button>
            <button class="btn btn-secondary" onclick="window.location.href='/masuk'">Masuk</button>
        </div>
    </div>
    
    <!-- Desktop Header -->
    <header class="header-desktop" aria-label="Header utama">
        <a href="/" class="logo">NAVORA</a>
        
        <nav class="nav">
            <a href="#tentang">Tentang</a>
            <a href="#fitur">Fitur</a>
            <a href="#cara-kerja">Cara Kerja</a>
        </nav>
        
        <div class="cta">
            <a href="/masuk" class="btn btn-secondary">Masuk</a>
            <a href="/daftar" class="btn btn-primary">Daftar</a>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero" id="awal">
            <div class="container">
                <div class="text-content">
                    <div class="headline">Menabung jadi lebih terarah.</div>
                    <p class="subhead">Buat tujuan tabungan, catat setoran dan penarikan, lalu pantau progres sampai tercapai.</p>
                </div>
                <div class="visual">
                    <!-- Hero visual: SVG abstrak target/progres/koin -->
                    <svg viewBox="0 0 100 100" class="hero-svg" style="width: 100%; height: 100%;">
                        <!-- Target ring: 3 lingkaran bertumpuk -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="currentColor" stroke-width="1.75" style="stroke: var(--nv-blue-300); transform: rotate(-90deg);" />
                        <circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="1.75" style="stroke: var(--nv-blue-400); transform: rotate(-90deg);" />
                        <circle cx="50" cy="50" r="22" fill="none" stroke="currentColor" stroke-width="1.75" style="stroke: var(--nv-blue-500); transform: rotate(-90deg);" />
                        
                        <!-- Progres bar -->
                        <g transform="translate(20, 70)">
                            <rect width="60" height="8" fill="none" stroke="currentColor" stroke-width="1.75" style="stroke: var(--nv-blue-500);" />
                        </g>
                        
                        <!-- 3 koin -->
                        <g transform="translate(10, 10)">
                            <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.75" />
                            <circle cx="34" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.75" />
                            <circle cx="58" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.75" />
                        </g>
                        
                        <g transform="translate(70, 45)">
                            <polygon points="50,5 61,52 50,43 39,52" fill="none" stroke="currentColor" stroke-width="1.75" />
                        </g>
                    </svg>
                </div>
            </div>
        </section>
        
        <!-- Tentang NAVORA -->
        <section style="padding: 60px 0;">
            <div class="container">
                <div style="max-width: 800px; margin: 0 auto;">
                    <h2 class="h2" style="color: var(--nv-blue-900); margin-bottom: 24px;">Tentang NAVORA</h2>
                    <p class="body" style="color: var(--text-secondary); margin-bottom: 32px;">
                        NAVORA membantu mencatat tabungan untuk satu tujuan yang jelas: target harga, catatan setoran, dan progres yang mudah dibaca.
                    </p>
                    <a href="#awal" style="color: var(--primary-action); text-decoration: none;">Kembali ke atas</a>
                </div>
            </div>
        </section>
        
        <!-- Fitur Utama -->
        <section class="features" id="fitur">
            <div class="container">
                <div style="max-width: 800px; margin: 0 auto;">
                    <h2 class="h2" style="color: var(--nv-blue-900); margin-bottom: 40px;">Fitur Utama</h2>
                    <div class="grid">
                        <!-- Card 1: Target harga & tanggal opsional -->
                        <article class="feature-card">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" style="width: 100%; height: 100%;">
                                    <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.75" />
                                    <line x1="15" y1="9" x2="9" y2="15" fill="none" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="title">Target harga & tanggal opsional</div>
                                <div class="desc">Atur target nilai yang ingin capai dan tanggal tujuan (opsional)</div>
                            </div>
                        </article>
                        
                        <!-- Card 2: Setoran & penarikan -->
                        <article class="feature-card">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" style="width: 100%; height: 100%;">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" fill="none" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M2 17l10 5 10-5" fill="none" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M2 12l10 5 10-5" fill="none" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="title">Setoran & penarikan</div>
                                <div class="desc">Catat setoran uang baru atau penarikan sesuai kebutuhan</div>
                            </div>
                        </article>
                        
                        <!-- Card 3: Progres saldo -->
                        <article class="feature-card">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" style="width: 100%; height: 100%;">
                                    <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z" fill="none" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="title">Progres saldo</div>
                                <div class="desc">Tampak seberapa jauh capai dari target setoran</div>
                            </div>
                        </article>
                        
                        <!-- Card 4: Riwayat tabungan -->
                        <article class="feature-card">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" style="width: 100%; height: 100%;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10h-8l-2-6h12z" fill="none" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="title">Riwayat tabungan</div>
                                <div class="desc">Lihat semua setoran dan penarikan yang pernah dilakukan</div>
                            </div>
                        </article>
                        
                        <!-- Card 5: Arsip & pulihkan goal -->
                        <article class="feature-card">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" style="width: 100%; height: 100%;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10h-8l-2-6h12z" fill="none" stroke="currentColor" stroke-width="1.75" />
                                    <polyline points="14 2 14 8 20 8" fill="none" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="title">Arsip & pulihkan</div>
                                <div class="desc">Simpan goal ke arsip dan pulihkan kapan saja</div>
                            </div>
                        </article>
                        
                        <!-- Card 6: Akun pribadi -->
                        <article class="feature-card">
                            <div class="icon">
                                <svg viewBox="0 0 24 24" style="width: 100%; height: 100%;">
                                    <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.75" />
                                    <line x1="8" y1="12" x2="21" y2="12" fill="none" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="title">Akun pribadi</div>
                                <div class="desc">Isolasi data per user, hanya Anda yang melihat</div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Cara Kerja -->
        <section class="how-it-works" id="cara-kerja">
            <div class="container">
                <div style="max-width: 800px; margin: 0 auto;">
                    <h2 class="h2" style="color: var(--nv-blue-900); margin-bottom: 40px;">Cara Kerja</h2>
                    <div class="steps">
                        <div class="step">
                            <div class="step-number">①</div>
                            <div class="step-daftar">
                                <div>Daftar</div>
                                <div class="step-desc">Buat akun dengan email dan kata sandi</div>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">②</div>
                            <div class="step-check">
                                <div>Cek Email</div>
                                <div class="step-desc">Verifikasi email melalui link yang dikirim</div>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">③</div>
                            <div class="step-verifikasi">
                                <div>Verifikasi</div>
                                <div class="step-desk">Aktifkan akun dan lengkapikan profil</div>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">④</div>
                            <div class="step-dashboard">
                                <div>Dashboard</div>
                                <div class="step-desc">Mulai atur tujuan tabunganmu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Closing CTA -->
        <section class="closing-cta">
            <div class="container">
                <h3 style="color: var(--nv-blue-900); margin-bottom: 16px; font-size: 20px; line-height: 28px;">Mulai atur tujuan tabunganmu hari ini.</h3>
                <a href="/daftar" class="btn btn-primary">Daftar</a>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <a href="#" class="brand">NAVORA</a>
            <span class="copyright">© 2026 NAVORA. Semua hak dilindungi.</span>
        </div>
    </footer>
    
    <!-- Mobile Menu JavaScript -->
    <script>
        function toggleDrawer() {
            const drawer = document.getElementById('drawer');
            const menuBtn = document.getElementById('mobileMenuBtn');
            drawer.classList.toggle('open');
            menuBtn.classList.toggle('active');
            // Trap focus logic simplified
            if (drawer.classList.contains('open')) {
                drawer.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            } else {
                drawer.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        }
        
        function closeDrawer() {
            const drawer = document.getElementById('drawer');
            const menuBtn = document.getElementById('mobileMenuBtn');
            drawer.classList.remove('open');
            menuBtn.classList.remove('active');
            drawer.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        
        // Close drawer on nav link click
        document.querySelectorAll('.drawer .nav-list a').forEach(link => {
            link.addEventListener('click', closeDrawer);
        });
        
        // Close drawer on overlay click
        const drawer = document.getElementById('drawer');
        if (drawer) {
            drawer.addEventListener('click', (e) => {
                if (e.target === drawer || e.target.classList.contains('drawer')) {
                    closeDrawer();
                }
            });
        }
    </script>
</body>
</html>