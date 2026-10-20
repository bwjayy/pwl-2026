<?php
require_once __DIR__ . '/../layout/header.php';
$isEditMode = $isEdit ?? false;
$actionUrl = $isEditMode ? BASE_URL . '/actions/' . $id . '/update' : BASE_URL . '/actions/store';
$formData = $values ?? [];
?>

<div class="module-header">
    <div class="module-title">
        <h2><?= $isEditMode ? 'Ubah Jenis Aksi' : 'Tambah Jenis Aksi Baru' ?></h2>
        <p><?= $isEditMode ? 'Perbarui informasi nama atau deskripsi jenis operasi hak akses.' : 'Buat definisi hak akses atau aksi baru untuk otorisasi sistem.' ?></p>
    </div>
</div>

<div class="form-card">
    <form action="<?= $actionUrl ?>" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Nama Aksi</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="input-text" 
                placeholder="Contoh: Create, Read, Update, Delete, Export, Approve" 
                value="<?= htmlspecialchars($formData['name'] ?? '') ?>" 
                required
            >
            <?php if (isset($errors['name'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi Operasi</label>
            <textarea 
                id="description" 
                name="description" 
                class="textarea-custom" 
                rows="4" 
                placeholder="Jelaskan batasan atau hak istimewa yang diberikan oleh aksi ini..."
            ><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
            <div class="form-hint">Opsional. Penjelasan ringkas untuk dokumentasi hak akses teknis.</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isEditMode ? 'Simpan Perubahan' : 'Simpan Jenis Aksi' ?>
            </button>
            <a href="<?= BASE_URL ?>/actions" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
