<?php
date_default_timezone_set('Europe/Istanbul');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../app/core/Database.php';
require_once '../app/models/User.php';
require_once '../app/models/Trip.php';
require_once '../app/models/Ticket.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/TripController.php';
require_once '../app/controllers/FirmaAdminController.php';
require_once '../app/core/helpers.php';
require_once '../app/controllers/UserController.php';
require_once '../app/models/Company.php';
require_once '../app/controllers/AdminController.php';
require_once '../app/models/Coupon.php';
require_once '../app/models/UserCoupon.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        require_once '../views/index.php';
        break;
    case 'search-trips':
        $controller = new TripController();
        $controller->search();
        break;
    case 'trip-details':
        $controller = new TripController();
        $controller->showDetails();
        break;
    case 'my-tickets':
        $controller = new UserController();
        $controller->showMyTickets();
        break;
    case 'confirm-purchase':
        $controller = new TripController();
        $controller->confirmPurchase();
        break;
    case 'cancel-ticket':
        $controller = new UserController();
        $controller->cancelTicket();
        break;
    case 'edit-trip':
        $controller = new FirmaAdminController();
        $controller->showEditForm();
        break;
    case 'my-account':
        $controller = new UserController();
        $controller->showMyAccountPage();
        break;
    case 'add-balance':
        $controller = new UserController();
        $controller->addBalance();
        break;
    case 'admin-dashboard':
        $controller = new AdminController();
        $controller->showDashboard();
        break;
    case 'add-firma-admin':
        $controller = new AdminController();
        $controller->addFirmaAdmin();
        break;
    case 'edit-firma-admin':
        $controller = new AdminController();
        $controller->showEditFirmaAdminForm();
        break;
    case 'update-firma-admin':
        $controller = new AdminController();
        $controller->updateFirmaAdmin();
        break;
    case 'delete-firma-admin':
        $controller = new AdminController();
        $controller->deleteFirmaAdmin();
        break;
    case 'add-coupon-admin':
        $controller = new AdminController();
        $controller->addGeneralCoupon();
        break;
    case 'delete-coupon-admin':
        $controller = new AdminController();
        $controller->deleteGeneralCoupon();
        break;
    case 'edit-coupon':
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'){
            $controller = new AdminController();
            $controller->showCouponEditForm();
        }elseif(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'firma-admin'){
            $controller = new FirmaAdminController();
            $controller->showEditCouponForm();
        }else{
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görüntüleme yetkiniz yok.');
        }
        break;
    case 'update-coupon-admin':
        $controller = new AdminController();
        $controller->updateCouponAdmin();
        break;
    case 'update-coupon-firma-admin':
        $controller = new FirmaAdminController();
        $controller->updateCouponFirmaAdmin();
        break;
    case 'add-coupon':
        $controller = new FirmaAdminController();
        $controller->addCoupon();
        break;
    case 'delete-coupon':
        $controller = new FirmaAdminController();
        $controller->deleteCoupon();
        break;
    case 'add-company':
        $controller = new AdminController();
        $controller->addCompany();
        break;
    case 'edit-company':
        $controller = new AdminController();
        $controller->showEditCompanyForm();
        break;
    case 'update-company':
        $controller = new AdminController();
        $controller->updateCompany();
        break;
    case 'delete-company':
        $controller = new AdminController();
        $controller->deleteCompany();
        break;
    case 'update-trip':
        $controller = new FirmaAdminController();
        $controller->updateTrip();
        break;
    case 'delete-trip':
        $controller = new FirmaAdminController();
        $controller->deleteTrip();
        break;
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegisterForm();
        }
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'firma-admin-dashboard':
        $controller = new FirmaAdminController();
        $controller->showDashboard();
        break;
    case 'add-trip':
        $controller = new FirmaAdminController();
        $controller->addTrip();
        break;
    default:
        view_error(404, 'Sayfa Bulunamadı', 'Aradığınız sayfa mevcut değil veya adresi değişmiş olabilir.');
        break;
}