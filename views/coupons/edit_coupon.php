<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Kupon Düzenle</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
                switch ($_GET['error']) {
                    case 'empty': echo 'Lütfen tüm zorunlu alanları doldurun.'; break;
                    case 'code_exists': echo 'Bu kupon kodu zaten başka bir kupon tarafından kullanılıyor.'; break;
                    default: echo 'Bir hata oluştu.'; break;
                }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="/index.php?page=<?php echo ($coupon['company_id'] === null) ? 'update-coupon-admin' : 'update-coupon-firma-admin'; ?>" method="POST">
                
                <input type="hidden" name="coupon_id" value="<?php echo htmlspecialchars($coupon['id']); ?>">

                <div class="mb-3">
                    <label for="code" class="form-label">Kupon Kodu</label>
                    <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($coupon['code']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="discount" class="form-label">İndirim Oranı (%)</label>
                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($coupon['discount'] * 100); ?>" placeholder="Örn: 10" required>
                    <div class="form-text">Örn: %10 indirim için 10 yazın.</div>
                </div>
                <div class="mb-3">
                    <label for="usage_limit" class="form-label">Kullanım Limiti (Opsiyonel)</label>
                    <input type="number" class="form-control" id="usage_limit" name="usage_limit" value="<?php echo htmlspecialchars($coupon['usage_limit'] ?? ''); ?>" placeholder="Boş bırakırsanız sınırsız olur">
                </div>
                <div class="mb-3">
                    <label for="expire_date" class="form-label">Son Kullanma Tarihi (Opsiyonel)</label>
                    <input type="date" class="form-control" id="expire_date" name="expire_date" value="<?php echo htmlspecialchars($coupon['expire_date'] ?? ''); ?>">
                </div>

                <?php if ($coupon['company_id'] !== null && isset($companies)): ?>
                    <div class="mb-3">
                       <label for="company_id" class="form-label">Ait Olduğu Firma</label>
                       <?php if(isset($_SESSION['user_role']) && trim($_SESSION['user_role']) === 'firma_admin'): ?>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($companies[0]['name'] ?? 'Bilinmiyor'); ?>" disabled readonly>
                            <input type="hidden" name="company_id_display" value="<?php echo htmlspecialchars($coupon['company_id']); ?>"> <?php else:  ?>
                           <select class="form-select" id="company_id" name="company_id" disabled> <?php foreach ($companies as $company): ?>
                                    <option value="<?php echo $company['id']; ?>" <?php echo ($coupon['company_id'] == $company['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($company['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                           </select>
                       <?php endif; ?>
                   </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                <a href="/index.php?page=<?php echo (isset($_SESSION['user_role']) && trim($_SESSION['user_role']) === 'admin') ? 'admin-dashboard' : 'firma-admin-dashboard'; ?>" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>