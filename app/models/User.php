<?php

class User{
    private $db;

    public function __construct(){
        $this->db = getDatabaseConnection();
    }

    public function findByEmail($email){
        $statement = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$email]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function findByID($id){
        $statement = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $statement->execute([$id]);
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($fullName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $currentTime = date('Y-m-d H:i:s');
        $id = generateUuid();

        $sql = "INSERT INTO users (id, full_name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        
        $statement = $this->db->prepare($sql);
        
        return $statement->execute([$id, $fullName, $email, $hashedPassword, 'user', $currentTime]);
    }

    public function getBalance($userId){
        $statement = $this->db->prepare("SELECT balance FROM users WHERE id = ?");
        $statement->execute([$userId]);

        return $statement->fetchColumn();
    }

    public function updateBalance($userId, $newBalance){
        $statement = $this->db->prepare("UPDATE users SET balance = ? WHERE id = ?");
        
        return $statement->execute([$newBalance, $userId]); 
    }

    public function getFirmaAdmins(){
        $sql = "SELECT u.id, u.full_name, u.email, u.role, u.company_id, c.name as company_name 
                FROM users u 
                LEFT JOIN bus_companies c ON u.company_id = c.id 
                WHERE u.role = 'firma-admin' 
                ORDER BY u.full_name ASC";
        $statement = $this->db->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createFirmaAdmin($full_name, $email, $password, $company_id){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user_id = generateUuid();
        $currentTime = date('Y-m-d H:i:s');

        $sql = "INSERT INTO users (id, full_name, email, password, role, company_id, created_at) 
                VALUES (?, ?, ?, ?, 'firma-admin', ?, ?)";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$user_id, $full_name, $email, $hashedPassword, $company_id, $currentTime]);
    }

    public function updateFirmaAdmin($user_id, $full_name, $email, $company_id){
        $sql = "UPDATE users SET full_name = ?, email = ?, company_id = ? 
                 WHERE id = ? AND role = 'firma-admin'";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$full_name, $email, $company_id, $user_id]);
    }

    public function deleteFirmaAdmin($user_id){
        $sql = "DELETE FROM users WHERE id = ?";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$user_id]);
    }
}