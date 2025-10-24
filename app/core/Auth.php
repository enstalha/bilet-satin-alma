<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function registerUser($db, $full_name, $email, $password, $role = 'user') {
    $id = bin2hex(random_bytes(16));
    $password_hash = password_hash($password, PASSWORD_ARGON2ID);

    $sql = "INSERT INTO User (id, full_name, email, role, password)
            VALUES (:id, :full_name, :email, :role, :password_hash)";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':full_name' => $full_name,
            ':email' => $email,
            ':role' => $role,
            ':password_hash' => $password_hash
        ]);
        return ['success' => true];
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            return ['success' => false, 'message' => 'Bu e-posta adresi zaten kullanımda.'];
        }
        return ['success' => false, 'message' => 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage()];
    }
}


function loginUser($db, $email, $password) {
    $sql = "SELECT id, full_name, password, role FROM User WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function logoutUser() {
    $_SESSION = array(); 
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
