<?php
require_once __DIR__ . '/../layout/header.php';
$isEditMode = $isEdit ?? false;
$isPasswordOnly = $isSelfPasswordOnly ?? false;
$actionUrl = $isEditMode ? BASE_URL . '/accounts/' . $id . '/update' : BASE_URL . '/accounts/store';
$formData = $values ?? [];
?>

<div class="module-header">
    <div class="module-title">
        <?php if ($isPasswordOnly): ?>
            <h2>Ganti Password Pribadi</h2>
            <p>Perbarui password akun Anda untuk keamanan akses sistem.</p>
        <?php else: ?>
            <h2><?= $isEditMode ? 'Ubah Data Akun' : 'Tambah Akun Baru' ?></h2>
            <p><?= $isEditMode ? 'Perbarui informasi profil atau hak akses akun pengguna.' : 'Masukkan identitas dan hak akses akun baru ke dalam sistem.' ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="form-card">
    <?php if ($isPasswordOnly): ?>
        <div class="alert alert-success" style="background-color: #EFF6FF; color: #1E3A8A; border-color: #BFDBFE;">
            Identitas profil Anda (Nama, Email, NIM/NIP, dan Tipe Akun) dikelola oleh Administrator. Anda hanya dapat mengubah password login akun Anda.
        </div>
    <?php endif; ?>

    <form action="<?= $actionUrl ?>" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="input-text" 
                    placeholder="Nama lengkap pengguna" 
                    value="<?= htmlspecialchars($formData['name'] ?? '') ?>" 
                    <?= $isPasswordOnly ? 'readonly style="background-color: #F8FAFC; cursor: not-allowed;"' : 'required' ?>
                >
                <?php if (isset($errors['name'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="input-text" 
                    placeholder="email@pnj.ac.id" 
                    value="<?= htmlspecialchars($formData['email'] ?? '') ?>" 
                    <?= $isPasswordOnly ? 'readonly style="background-color: #F8FAFC; cursor: not-allowed;"' : 'required' ?>
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">
                <?php if ($isPasswordOnly): ?>
                    Password Baru <span style="color: #B91C1C;">*</span>
                <?php else: ?>
                    Password <?= $isEditMode ? '<span style="font-weight:normal;color:var(--text-muted);">(Biarkan kosong jika tidak ingin diubah)</span>' : '' ?>
                <?php endif; ?>
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="input-text" 
                placeholder="<?= $isPasswordOnly ? 'Masukkan password baru Anda' : ($isEditMode ? 'Masukkan password baru jika ingin mengubah' : 'Password akun baru') ?>"
                <?= ($isEditMode && !$isPasswordOnly) ? '' : 'required' ?>
            >
            <?php if (isset($errors['password'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['password']) ?></div>
            <?php endif; ?>
        </div>

        <?php if (!$isPasswordOnly): ?>
            <div class="form-row">
                <div class="form-group">
                    <label for="account_type_id" class="form-label">Tipe Akun</label>
                    <select id="account_type_id" name="account_type_id" class="input-select" required>
                        <option value="">Pilih Tipe Akun</option>
                        <?php foreach ($accountTypes as $type): ?>
                            <option 
                                value="<?= $type['id'] ?>"
                                <?= ($formData['account_type_id'] ?? '') === $type['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['account_type_id'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['account_type_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status Akun</label>
                    <select id="status" name="status" class="input-select" required>
                        <option value="Aktif" <?= ($formData['status'] ?? 'Aktif') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= ($formData['status'] ?? '') === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                    <?php if (isset($errors['status'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['status']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="identification_type" class="form-label">Jenis Identitas</label>
                    <select id="identification_type" name="identification_type" class="input-select" required>
                        <option value="NIM" <?= ($formData['identification_type'] ?? 'NIM') === 'NIM' ? 'selected' : '' ?>>NIM (Mahasiswa)</option>
                        <option value="NIP" <?= ($formData['identification_type'] ?? '') === 'NIP' ? 'selected' : '' ?>>NIP (Dosen / Tenaga Pendidik)</option>
                    </select>
                    <?php if (isset($errors['identification_type'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['identification_type']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="identification_number" class="form-label">Nomor Identitas (NIM / NIP)</label>
                    <input 
                        type="text" 
                        id="identification_number" 
                        name="identification_number" 
                        class="input-text" 
                        placeholder="Contoh: 201012121 atau 520000000000000746" 
                        value="<?= htmlspecialchars($formData['identification_number'] ?? '') ?>" 
                        required
                    >
                    <?php if (isset($errors['identification_number'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['identification_number']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isPasswordOnly ? 'Simpan Password Baru' : ($isEditMode ? 'Simpan Perubahan' : 'Simpan Akun') ?>
            </button>
            <a href="<?= BASE_URL ?>/accounts" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
