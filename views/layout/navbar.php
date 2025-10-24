<nav class="navbar navbar-expand-lg navbar-dark" style="background:#252626;">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="/index.php?page=home"><strong>BiletFix</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        
        <?php 
        if (isset($_SESSION['user_id'])): ?>
          
          <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
            
            <li class="nav-item mt-2">
              <span class="navbar-text me-3">
                Hoş geldin, <?php echo htmlspecialchars($_SESSION['full_name']); ?>
              </span>
            </li>
            <li class="nav-item mt-2">
              <span class="navbar-text me-3">
                Bakiye: <?php echo number_format($_SESSION['balance'] ?? 0, 2); ?> TL
              </span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?page=my-tickets"><i class="fa-solid fa-ticket"></i> Biletlerim</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?page=my-account"><i class="fa-solid fa-user"></i> Hesabım</a>
            </li>
          <?php endif; ?>

          <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'firma-admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?page=firma-admin-dashboard"><i class="fa-solid fa-user-tie"></i> Firma Paneli</a>
            </li>
          <?php endif; ?>

          <?php if (isset($_SESSION['user_role']) && trim($_SESSION['user_role']) === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?page=admin-dashboard"><i class="fa-solid fa-user-secret"></i> Admin Paneli</a>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link" href="/index.php?page=logout"><i class="fa-solid fa-right-from-bracket"></i> Çıkış Yap</a>
          </li>
          
        <?php else:?>

          <ul class="navbar-nav ms-auto d-flex align-items-center">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="/index.php?page=login">
                <i class="fa-solid fa-right-to-bracket me-1"></i>
                Giriş Yap
              </a>
            </li>
            <li class="nav-item ms-3">
              <a class="nav-link d-flex align-items-center" href="/index.php?page=register">
                <i class="fa-solid fa-user me-1"></i>
                Kayıt Ol
              </a>
            </li>
          </ul>



        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>