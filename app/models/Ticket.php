<?php

class Ticket {
    private $db;

    public function __construct() {
        $this->db = getDatabaseConnection();
    }

    public function findById($ticket_id) {
        $sql = "SELECT
                t.id as ticket_id, t.user_id, t.status, t.total_price,
                t.created_at, 
                bs.seat_number,
                tr.departure_city, tr.destination_city, tr.departure_time,
                tr.arrival_time,
                c.name as company_name
            FROM tickets t
            JOIN trips tr ON t.trip_id = tr.id
            JOIN bus_companies c ON tr.company_id = c.id 
            JOIN booked_seats bs ON t.id = bs.ticket_id
            WHERE t.id = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$ticket_id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookedSeats($trip_id) {
        $sql = "SELECT bs.seat_number 
                FROM booked_seats bs
                JOIN tickets t ON bs.ticket_id = t.id
                WHERE t.trip_id = ? AND t.status = 'active'";
        
        $statement = $this->db->prepare($sql);
        $statement->execute([$trip_id]);

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createBooking($user_id, $trip_id, $seat_numbers, $final_price) {
        try {
            $this->db->beginTransaction();

            $ticket_id = generateUuid();
            $sqlTicket = "INSERT INTO tickets (id, user_id, trip_id, total_price) VALUES (?, ?, ?, ?)";
            $stmtTicket = $this->db->prepare($sqlTicket);
            $stmtTicket->execute([$ticket_id, $user_id, $trip_id, $final_price]);

            $sqlSeat = "INSERT INTO booked_seats (id, ticket_id, seat_number) VALUES (?, ?, ?)";
            $stmtSeat = $this->db->prepare($sqlSeat);
            foreach ($seat_numbers as $seat) {
                $seat_id = generateUuid();
                $stmtSeat->execute([$seat_id, $ticket_id, $seat]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
             if ($this->db->inTransaction()) {
                 $this->db->rollBack();
             }
             error_log("Bilet oluşturma hatası: " . $e->getMessage());
            return false;
        }
    }

    public function getTicketsByUserId($user_id){
        $sql = "SELECT 
                    t.id as ticket_id,
                    t.status,
                    bs.seat_number,
                    tr.departure_city,
                    tr.destination_city,
                    tr.departure_time,
                    tr.price,
                    c.name as company_name
                FROM tickets t
                JOIN booked_seats bs ON t.id = bs.ticket_id
                JOIN trips tr ON t.trip_id = tr.id
                JOIN bus_companies c ON tr.company_id = c.id
                WHERE t.user_id = ?
                ORDER BY tr.departure_time DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute([$user_id]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function cancel($ticket_id) {
        $sql = "UPDATE tickets SET status = 'cancelled' WHERE id = ?";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$ticket_id]); 
    }
}