<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="module-header">
    <div class="module-title">
        <h2>Manajemen Akun</h2>
        <p>Kelola data akun pengguna, tipe akun, dan kredensial akses sistem.</p>
    </div>
    <?php if (Auth::isAdmin()): ?>
        <a href="<?= BASE_URL ?>/accounts/create" class="btn btn-primary">
            + Tambah Akun
        </a>
    <?php endif; ?>
</div>

<?php if (!empty($flashSuccess)): ?>
    <div class="alert alert-success" role="status">
        <?= htmlspecialchars($flashSuccess) ?>
    </div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="alert alert-error" role="alert">
        <?= htmlspecialchars($flashError) ?>
    </div>
<?php endif; ?>

<div class="filter-panel">
    <form action="<?= BASE_URL ?>/accounts" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; flex-grow: 1; align-items: center;">
        <input 
            type="search" 
            name="q" 
            class="input-text" 
            style="max-width: 300px;"
            placeholder="Cari nama, email, NIM/NIP..." 
            value="<?= htmlspecialchars($search ?? '') ?>"
        >
        <?php if (Auth::isAdmin()): ?>
            <input 
                type="text" 
                name="type" 
                class="input-text" 
                style="max-width: 220px;"
                placeholder="Filter tipe akun (teks)..." 
                value="<?= htmlspecialchars($typeFilter ?? '') ?>"
            >
        <?php endif; ?>
        <button type="submit" class="btn btn-secondary">Terapkan Filter</button>
        <?php if (!empty($search) || !empty($typeFilter)): ?>
            <a href="<?= BASE_URL ?>/accounts" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
    <div>
        <span style="font-size: 0.8125rem; color: var(--text-muted);">
            Total Data: <strong><?= count($accounts) ?></strong> akun
            <?php if (!Auth::isAdmin()): ?>
                (Direktori: <strong><?= htmlspecialchars(Auth::role()) ?></strong>)
            <?php endif; ?>
        </span>
    </div>
</div>

<div class="table-card">
    <?php if (empty($accounts)): ?>
        <div class="empty-state">
            <h3>Tidak Ada Data Akun</h3>
            <p>
                <?= !empty($search) 
                    ? 'Tidak ditemukan akun yang cocok dengan kata kunci pencarian Anda.' 
                    : 'Belum ada data akun yang tersimpan dalam sistem saat ini.' ?>
            </p>
            <a href="<?= BASE_URL ?>/accounts/create" class="btn btn-primary btn-sm">+ Tambah Akun Baru</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Identitas</th>
                        <th>Tipe Akun</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounts as $row): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <span class="mono-tag"><?= htmlspecialchars($row['identification_type']) ?>: <?= htmlspecialchars($row['identification_number']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['account_type_name'] ?? 'Tidak Terkait') ?></td>
                            <td>
                                <?php if ($row['status'] === 'Aktif'): ?>
                                    <span class="status-badge active">Aktif</span>
                                <?php else: ?>
                                    <span class="status-badge inactive">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?php if (Auth::isAdmin()): ?>
                                    <div style="display: inline-flex; gap: 8px;">
                                        <a href="<?= BASE_URL ?>/accounts/<?= $row['id'] ?>/edit" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                        <form action="<?= BASE_URL ?>/accounts/<?= $row['id'] ?>/delete" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data akun ini (soft delete)?');">
                                            <button type="submit" class="btn btn-danger-outline btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <?php if ($row['id'] === Auth::id()): ?>
                                        <a href="<?= BASE_URL ?>/accounts/<?= $row['id'] ?>/edit" class="btn btn-primary btn-sm">
                                            Ganti Password
                                        </a>
                                    <?php else: ?>
                                        <span class="mono-tag" style="font-size: 0.75rem; color: var(--text-muted);">
                                            Hanya Lihat
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
