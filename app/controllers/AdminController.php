<?php

class AdminController{
    public function showDashboard(){
        if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'){
            view_error(403, 'Erişim Engellendi', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $companyModel = new Company();
        $companies = $companyModel->getAllCompanies();

        $userModel = new User();
        $firmaAdmins = $userModel->getFirmaAdmins();

        $couponModel = new Coupon();
        $coupons = $couponModel->getAllCouponsWithCompany();

        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function showEditCompanyForm(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        $company_id = $_GET['id'] ?? '';

        if (empty($company_id)) {
            view_error(400, 'Geçersiz İstek', 'Düzenlenecek firma ID\'si belirtilmedi.');
        }

        $companyModel = new Company();
        $company = $companyModel->findById($company_id);

        if (!$company) {
            view_error(404, 'Firma Bulunamadı', 'Düzenlemek istediğiniz firma bulunamadı.');
        }

        require_once __DIR__ . '/../../views/admin/edit_company.php';
    }

    public function showCouponEditForm(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin'){
            view_error(0, '', '');
        }

        $coupon_id = $_GET['id'] ?? '';

        if (empty($coupon_id)){
            view_error(0, '', '');
        }

        $couponModel = new Coupon();
        $coupon = $couponModel->findById($coupon_id);

        if (!$coupon){
            view_error(404, '', '');
        }

        $companyModel = new Company();
        $companies = $companyModel->getAllCompanies();

        require_once __DIR__ . '/../../views/coupons/edit_coupon.php';
    }

    public function addCompany(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $company_name = $_POST['company_name'];
        if(empty($company_name)){
            header('Location: /index.php?page=admin-dashboard&status=error_empty_name');
            exit();
        }

        $companyModel = new Company();
        $success = $companyModel->create($company_name);

        $status = $success ? 'company_added' : 'error_add_company';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function updateCompany(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $company_id = $_POST['company_id'] ?? '';
        $company_name = $_POST['company_name'] ?? '';

        if (empty($company_id) || empty($company_name)) {
            header('Location: /index.php?page=admin-dashboard&status=error_update_empty');
            exit();
        }

        $companyModel = new Company();
        $success = $companyModel->update($company_id, $company_name);

        $status = $success ? 'company_updated' : 'error_update_company';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function deleteCompany(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $company_id = $_GET['id'] ??'';

        if (empty($company_id)) {
            header('Location: /index.php?page=admin-dashboard&status=error_delete_no_id');
            exit();
        }

        $companyModel = new Company();
        $company = $companyModel->findById($company_id);

        if(!$company){
            view_error(404, 'Firma Bulunamadı', 'Silmek istediğiniz firma mevcut değil.');
        }

        $success = $companyModel->delete($company_id);

        $status = $success ? 'company_deleted' : 'error_delete_company';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function addFirmaAdmin(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $full_name = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $company_id = $_POST['company_id'] ?? '';

        if (empty($full_name) || empty($email) || empty($password) || empty($company_id)) {
            header('Location: /index.php?page=admin-dashboard&status=error_fa_empty');
            exit();
        }

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
             header('Location: /index.php?page=admin-dashboard&status=error_fa_email_taken');
             exit();
        }

        $success = $userModel->createFirmaAdmin($full_name, $email, $password, $company_id);

        $status = $success ? 'fa_added' : 'error_fa_add';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function showEditFirmaAdminForm(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        $user_id = $_GET['id'] ?? '';

        if (empty($user_id)) {
            view_error(400, 'Geçersiz İstek', 'Düzenlenecek admin ID\'si belirtilmedi.');
        }

        $userModel = new User();
        $adminToEdit = $userModel->findByID($user_id);

        if (!$adminToEdit || $adminToEdit['role'] !== 'firma-admin') {
            view_error(404, 'Kullanıcı Bulunamadı', 'Düzenlemek istediğiniz firma admini bulunamadı.');
        }

        $companyModel = new Company();
        $companies = $companyModel->getAllCompanies();

        require_once __DIR__ . '/../../views/admin/edit_firma_admin.php';
    }

    public function updateFirmaAdmin(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $user_id = $_POST['user_id'] ?? '';
        $full_name = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $company_id = $_POST['company_id'] ?? '';

        if (empty($user_id) || empty($full_name) || empty($email) || empty($company_id)) {
            header('Location: /index.php?page=edit-firma-admin&id=' . urlencode($user_id) . '&error=empty');
            exit();
        }

        $userModel = new user();
        $existingUser = $userModel->findByEmail($email);

        if ($existingUser && $existingUser['id'] !== $user_id) {
             header('Location: /index.php?page=edit-firma-admin&id=' . urlencode($user_id) . '&error=email_taken');
             exit();
        }

        $success = $userModel->updateFirmaAdmin($user_id, $full_name, $email, $company_id);

        $status = $success ? 'fa_updated' : 'error_fa_update';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function deleteFirmaAdmin(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $user_id = $_GET['id'] ?? '';

        if (empty($user_id)) {
            header('Location: /index.php?page=admin-dashboard&status=error_fa_delete_no_id');
            exit();
        }

        $userModel = new User();
        $adminToDelete = $userModel->findByID($user_id);

        if (!$adminToDelete || $adminToDelete['role'] !== 'firma-admin') {
             view_error(404, 'Kullanıcı Bulunamadı', 'Silmek istediğiniz firma admini mevcut değil.');
        }

        $success = $userModel->deleteFirmaAdmin($user_id);

        $status = $success ? 'fa_deleted' : 'error_fa_delete';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function addGeneralCoupon(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $code = trim($_POST['code'] ?? '');
        $discount = $_POST['discount'] ?? 0;
        $usage_limit = $_POST['usage_limit'] ?? null;
        $expire_date = $_POST['expire_date'] ?? null;
        $company_id = null;

        if (empty($code) || !is_numeric($discount) || $discount <= 0) {
            header('Location: /index.php?page=admin-dashboard&status=error_coupon_invalid');
            exit();
        }

        if($discount >= 1) {
            $discount = $discount / 100.0;
        }

        $couponModel = new Coupon();

        if ($couponModel->findByCode($code)) {
            header('Location: /index.php?page=admin-dashboard&status=error_coupon_code_exists');
            exit();
        }

        $success = $couponModel->create($company_id, $code, $discount, $usage_limit, $expire_date);

        $status = $success ? 'coupon_added' : 'error_coupon_add';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function deleteGeneralCoupon() {
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin') {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $coupon_id = $_GET['id'] ?? '';

        if (empty($coupon_id)) {
            header('Location: /index.php?page=admin-dashboard&status=error_coupon_delete_no_id');
            exit();
        }

        $couponModel = new Coupon();
        
        $coupon = $couponModel->findById($coupon_id);
        if (!$coupon) {
             view_error(404, 'Kupon Bulunamadı', 'Silmek istediğiniz kupon mevcut değil.');
        }

        $success = $couponModel->delete($coupon_id);

        $status = $success ? 'coupon_deleted' : 'error_coupon_delete';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }

    public function updateCouponAdmin(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'admin'){
            view_error(0, '','');
        }

        $coupon_id = $_POST['coupon_id'] ?? '';
        $code = trim($_POST['code'] ?? '');
        $discount = $_POST['discount'] ?? 0;
        $usage_limit = $_POST['usage_limit'] ?? null;
        $expire_date = $_POST['expire_date'] ?? null;

        if (empty($coupon_id) || empty($code) || !is_numeric($discount) || $discount <= 0) {
            header('Location: /index.php?page=edit-coupon&id=' . urlencode($coupon_id) . '&error=empty'); exit();
        }

        if($discount >= 1){ 
            $discount = $discount / 100.0; 
        }

        $couponModel = new Coupon();
        $existingCoupon = $couponModel->findByCode($code);

        if ($existingCoupon && $existingCoupon['id'] !== $coupon_id) {
             header('Location: /index.php?page=edit-coupon&id=' . urlencode($coupon_id) . '&error=code_exists'); exit();
        }

        $success = $couponModel->update($coupon_id, $code, $discount, $usage_limit, $expire_date);
        $status = $success ? 'coupon_updated' : 'error_coupon_update';
        header('Location: /index.php?page=admin-dashboard&status=' . $status);
        exit();
    }
}