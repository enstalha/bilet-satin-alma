<?php 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Kayıt Ol</h2>

                <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] === 'email_taken') {
                            echo '<div class="alert alert-warning">Bu e-posta adresi zaten kullanılıyor. Lütfen başka bir tane deneyin veya giriş yapın.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Kayıt sırasında bilinmeyen bir hata oluştu. Lütfen tekrar deneyin.</div>';
                        }
                    }
                ?>

                <form action="/index.php?page=register" method="POST">
                    <div class="mb-3">
                        <i class="fa-solid fa-user"></i>
                        <label for="full_name" class="form-label">Ad Soyad</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
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
                    <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
                </form>

                <p class="mt-3 text-center">
                    Zaten hesabınız var mı? 
                    <a href="/index.php?page=login">Giriş Yap</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/../layout/footer.php'; 
?>
