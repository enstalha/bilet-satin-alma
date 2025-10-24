<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold">Hesabım</h2>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success_balance_added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Bakiyeniz başarıyla güncellendi.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (str_contains($_GET['status'], 'error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                    switch ($_GET['status']) {
                        case 'error_invalid_amount': echo 'Lütfen geçerli bir miktar girin.'; break;
                        case 'error_balance_update': echo 'Bakiye güncellenirken bir hata oluştu.'; break;
                        default: echo 'Bilinmeyen bir hata oluştu.'; break;
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card shadow-lg rounded-4 border-0 mt-4 mb-4">
        <div class="card-header bg-secondary text-white fw-bold rounded-top-4">Hesap Bilgileri</div>
        <div class="card-body">
            <p><i class="fa-solid fa-user me-2"></i><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><i class="fa-solid fa-envelope me-2"></i><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><i class="fa-solid fa-wallet me-2"></i><strong>Mevcut Bakiye:</strong> <span class="text-success fw-bold"><?php echo number_format($user['balance'], 2, ',', '.'); ?> TL</span></p>
        </div>
    </div>
    
    
    <div class="card shadow-lg rounded-2 border-0 mt-5">
        <div class="card-header bg-secondary text-white fw-bold rounded-top-4">Bakiye Ekle</div>
        <div class="card-body">
            <form action="/index.php?page=add-balance" method="POST">
                <div class="mb-3">
                    <label for="amount" class="form-label fw-semibold">Eklenecek Tutar (TL)</label>
                    <div class="input-group">
                        <span class="input-group-text">₺</span>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" min="1" required>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-secondary">Ödeme Bilgileri</h5>

                <div class="mb-3">
                    <label for="card_number" class="form-label fw-semibold">Kart Numarası</label>
                    <input type="text" class="form-control" id="card_number" placeholder="XXXX XXXX XXXX XXXX" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label for="expiry_date" class="form-label fw-semibold">Son Kullanma Tarihi</label>
                        <input type="text" class="form-control" id="expiry_date" placeholder="AA/YY" required>
                    </div>
                    <div class="col-md-5">
                        <label for="cvv" class="form-label fw-semibold">CVV</label>
                        <input type="text" class="form-control" id="cvv" placeholder="XXX" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold">
                    <i class="fa-solid fa-credit-card me-2"></i> Ödeme Yap ve Bakiye Ekle
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
