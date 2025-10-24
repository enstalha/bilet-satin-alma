<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h2 class="mb-4">Firma Admini Düzenle</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php if ($_GET['error'] === 'empty'): ?>
                Lütfen tüm alanları doldurun.
            <?php elseif ($_GET['error'] === 'email_taken'): ?>
                Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.
            <?php else: ?>
                Bir hata oluştu.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="/index.php?page=update-firma-admin" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($adminToEdit['id']); ?>">

                <div class="mb-3">
                    <label for="fullname" class="form-label">Ad Soyad</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($adminToEdit['full_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($adminToEdit['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="company_id" class="form-label">Atanacak Firma</label>
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="" disabled>Firma Seçin...</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?php echo $company['id']; ?>" <?php echo ($adminToEdit['company_id'] == $company['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($company['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                <a href="/index.php?page=admin-dashboard" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>