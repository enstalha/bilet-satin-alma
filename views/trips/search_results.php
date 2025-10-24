<?php require_once __DIR__ . '/../layout/header.php';

$current_departure = $_GET['departure'] ?? '';
$current_arrival = $_GET['arrival'] ?? '';
$current_date = $_GET['date'] ?? '';
$current_sort = $_GET['sort_by'] ?? 'time_asc';
$current_company = $_GET['company_filter'] ?? '';
?>

<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Arama Sonuçları</h2>

        <form id="sortFilterForm" action="/index.php" method="GET" class="d-flex flex-column flex-md-row align-items-md-center gap-2">
            <input type="hidden" name="page" value="search-trips">
            <input type="hidden" name="departure" value="<?php echo htmlspecialchars($current_departure); ?>">
            <input type="hidden" name="arrival" value="<?php echo htmlspecialchars($current_arrival); ?>">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($current_date); ?>">

            <div class="d-flex align-items-center">
                <label for="company_filter" class="form-label me-2 mb-0 fw-semibold text-nowrap">Firma:</label>
                <select class="form-select form-select-sm" id="company_filter" name="company_filter" onchange="this.form.submit();" style="min-width: 150px;">
                    <option value="" <?php echo ($current_company === '') ? 'selected' : ''; ?>>Tüm Firmalar</option>
                    <?php if (isset($companies)):  ?>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?php echo $company['id']; ?>" <?php echo ($current_company == $company['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($company['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="d-flex align-items-center">
                <label for="sort_by" class="form-label me-2 mb-0 fw-semibold">Sırala:</label>
                <select class="form-select form-select-sm" id="sort_by" name="sort_by" onchange="this.form.submit();" style="min-width: 180px;">
                    <option value="time_asc" <?php echo ($current_sort === 'time_asc') ? 'selected' : ''; ?>>Kalkış Saati (Önce)</option>
                    <option value="price_asc" <?php echo ($current_sort === 'price_asc') ? 'selected' : ''; ?>>Fiyat (Artan)</option>
                    <option value="price_desc" <?php echo ($current_sort === 'price_desc') ? 'selected' : ''; ?>>Fiyat (Azalan)</option>
                </select>
            </div>
        </form>
    </div>

    <?php if (empty($trips)): ?>
        <div class="alert alert-info text-center">Aradığınız kriterlere uygun sefer bulunamadı.</div>
    <?php else: ?>

        <div class="row g-4"> <?php foreach ($trips as $trip): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-light h-100">
                        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center p-3">

                            <div class="mb-2 mb-md-0 text-center text-md-start" style="min-width: 150px;">
                                <h5>
                                    <strong><?php echo htmlspecialchars($trip['company_name']); ?></strong>
                                </h5>
                            </div>

                            <div class="mb-2 mb-md-0 text-center">
                                <p class="m-0 ms-1"><?php echo date('d.m.Y', strtotime($trip['departure_time'])) ?></p>
                                <span class="fw-bold fs-5">
                                    <i class="far fa-clock me-1"></i>    
                                    <?php echo date('H:i', strtotime($trip['departure_time'])); ?>
                                </span>
                                <br>    
                            </div>

                            <div class="mb-2 mb-md-0 text-center text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?php echo htmlspecialchars($trip['departure_city']); ?>
                                <i class="fas fa-long-arrow-alt-right mx-2"></i>
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?php echo htmlspecialchars($trip['destination_city']); ?>
                            </div>

                            <div class="mb-3 mb-md-0 text-center fw-bold text-primary fs-5" style="min-width: 100px;">
                                <?php echo htmlspecialchars(number_format($trip['price'], 2, ',', '.')); ?> TL
                            </div>

                            <div class="text-center">
                                <a href="/index.php?page=trip-details&id=<?php echo $trip['id']; ?>" class="btn btn-success">
                                    Koltuk Seç
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/index.php?page=home" class="btn btn-secondary"><i class="fas fa-search me-2"></i>Yeni Arama Yap</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>