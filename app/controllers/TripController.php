<?php
class TripController {
    public function search() {
        $departure = $_GET['departure'] ?? '';
        $arrival = $_GET['arrival'] ?? '';
        $date = $_GET['date'] ?? '';
        $sort_by = $_GET['sort_by'] ?? 'time_asc';
        $company_filter = $_GET['company_filter'] ?? '';

        $companyModel = new Company();
        $companies = $companyModel->getAllCompanies();

        $tripModel = new Trip();
        $trips = $tripModel->search($departure, $arrival, $date, $sort_by, $company_filter);

        require_once __DIR__ . '/../../views/trips/search_results.php'; 
    }

    public function showDetails() {
        $trip_id = $_GET['id'] ?? 0;

        $tripModel = new Trip();
        $trip = $tripModel->findById($trip_id);

        $ticketModel = new Ticket();
        $occupiedSeats = $ticketModel->getBookedSeats($trip_id);

        require_once __DIR__ . '/../../views/trips/trip_details.php';
    }

    public function confirmPurchase(){
        if (!isset($_SESSION['user_role']) || trim($_SESSION['user_role']) !== 'user') {
            view_error(403, 'Yetkisiz İşlem', 'Sadece yolcu rolündeki kullanıcılar bilet satın alabilir.');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            view_error(403, 'Erişim Engellendi', 'Bu işlemi yapma yetkiniz yok.');
        }

        $trip_id = $_POST['trip_id'];
        $seat_numbers = $_POST['seat_numbers'] ?? [];
        $user_id = $_SESSION['user_id'];
        $coupon_code = trim($_POST['coupon_code'] ?? '');

        if (empty($seat_numbers)) {
            header('Location: /index.php?page=trip-details&id=' . $trip_id . '&error=no_seat_selected');
            exit();
        }

        $tripModel = new Trip();
        $userModel = new User();
        $couponModel = new Coupon();
        $userCouponModel = new UserCoupon();

        $trip = $tripModel->findById($trip_id);
        $current_balance = $userModel->getBalance($user_id);

        $original_price = count($seat_numbers) * $trip['price'];
        $final_price = $original_price;
        $applied_coupon = null;

        if(!empty($coupon_code)){
            $coupon = $couponModel->findByCode($coupon_code);

            if($coupon){
                $isCompanyMatch = $coupon['company_id'] === null || $coupon['company_id'] == $trip['company_id'];

                $isExpired = $coupon['expire_date'] && (new DateTime() > new DateTime($coupon['expire_date']));
            
                if($isCompanyMatch && !$isExpired){
                    $discount_amount = $original_price * $coupon['discount'];
                    $final_price = max(0, $original_price - $discount_amount);
                    $applied_coupon = $coupon;
                }else{
                    header('Location: /index.php?page=trip-details&id=' . urlencode($trip_id) . '&error=invalid_coupon');
                    exit();
                }
            }else{
                header('Location: /index.php?page=trip-details&id=' . urlencode($trip_id) . '&error=coupon_not_found');
                exit();
            }
        }

        if($current_balance < $final_price){
            header('Location: /index.php?page=trip-details&id=' . urlencode($trip_id) . '&error=insufficient_funds');
            exit();
        }

        $ticketModel = new Ticket();
        $success = $ticketModel->createBooking($user_id, $trip_id, $seat_numbers, $final_price); 

        if($success){
            $new_balance = $current_balance - $final_price;
            $userModel->updateBalance($user_id, $new_balance);
            $_SESSION['balance'] = $new_balance;

            if($applied_coupon !== null){
                $userCouponModel->recordUsage($user_id, $applied_coupon['id']);
            }

            session_write_close();
            header('Location: /index.php?page=my-tickets&purchase=success');
            exit();
        }else{
            session_write_close();
            header('Location: /index.php?page=trip-details&id=' . urlencode($trip_id) . '&error=booking_failed');
            exit();
        }
    }
    
}