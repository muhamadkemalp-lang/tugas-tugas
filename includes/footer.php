<?php if (($layout ?? 'public') === 'admin'): ?>
        </div><!-- /content -->
    </main>
</div><!-- /admin-wrap -->
<?php else: ?>
</div><!-- /container -->
<footer class="footer-laporin text-center py-3">
    <small>&copy; <?= date('Y') ?> LAPORIN – Sistem Informasi Pengaduan Masyarakat</small>
</footer>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>
