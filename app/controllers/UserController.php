<?php

class UserController{
    public function showMyTickets(){
        if(!isset($_SESSION['user_id'])){
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görmek için giriş yapmalısınız.');
        }

        $user_id = $_SESSION['user_id'];

        $ticketModel = new Ticket();
        $tickets = $ticketModel->getTicketsByUserId($user_id);

        require_once __DIR__ . '/../../views/user/my-tickets.php';
    }

    public function showMyAccountPage(){
        if (!isset($_SESSION['user_id'])) {
            view_error(403, 'Erişim Engellendi', 'Bu sayfayı görmek için giriş yapmalısınız.');
        }

        $user_id = $_SESSION['user_id'];

        $userMdodel = new User();
        $user = $userMdodel->findByID($user_id);

        if (!$user) {
             view_error(404, 'Kullanıcı Bulunamadı', 'Kullanıcı bilgileri alınamadı.');
        }

        require_once __DIR__ . '/../../views/user/my_account.php';
    }

    public function cancelTicket(){
        if (!isset($_SESSION['user_id'])) {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapmak için giriş yapmalısınız.');
        }

        $ticket_id = $_GET['ticket_id'] ?? 0;
        $user_id = $_SESSION['user_id'];

        $ticketModel = new Ticket();
        $ticket = $ticketModel->findById($ticket_id);

        if(!$ticket || $ticket['user_id'] != $user_id || $ticket['status'] != 'active'){
            view_error(404, 'Geçersiz İşlem', 'Böyle bir bilet bulunamadı veya bu bileti iptal etme yetkiniz yok.');
        }

        $departure_time = new DateTime($ticket['departure_time']);
        $now = new DateTime();
        $time_diff_seconds = $departure_time->getTimestamp() - $now->getTimestamp();

        if($time_diff_seconds < 3600){
            header('Location: /index.php?page=my-tickets&cancellation=failed_time');
            exit();
        }

        $isCancelled = $ticketModel->cancel($ticket_id);

        if($isCancelled){
            $userModel = new User();
            $current_balance = $userModel->getBalance($user_id);
            $new_balance = $current_balance + $ticket['total_price'];
            $userModel->updateBalance($user_id, $new_balance);

            $_SESSION['balance'] = $new_balance;

            session_write_close();
            header('Location: /index.php?page=my-tickets&cancellation=success');
        }else{
            session_write_close();
            header('Location: /index.php?page=my-tickets&cancellation=failed_db');
        }
        exit();
    }

    public function addBalance(){
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
             view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $user_id = $_SESSION['user_id'];
        $amount_to_add = $_POST['amount'];

        if (!is_numeric($amount_to_add) || $amount_to_add <= 0) {
             header('Location: /index.php?page=my-account&status=error_invalid_amount');
             exit();
        }

        $userModel = new User();
        $current_balance = $userModel->getBalance($user_id);

        $new_balance = $current_balance + $amount_to_add;

        $success = $userModel->updateBalance($user_id, $new_balance);

        if ($success) {
            $_SESSION['balance'] = $new_balance;
            session_write_close();
            header('Location: /index.php?page=my-account&status=success_balance_added');
        } else {
             header('Location: /index.php?page=my-account&status=error_balance_update');
        }
        exit();
    }
}