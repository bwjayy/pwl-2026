<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="table-card" style="padding: 48px 32px; text-align: center; max-width: 640px; margin: 40px auto;">
    <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 12px; background: #FEE2E2; color: #B91C1C; font-size: 1.8rem; font-weight: 700; margin-bottom: 20px;">
        403
    </div>
    <h2 style="font-size: 1.4rem; color: var(--text-main); margin-bottom: 8px;">Akses Ditolak</h2>
    <p style="color: var(--text-muted); font-size: 0.9375rem; margin-bottom: 24px; line-height: 1.6;">
        <?= htmlspecialchars($message ?? 'Anda tidak memiliki hak akses yang cukup untuk membuka halaman atau melakukan aksi ini.') ?>
    </p>
    <div>
        <a href="<?= BASE_URL ?>/accounts" class="btn btn-primary">
            Kembali ke Beranda
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
