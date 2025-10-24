<?php

class FirmaAdminController{
    public function showDashboard(){
        if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'firma-admin'){
            view_error(403, 'Erişim Engellendi', 'Bu işlemi gerçekleştirme yetkiniz bulunmamaktadır.');
        }

        $company_id = $_SESSION['company_id'];

        $companyModel = new Company();
        $company = $companyModel->findById($company_id);

        $tripModel = new Trip();
        $trips = $tripModel->getTripsByCompanyId($company_id);

        $couponModel = new Coupon();
        $coupons = $couponModel->getCouponsByCompanyId($company_id);

        require_once __DIR__ . '/../../views/firma-admin/dashboard.php';
    }

    public function showEditForm(){
        if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'firma-admin'){
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görüntüleme yetkiniz yok.');
        } 

        $trip_id = $_GET['id'] ?? 0;
        $company_id = $_SESSION['company_id'];

        $tripModel = new Trip();
        $trip = $tripModel->findById($trip_id);

        if(!$trip || (int)$trip['company_id'] !== (int)$company_id){
            view_error(404, 'Sefer Bulunamadı', 'Düzenlemek istediğiniz sefer bulunamadı veya bu sefere erişim yetkiniz yok.');
        }

        require_once __DIR__ . '/../../views/firma-admin/edit_trip.php';
    }

    public function showEditCouponForm(){
        if(!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'firma-admin'){
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        $coupon_id = $_GET['id'] ?? '';
        $company_id = $_SESSION['company_id'];

        if(!$coupon_id){
            view_error(400, 'Geçersiz İstek', 'Düzenlenecek kupon ID\'si belirtilmedi.');
        }

        $couponModel = new Coupon();
        $coupon = $couponModel->findById($coupon_id);

        if (!$coupon || $coupon['company_id'] != $company_id) {
            view_error(404, 'Kupon Bulunamadı', 'Düzenlemek istediğiniz kupon bulunamadı veya bu kupona erişim yetkiniz yok.');
        }

        $companyModel = new Company();
        $companies = [$companyModel->findById($company_id)];

        require_once __DIR__ . '/../../views/coupons/edit_coupon.php';
    }

    public function addTrip(){
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'firma-admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi gerçekleştirme yetkiniz bulunmamaktadır.');
        }

        $departure_city = $_POST['departure_city'];
        $destination_city = $_POST['destination_city'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];
        $price = $_POST['price'];
        $capacity = $_POST['capacity'];

        $company_id = $_SESSION['company_id'];

        $tripModel = new Trip();
        $success = $tripModel->create($company_id, $departure_city, $destination_city, $departure_time, $arrival_time, $price, $capacity);

        if ($success) {
            header('Location: /index.php?page=firma-admin-dashboard&status=success');
        } else {
            header('Location: /index.php?page=firma-admin-dashboard&status=error');
        }
        exit();
    }

    public function updateTrip() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'firma-admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $trip_id = $_POST['trip_id'];
        $company_id = $_SESSION['company_id'];

        $tripModel = new Trip();
        $trip = $tripModel->findById($trip_id);

        if (!$trip || (int)$trip['company_id'] !== (int)$company_id) {
            view_error(403, 'Yetkisiz İşlem', 'Bu sefer üzerinde değişiklik yapma yetkiniz bulunmamaktadır.');
        }

        $departure_city = $_POST['departure_city'];
        $destination_city = $_POST['destination_city'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];
        $price = $_POST['price'];
        $capacity = $_POST['capacity'];

        $success = $tripModel->update($trip_id, $departure_city, $destination_city, $departure_time, $arrival_time, $price, $capacity);

        $status = $success ? 'update_success' : 'update_error';
        header('Location: /index.php?page=firma-admin-dashboard&status=' . $status);
        exit();
    }

    public function deleteTrip(){
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'firma-admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $trip_id = $_GET['id'] ?? 0;
        $company_id = $_SESSION['company_id'];

        $tripModel = new Trip();
        $trip = $tripModel->findById($trip_id);

        if (!$trip || (int)$trip['company_id'] !== (int)$company_id) {
            view_error(403, 'Yetkisiz İşlem', 'Bu seferi silme yetkiniz bulunmamaktadır.');
        }

        $success = $tripModel->delete($trip_id);
        
        $status = $success ? 'delete_success' : 'delete_error';
        header('Location: /index.php?page=firma-admin-dashboard&status=' . $status);
        exit();
    }

    public function addCoupon() {
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'firma-admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $company_id = $_SESSION['company_id'];
        $code = trim($_POST['code'] ?? '');
        $discount = $_POST['discount'] ?? 0;
        $usage_limit = $_POST['usage_limit'] ?? null;
        $expire_date = $_POST['expire_date'] ?? null;

        if (empty($code) || !is_numeric($discount) || $discount <= 0) {
            header('Location: /index.php?page=firma-admin-dashboard&status=error_coupon_invalid');
            exit();
        }
        if($discount >= 1) {
            $discount = $discount / 100.0;
        }

        $couponModel = new Coupon();
        
        if ($couponModel->findByCode($code)) {
            header('Location: /index.php?page=firma-admin-dashboard&status=error_coupon_code_exists');
            exit();
        }

        $success = $couponModel->create($company_id, $code, $discount, $usage_limit, $expire_date);

        $status = $success ? 'coupon_added' : 'error_coupon_add';
        header('Location: /index.php?page=firma-admin-dashboard&status=' . $status);
        exit();
    }

    public function deleteCoupon() {
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'firma-admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $coupon_id = $_GET['id'] ?? '';
        $company_id = $_SESSION['company_id'];

        if (empty($coupon_id)) {
            header('Location: /index.php?page=firma-admin-dashboard&status=error_coupon_delete_no_id');
            exit();
        }

        $couponModel = new Coupon();
        
        $coupon = $couponModel->findById($coupon_id); 
        if (!$coupon || $coupon['company_id'] != $company_id) {
             view_error(403, 'Yetkisiz İşlem', 'Bu kuponu silme yetkiniz bulunmamaktadır.');
        }

        $success = $couponModel->delete($coupon_id);

        $status = $success ? 'coupon_deleted' : 'error_coupon_delete';
        header('Location: /index.php?page=firma-admin-dashboard&status=' . $status);
        exit();
    }

    public function updateCouponFirmaAdmin(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'firma-admin'){
            view_error(0, '','');
        }

        $coupon_id = $_POST['coupon_id'] ?? '';
        $company_id = $_SESSION['company_id'];
        $code = trim($_POST['code'] ?? '');
        $discount = $_POST['discount'] ?? 0;
        $usage_limit = $_POST['usage_limit'] ?? null;
        $expire_date = $_POST['expire_date'] ?? null;

        if (empty($coupon_id) || empty($code) || !is_numeric($discount) || $discount <= 0) {
             header('Location: /index.php?page=edit-coupon&id=' . urlencode($coupon_id) . '&error=empty'); exit();
        }

        if($discount >= 1) { 
            $discount = $discount / 100.0;
        }

        $couponModel = new Coupon();
        $coupon = $couponModel->findById($coupon_id);

        if (!$coupon || $coupon['company_id'] != $company_id) {
            view_error(403, 'Yetkisiz İşlem', 'Bu kupon üzerinde değişiklik yapma yetkiniz yok.');
        }

        $success = $couponModel->update($coupon_id, $code, $discount, $usage_limit, $expire_date);
        $status = $success ? 'coupon_updated' : 'error_coupon_update';
        header('Location: /index.php?page=firma-admin-dashboard&status=' . $status);
        exit();
    }
}