<?php

class UserCoupon{
    private $db;

    public function __construct(){
        $this->db = getDatabaseConnection();
    }

    public function recordUsage($user_id, $coupon_id){
        $id = generateUuid();
        $currentTime = date('Y-m-d H:i:s');

        $sql = "INSERT INTO user_coupons (id, user_id, coupon_id, created_at) VALUES (?,?,?,?)";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$id, $user_id, $coupon_id, $currentTime]);
    }

    public function getUsageCount($coupon_id){
        $sql = "SELECT COUNT(*) FROM user_coupons WHERE coupon_id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$coupon_id]);

        return $statement->fetchColumn();
    }
}