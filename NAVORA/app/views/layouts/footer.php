<?php
// NAVORA — app/views/layouts/footer.php
// Closes the shell opened by header.php and loads the shared script.

$nvLayout = isset($nvLayout) && in_array($nvLayout, ['public', 'auth'], true) ? (string) $nvLayout : 'app';
?>
<?php if ($nvLayout === 'public'): ?>
    </main>

    <footer class="nv-site-footer">
        <div class="nv-container nv-site-footer__inner">
            <a class="nv-brand" href="/" aria-label="NAVORA — Beranda">
                <?= nv_logo_mark(20) ?>
                <span class="nv-wordmark__text">NAVORA</span>
            </a>
            <p class="nv-site-footer__copy">&copy; 2026 NAVORA. Semua hak dilindungi.</p>
        </div>
    </footer>
<?php elseif ($nvLayout === 'auth'): ?>
        </main>
        <footer class="nv-auth__footer">
            <p>&copy; 2026 NAVORA. Semua hak dilindungi.</p>
        </footer>
    </div>
<?php else: ?>
            </main>
        </div>
    </div>
<?php endif; ?>
<script src="/public/js/script.js"></script>
</body>
</html>
