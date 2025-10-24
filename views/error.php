<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="container text-center py-5">
    <h1 class="display-1"><?php echo isset($http_code) ? htmlspecialchars($http_code) : 'Hata'; ?></h1>
    <h2 class="mb-4"><?php echo isset($title) ? htmlspecialchars($title) : 'Bir sorun oluştu'; ?></h2>
    <p class="lead"><?php echo isset($message) ? htmlspecialchars($message) : 'Beklenmedik bir hata meydana geldi.'; ?></p>
    <a href="/" class="btn btn-primary mt-3">Ana Sayfaya Dön</a>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>