<?php
require_once __DIR__ . '/../layout/header.php';
$isEditMode = $isEdit ?? false;
$actionUrl = $isEditMode ? BASE_URL . '/account-type/' . $id . '/update' : BASE_URL . '/account-type/store';
$formData = $values ?? [];
?>

<div class="module-header">
    <div class="module-title">
        <h2><?= $isEditMode ? 'Ubah Tipe Akun' : 'Tambah Tipe Akun Baru' ?></h2>
        <p><?= $isEditMode ? 'Perbarui informasi nama atau deskripsi peranan pengguna.' : 'Buat klasifikasi tipe akun baru untuk menentukan hak akses pengguna.' ?></p>
    </div>
</div>

<div class="form-card">
    <form action="<?= $actionUrl ?>" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Nama Tipe Akun</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="input-text" 
                placeholder="Contoh: Admin, Dosen, Mahasiswa, Staf" 
                value="<?= htmlspecialchars($formData['name'] ?? '') ?>" 
                required
            >
            <?php if (isset($errors['name'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea 
                id="description" 
                name="description" 
                class="textarea-custom" 
                rows="4" 
                placeholder="Jelaskan cakupan peranan atau wewenang tipe akun ini..."
            ><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
            <div class="form-hint">Opsional. Memberikan kejelasan tugas bagi pengguna dengan tipe akun ini.</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isEditMode ? 'Simpan Perubahan' : 'Simpan Tipe Akun' ?>
            </button>
            <a href="<?= BASE_URL ?>/account-type" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
