<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="module-header">
    <div class="module-title">
        <h2>Manajemen Tipe Akun</h2>
        <p>Definisikan klasifikasi akun pengguna seperti Administrator, Dosen, atau Mahasiswa.</p>
    </div>
    <a href="<?= BASE_URL ?>/account-type/create" class="btn btn-primary">
        + Tambah Tipe Akun
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
    <form action="<?= BASE_URL ?>/account-type" method="GET" class="search-form">
        <input 
            type="search" 
            name="q" 
            class="input-text" 
            placeholder="Cari berdasarkan nama atau deskripsi tipe akun..." 
            value="<?= htmlspecialchars($search ?? '') ?>"
        >
        <button type="submit" class="btn btn-secondary">Cari</button>
        <?php if (!empty($search)): ?>
            <a href="<?= BASE_URL ?>/account-type" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>
    <div>
        <span style="font-size: 0.8125rem; color: var(--text-muted);">
            Total Data: <strong><?= count($types) ?></strong> tipe
        </span>
    </div>
</div>

<div class="table-card">
    <?php if (empty($types)): ?>
        <div class="empty-state">
            <h3>Tidak Ada Data Tipe Akun</h3>
            <p>
                <?= !empty($search) 
                    ? 'Tidak ditemukan tipe akun yang cocok dengan kata kunci pencarian Anda.' 
                    : 'Belum ada tipe akun yang tersimpan dalam sistem.' ?>
            </p>
            <a href="<?= BASE_URL ?>/account-type/create" class="btn btn-primary btn-sm">+ Tambah Tipe Akun Baru</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Tipe Akun</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Dibuat</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($types as $row): ?>
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
                                    <a href="<?= BASE_URL ?>/account-type/<?= $row['id'] ?>/edit" class="btn btn-secondary btn-sm">
                                        Edit
                                    </a>
                                    <form action="<?= BASE_URL ?>/account-type/<?= $row['id'] ?>/delete" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tipe akun ini (soft delete)?');">
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

