<?php

class Coupon{
    private $db;

    public function __construct(){
        $this->db = getDatabaseConnection();
    }

    public function getCouponsByCompanyID($company_id){
        $sql = "SELECT * FROM coupons WHERE company_id = ? ORDER BY created_at DESC";
        $statement = $this->db->prepare($sql);
        $statement->execute([$company_id]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($company_id, $code, $discount, $usage_limit, $expire_date) {
        $id = generateUuid();
        $currentTime = date('Y-m-d H:i:s');

        $sql = "INSERT INTO coupons (id, company_id, code, discount, usage_limit, expire_date, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);

        $limit = empty($usage_limit) ? null : $usage_limit;
        $expiry = empty($expire_date) ? null : $expire_date;
        $compId = ($company_id === '') ? null : $company_id;

        return $statement->execute([$id, $compId, $code, $discount, $limit, $expiry, $currentTime]);
    }

    public function delete($id) {
        $sql = "DELETE FROM coupons WHERE id = ?";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$id]);
    }

    public function findByCode($code) {
        $sql = "SELECT * FROM coupons WHERE code = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$code]);
        $coupon = $statement->fetch(PDO::FETCH_ASSOC);

        if($coupon && $coupon['usage_limit'] !== null){
            $userCouponModel = new UserCoupon();
            $usageCount = $userCouponModel->getUsageCount($coupon['id']);

            if($usageCount >= $coupon['usage_limit']){
                return null;
            }
        }

        return $coupon;
    }

    public function findById($id) {
        $sql = "SELECT * FROM coupons WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCouponsWithCompany() {
        $sql = "SELECT 
                    c.*, 
                    COALESCE(co.name, 'Genel Kupon') as company_name 
                FROM coupons c 
                LEFT JOIN bus_companies co ON c.company_id = co.id 
                ORDER BY c.created_at DESC";
        $statement = $this->db->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $code, $discount, $usage_limit, $expire_date) {
         $sql = "UPDATE coupons SET code = ?, discount = ?, usage_limit = ?, expire_date = ?
                 WHERE id = ?";
         $statement = $this->db->prepare($sql);

         $limit = empty($usage_limit) ? null : $usage_limit;
         $expiry = empty($expire_date) ? null : $expire_date;

         return $statement->execute([$code, $discount, $limit, $expiry, $id]);
    }
}