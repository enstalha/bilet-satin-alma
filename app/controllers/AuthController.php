<?php
class AuthController{
    public function showLoginForm() {
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    public function register(){
        $fullName = $_POST["full_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $userModel = new User();
        $existingUser = $userModel->findByEmail($email);

        if($existingUser){
            header('Location: /index.php?page=register&error=email_taken');
            exit();
        }

        $success = $userModel->createUser($fullName, $email, $password);

        if($success){
            header("Location: /index.php?page=login&register=success");
            exit();
        }else{
            header("Location: /index.php?page=register&error=1");
            exit();
        }
    }

    public function login(){
        $email = $_POST["email"];
        $password = $_POST["password"];

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if($user && password_verify($password, $user["password"])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['company_id'] = $user['company_id'];
            $_SESSION['balance'] = $user['balance'];

            session_write_close();

            header("Location: /index.php?page=home");
            exit();
        } else {
            header("Location: /index.php?page=login&error=1");
            exit();
        }
    }

    public function logout(){
        session_unset();
        session_destroy();

        header("Location: /index.php?page=login");
        exit();
    }
}