<?php
require_once __DIR__ . "/../layout/header.php";
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Giriş Yap</h2>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">E-posta veya şifre hatalı.</div>
                <?php endif; ?>

                <form action="/index.php?page=login" method="POST">
                    <div class="mb-3">
                        <i class="fa-solid fa-envelope"></i>
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <i class="fa-solid fa-lock"></i>
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                </form>

                <p class="mt-3 text-center">
                    Hesabınız yok mu? 
                    <a href="/index.php?page=register">Kayıt Ol</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/../layout/footer.php'; 
?>
