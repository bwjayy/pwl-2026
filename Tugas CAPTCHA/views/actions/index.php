<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="module-header">
    <div class="module-title">
        <h2>Manajemen Jenis Aksi</h2>
        <p>Definisikan operasi hak akses atau permissions (misal: Create, Read, Update, Delete) yang dapat dilakukan dalam sistem.</p>
    </div>
    <a href="<?= BASE_URL ?>/actions/create" class="btn btn-primary">
        + Tambah Jenis Aksi
    </a>
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
    <form action="<?= BASE_URL ?>/actions" method="GET" class="search-form">
        <input 
            type="search" 
            name="q" 
            class="input-text" 
            placeholder="Cari berdasarkan nama atau deskripsi aksi..." 
            value="<?= htmlspecialchars($search ?? '') ?>"
        >
        <button type="submit" class="btn btn-secondary">Cari</button>
        <?php if (!empty($search)): ?>
            <a href="<?= BASE_URL ?>/actions" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
    <div>
        <span style="font-size: 0.8125rem; color: var(--text-muted);">
            Total Data: <strong><?= count($actions) ?></strong> jenis aksi
        </span>
    </div>
</div>

<div class="table-card">
    <?php if (empty($actions)): ?>
        <div class="empty-state">
            <h3>Tidak Ada Data Jenis Aksi</h3>
            <p>
                <?= !empty($search) 
                    ? 'Tidak ditemukan jenis aksi yang cocok dengan kata kunci pencarian Anda.' 
                    : 'Belum ada jenis aksi yang tersimpan dalam sistem.' ?>
            </p>
            <a href="<?= BASE_URL ?>/actions/create" class="btn btn-primary btn-sm">+ Tambah Jenis Aksi Baru</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Aksi</th>
                        <th>Deskripsi Operasi</th>
                        <th>Tanggal Dibuat</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actions as $row): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($row['description'] ?? '-') ?></td>
                            <td>
                                <span class="mono-tag">
                                    <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <a href="<?= BASE_URL ?>/actions/<?= $row['id'] ?>/edit" class="btn btn-secondary btn-sm">
                                        Edit
                                    </a>
                                    <form action="<?= BASE_URL ?>/actions/<?= $row['id'] ?>/delete" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data jenis aksi ini (soft delete)?');">
                                        <button type="submit" class="btn btn-danger-outline btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
