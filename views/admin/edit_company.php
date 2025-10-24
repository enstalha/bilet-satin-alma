<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h2 class="mb-4">Firmayı Düzenle</h2>

    <div class="card">
        <div class="card-body">
            <form action="/index.php?page=update-company" method="POST">
                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">

                <div class="mb-3">
                    <label for="company_name" class="form-label">Firma Adı</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" 
                           value="<?php echo htmlspecialchars($company['name']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                <a href="/index.php?page=admin-dashboard" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>