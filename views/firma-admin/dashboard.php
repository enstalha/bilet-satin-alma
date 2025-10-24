<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <?php echo isset($company['name']) ? htmlspecialchars($company['name']) . ' - ' : 'Firma'; ?> Yönetim Paneli
        </h2>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="alert alert-success">Yeni sefer başarıyla eklendi.</div>
        <?php elseif ($_GET['status'] === 'update_success'): ?>
            <div class="alert alert-success">Sefer başarıyla güncellendi.</div>
        <?php elseif ($_GET['status'] === 'delete_success'): ?>
            <div class="alert alert-success">Sefer başarıyla silindi.</div>
        <?php elseif ($_GET['status'] === 'coupon_added'): ?>
            <div class="alert alert-success">Yeni kupon başarıyla eklendi.</div>
        <?php elseif ($_GET['status'] === 'coupon_deleted'): ?>
            <div class="alert alert-success">Kupon başarıyla silindi.</div>
        <?php elseif (str_contains($_GET['status'], 'error')): ?>
            <div class="alert alert-danger">İşlem sırasında bir hata oluştu. Detay: <?php echo htmlspecialchars($_GET['status']); ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="accordion" id="adminAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Mevcut Seferler <span class="badge bg-secondary ms-2"><?php echo count($trips); ?></span>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                    <?php if (empty($trips)): ?>
                        <div class="alert alert-info">Henüz firmanıza ait bir sefer bulunmamaktadır.</div>
                    <?php else: ?>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Güzergah</th>
                                    <th>Kalkış Zamanı</th>
                                    <th>Fiyat</th>
                                    <th>Kapasite</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($trips as $trip): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($trip['departure_city']); ?> ➔ <?php echo htmlspecialchars($trip['destination_city']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($trip['departure_time'])); ?></td>
                                        <td><?php echo htmlspecialchars($trip['price']); ?> TL</td>
                                        <td><?php echo htmlspecialchars($trip['capacity']); ?></td>
                                        <td>
                                            <a href="/index.php?page=edit-trip&id=<?php echo $trip['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                            <a href="/index.php?page=delete-trip&id=<?php echo $trip['id']; ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Bu seferi kalıcı olarak iptal etmek istediğinizden emin misiniz? Bu işlem geri alınamaz.');">
                                               Seferi iptal et
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Yeni Sefer Ekle
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                   <form action="/index.php?page=add-trip" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="departure_city" class="form-label">Kalkış Şehri</label>
                                <input type="text" class="form-control" id="departure_city" name="departure_city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="destination_city" class="form-label">Varış Şehri</label>
                                <input type="text" class="form-control" id="destination_city" name="destination_city" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="departure_time" class="form-label">Kalkış Zamanı</label>
                                <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="arrival_time" class="form-label">Varış Zamanı</label>
                                <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Fiyat (TL)</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Kapasite (Koltuk Sayısı)</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Seferi Ekle</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCoupons">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCoupons" aria-expanded="false" aria-controls="collapseCoupons">
                    İndirim Kuponları Yönetimi <span class="badge bg-secondary ms-2"><?php echo count($coupons); ?></span>
                </button>
            </h2>
            <div id="collapseCoupons" class="accordion-collapse collapse" aria-labelledby="headingCoupons" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                     <div class="row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <h5>Mevcut Kuponlar</h5>
                            <?php if (empty($coupons)): ?>
                                <p>Henüz firmanıza ait bir indirim kuponu bulunmamaktadır.</p>
                            <?php else: ?>
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Kod</th>
                                            <th>İndirim (%)</th>
                                            <th>Limit</th>
                                            <th>Son Tarih</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($coupons as $coupon): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($coupon['code']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($coupon['discount'] * 100); ?>%</td>
                                                <td><?php echo htmlspecialchars($coupon['usage_limit'] ?? 'Sınırsız'); ?></td>
                                                <td><?php echo $coupon['expire_date'] ? date('d/m/Y', strtotime($coupon['expire_date'])) : 'Süresiz'; ?></td>
                                                <td>
                                                    <a href="/index.php?page=edit-coupon&id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                                    <a href="/index.php?page=delete-coupon&id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kuponu silmek istediğinizden emin misiniz?');">Sil</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-5">
                            <h5>Yeni Kupon Ekle</h5>
                            <form action="/index.php?page=add-coupon" method="POST">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kupon Kodu</label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="discount" class="form-label">İndirim Oranı (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" placeholder="Örn: 10" required>
                                    <div class="form-text">Örn: %10 indirim için 10 yazın.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="usage_limit" class="form-label">Kullanım Limiti (Opsiyonel)</label>
                                    <input type="number" class="form-control" id="usage_limit" name="usage_limit" placeholder="Boş bırakırsanız sınırsız olur">
                                </div>
                                <div class="mb-3">
                                    <label for="expire_date" class="form-label">Son Kullanma Tarihi (Opsiyonel)</label>
                                    <input type="date" class="form-control" id="expire_date" name="expire_date">
                                </div>
                                <button type="submit" class="btn btn-primary">Kupon Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> </div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>