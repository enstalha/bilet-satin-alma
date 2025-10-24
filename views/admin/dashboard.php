<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Paneli</h2>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'company_added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Yeni firma başarıyla eklendi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif ($_GET['status'] === 'company_updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Firma bilgileri başarıyla güncellendi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif ($_GET['status'] === 'company_deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Firma başarıyla silindi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif ($_GET['status'] === 'fa_added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Yeni firma admini başarıyla eklendi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif ($_GET['status'] === 'fa_updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Firma admini bilgileri başarıyla güncellendi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif ($_GET['status'] === 'fa_deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Firma admini başarıyla silindi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif ($_GET['status'] === 'coupon_added'): ?>
             <div class="alert alert-success alert-dismissible fade show" role="alert">Yeni kupon başarıyla eklendi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
         <?php elseif ($_GET['status'] === 'coupon_updated'): ?>
             <div class="alert alert-success alert-dismissible fade show" role="alert">Kupon başarıyla güncellendi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
         <?php elseif ($_GET['status'] === 'coupon_deleted'): ?>
             <div class="alert alert-success alert-dismissible fade show" role="alert">Kupon başarıyla silindi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php elseif (str_contains($_GET['status'], 'error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">İşlem sırasında bir hata oluştu. Detay: <?php echo htmlspecialchars($_GET['status']); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php endif; ?>
    <?php endif; ?>


    <div class="accordion" id="adminAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCompanies">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompanies" aria-expanded="true" aria-controls="collapseCompanies">
                    Firma Yönetimi
                </button>
            </h2>
            <div id="collapseCompanies" class="accordion-collapse collapse show" aria-labelledby="headingCompanies" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <h5>Kayıtlı Firmalar</h5>
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; padding: 0;">
                                <?php if (empty($companies)): ?>
                                    <p class="p-3">Henüz kayıtlı bir firma bulunmamaktadır.</p>
                                <?php else: ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($companies as $company): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?php echo htmlspecialchars($company['name']); ?>
                                                <span>
                                                    <a href="/index.php?page=edit-company&id=<?php echo $company['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                                    <a href="/index.php?page=delete-company&id=<?php echo $company['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu firmayı silmek istediğinizden emin misiniz?');">Sil</a>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h5>Yeni Firma Ekle</h5>
                            <form action="/index.php?page=add-company" method="POST">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Firma Adı</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Firmayı Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAdmins">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmins" aria-expanded="false" aria-controls="collapseAdmins">
                    Firma Admini Yönetimi
                </button>
            </h2>
            <div id="collapseAdmins" class="accordion-collapse collapse" aria-labelledby="headingAdmins" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <h5>Kayıtlı Firma Adminleri</h5>
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; padding: 10px;">
                                <?php if (empty($firmaAdmins)): ?>
                                    <p>Henüz kayıtlı bir firma admini bulunmamaktadır.</p>
                                <?php else: ?>
                                    <table class="table table-sm table-striped table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Ad Soyad</th>
                                                <th>Email</th>
                                                <th>Firma</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($firmaAdmins as $admin): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($admin['company_name'] ?? 'Atanmamış'); ?></td>
                                                    <td>
                                                        <a href="/index.php?page=edit-firma-admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                                        <a href="/index.php?page=delete-firma-admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu firma adminini silmek istediğinizden emin misiniz?');">Sil</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h5>Yeni Firma Admini Ekle</h5>
                            <form action="/index.php?page=add-firma-admin" method="POST">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Ad Soyad</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Şifre</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="company_id_fa" class="form-label">Atanacak Firma</label>
                                    <select class="form-select" id="company_id_fa" name="company_id" required>
                                        <option value="" disabled selected>Firma Seçin...</option>
                                        <?php foreach ($companies as $company): ?>
                                            <option value="<?php echo $company['id']; ?>"><?php echo htmlspecialchars($company['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Firma Admini Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCoupons">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCoupons" aria-expanded="false" aria-controls="collapseCoupons">
                    Kupon Yönetimi
                </button>
            </h2>
            <div id="collapseCoupons" class="accordion-collapse collapse" aria-labelledby="headingCoupons" data-bs-parent="#adminAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <h5>Tüm Kuponlar</h5>
                             <div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; padding: 10px;">
                                <?php if (empty($coupons)): ?>
                                    <p>Henüz sistemde kayıtlı bir kupon bulunmamaktadır.</p>
                                <?php else: ?>
                                    <table class="table table-sm table-striped table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kod</th>
                                                <th>İndirim (%)</th>
                                                <th>Firma</th>
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
                                                    <td><?php echo htmlspecialchars($coupon['company_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($coupon['usage_limit'] ?? 'Sınırsız'); ?></td>
                                                    <td><?php echo $coupon['expire_date'] ? date('d/m/Y', strtotime($coupon['expire_date'])) : 'Süresiz'; ?></td>
                                                    <td>
                                                        <a href="/index.php?page=edit-coupon&id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                                        <a href="/index.php?page=delete-coupon-admin&id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kuponu silmek istediğinizden emin misiniz?');">Sil</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                           <h5>Yeni Genel Kupon Ekle</h5>
                            <form action="/index.php?page=add-coupon-admin" method="POST">
                                <input type="hidden" name="company_id" value="">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kupon Kodu</label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="discount" class="form-label">İndirim Oranı (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" placeholder="Örn: 15" required>
                                    <div class="form-text">Örn: %15 indirim için 15 yazın.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="usage_limit" class="form-label">Kullanım Limiti (Opsiyonel)</label>
                                    <input type="number" class="form-control" id="usage_limit" name="usage_limit" placeholder="Boş bırakırsanız sınırsız olur">
                                </div>
                                <div class="mb-3">
                                    <label for="expire_date" class="form-label">Son Kullanma Tarihi (Opsiyonel)</label>
                                    <input type="date" class="form-control" id="expire_date" name="expire_date">
                                </div>
                                <button type="submit" class="btn btn-primary">Genel Kupon Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> </div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>