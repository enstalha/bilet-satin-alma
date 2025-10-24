<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Seferi Düzenle</h2>

    <div class="card">
        <div class="card-body">
            <form action="/index.php?page=update-trip" method="POST">
                
                <input type="hidden" name="trip_id" value="<?php echo htmlspecialchars($trip['id']); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="departure_city" class="form-label">Kalkış Şehri</label>
                        <input type="text" class="form-control" id="departure_city" name="departure_city" value="<?php echo htmlspecialchars($trip['departure_city']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination_city" class="form-label">Varış Şehri</label>
                        <input type="text" class="form-control" id="destination_city" name="destination_city" value="<?php echo htmlspecialchars($trip['destination_city']); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="departure_time" class="form-label">Kalkış Zamanı</label>
                        <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" value="<?php echo date('Y-m-d\TH:i', strtotime($trip['departure_time'])); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="arrival_time" class="form-label">Varış Zamanı</label>
                        <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" value="<?php echo date('Y-m-d\TH:i', strtotime($trip['arrival_time'])); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Fiyat (TL)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($trip['price']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="capacity" class="form-label">Kapasite (Koltuk Sayısı)</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($trip['capacity']); ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                <a href="/index.php?page=firma-admin-dashboard" class="btn btn-secondary">İptal</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>