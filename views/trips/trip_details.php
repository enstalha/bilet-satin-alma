<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    .seat-layout { display: flex; flex-wrap: wrap; gap: 1px; max-width: 350px; padding: 1px; border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa; }
    .seat-row { display: flex; width: 100%; justify-content: center; align-items: center; }
    .seat-pair { display: flex; gap: 1px; }
    .aisle { width: 30px; height: 40px; } 
    .seat-checkbox { display: none; }
    .seat-checkbox + label {
        width: 40px;
        height: 40px;
        display: flex;
        font-weight: bold;
        justify-content: center;
        align-items: center;
        border: 1px solid #adb5bd;
        border-radius: 5px;
        cursor: pointer;
        background-color: white;
    }
    .seat-checkbox:checked + label {
        background-color: #198754;
        color: white;
        border-color: #198754;
    }
    .seat-checkbox:disabled + label {
        background-color: red;
        color: white;
        cursor: not-allowed;
        border-color: red;
        text-decoration: line-through;
    }
</style>

<div class="container mt-5">
    <?php 
    if (isset($_GET['error']) && $_GET['error'] === "insufficient_funds"): ?>
        <div class="alert alert-danger">Yetersiz bakiye.</div>
    <?php endif; ?>

    <?php 
    if (isset($_GET['error']) && $_GET['error'] === "no_seat_selected"): ?>
        <div class="alert alert-danger">Lütfen öncelikle koltuk seçimi yapınız.</div>
    <?php endif; ?>

    <?php 
    if (isset($_GET['error']) && $_GET['error'] === "coupon_not_found"): ?>
        <div class="alert alert-danger">Kupon bulunamadı.</div>
    <?php endif; ?>

    <?php if (!$trip): ?>
        <div class="alert alert-danger mt-4"><h4>Sefer Bulunamadı</h4></div>
    <?php else: ?>

        <form action="/index.php?page=confirm-purchase" method="POST">
            <input type="hidden" name="trip_id" value="<?php echo htmlspecialchars($trip['id']); ?>">

            <div class="row">
                <div class="col-lg-5 mb-4 me-2">
                    <div class="card mb-3">
                        <div class="card-header"><h4>Sefer Bilgileri</h4></div>
                        <div class="card-body">
                            <dl class="row mb-0"> <dt class="col-sm-4"><i class="fas fa-building text-muted me-1"></i> Firma</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($trip['company_name']); ?></dd>

                                <dt class="col-sm-4"><i class="fas fa-route text-muted me-1"></i> Güzergah</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($trip['departure_city']); ?> <i class="fas fa-long-arrow-alt-right mx-1"></i> <?php echo htmlspecialchars($trip['destination_city']); ?></dd>

                                <dt class="col-sm-4"><i class="far fa-calendar-alt text-muted me-1"></i> Kalkış</dt>
                                <dd class="col-sm-8"><?php echo date('d.m.Y H:i', strtotime($trip['departure_time'])); ?></dd>

                                <dt class="col-sm-4"><i class="far fa-calendar-check text-muted me-1"></i> Varış</dt>
                                <dd class="col-sm-8"><?php echo date('d.m.Y H:i', strtotime($trip['arrival_time'])); ?></dd>

                                <dt class="col-sm-4"><i class="fas fa-lira-sign text-muted me-1"></i> Fiyat</dt>
                                <dd class="col-sm-8"><span id="price-per-seat"><?php echo htmlspecialchars(number_format($trip['price'], 2, ',', '.')); ?></span> TL / Koltuk</dd>
                            </dl>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-body">
                            <i class="fa-solid fa-tag"></i>
                            <label for="coupon_code" class="form-label fw-bold">İndirim Kuponu</label>
                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Varsa kupon kodunuzu girin">
                        </div>
                    </div>

                    <?php 
                    if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && trim($_SESSION['user_role']) === 'user'): 
                    ?>
                        <button type="submit" class="btn btn-success btn-lg mt-3 w-100">
                            <i class="fa-solid fa-circle-check text-white"></i>
                            Satın Almayı Onayla
                        </button>
                    <?php elseif (isset($_SESSION['user_id'])):  ?>
                        <div class="alert alert-warning mt-3">Admin veya Firma Admin rolleri bilet satın alamaz.</div>
                    <?php else: ?>
                        <div class="alert alert-warning mt-3">Bilet satın almak için lütfen <a href="/index.php?page=login">giriş yapın</a>.</div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-6 ps-5">
                    <h4>Koltuk Seçimi</h4>
                    <div class="seat-layout">
                        <?php for ($i = 1; $i <= $trip['capacity']; $i++): ?>
                            <?php $isOccupied = in_array($i, $occupiedSeats); ?>
                            
                            <?php if ($i % 4 == 1) echo '<div class="seat-row"><div class="seat-pair">'; ?>

                            <div class="form-check">
                                <input class="form-check-input seat-checkbox" type="checkbox" name="seat_numbers[]" 
                                       id="seat<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($isOccupied) echo 'disabled'; ?>>
                                <label class="form-check-label" for="seat<?php echo $i; ?>"><?php echo $i; ?></label>
                            </div>

                            <?php if ($i % 4 == 2) echo '</div><div class="aisle"></div><div class="seat-pair">'; ?>
                            <?php if ($i % 4 == 0 || $i == $trip['capacity']) echo '</div></div>'; ?>
                        <?php endfor; ?>
                    </div>
                    
                </div>
            </div>
        </form>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
