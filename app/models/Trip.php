<?php

class Trip {
    private $db;

    public function __construct() {
        $this->db = getDatabaseConnection();
    }
 
    public function search($departure, $arrival, $date, $sort_by = 'time_asc', $company_filter = '') {
        $sql = "SELECT trips.*, bus_companies.name as company_name
            FROM trips
            JOIN bus_companies ON trips.company_id = bus_companies.id
            WHERE trips.departure_city LIKE ? AND trips.destination_city LIKE ?";
        $params = ['%' . $departure . '%', '%' . $arrival . '%'];

        if (!empty($date)) {
            $sql .= " AND DATE(trips.departure_time) = ?";
            $params[] = $date;
        } else {
            $sql .= " AND datetime(replace(trips.departure_time, 'T', ' ')) >= datetime('now', 'localtime')";
        }

        if(!empty($company_filter)){
            $sql .= " AND trips.company_id = ?"; 
            $params[] = $company_filter;          
        }

        switch($sort_by){
            case 'price_asc':
                $sql .= " ORDER BY trips.price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY trips.price DESC";
                break;
            case 'time_asc':
            default:
                $sql .= " ORDER BY trips.departure_time ASC";
                break;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($company_id, $departure_city, $destination_city, $departure_time, $arrival_time, $price, $capacity) {
        $uuid = generateUuid();

        $sql = "INSERT INTO \"trips\" (id, company_id, departure_city, destination_city, departure_time, arrival_time, price, capacity) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            $uuid, 
            $company_id, 
            $departure_city, 
            $destination_city, 
            $departure_time, 
            $arrival_time, 
            $price, 
            $capacity
        ]);
    }

    public function findById($id) {
        $sql = "SELECT trips.*, bus_companies.name AS company_name 
                FROM trips
                JOIN bus_companies ON trips.company_id = bus_companies.id
                WHERE trips.id = ?";
        
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getTripsByCompanyId($company_id){
        $sql = "SELECT * FROM trips WHERE company_id = ? ORDER BY departure_time DESC";
        $statement = $this->db->prepare($sql);
        $statement->execute(([$company_id]));

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($trip_id, $departure_city, $destination_city, $departure_time, $arrival_time, $price, $capacity){
        $sql = "UPDATE trips SET 
                    departure_city = ?, 
                    destination_city = ?, 
                    departure_time = ?, 
                    arrival_time = ?, 
                    price = ?, 
                    capacity = ? 
                WHERE id = ?";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            $departure_city, 
            $destination_city, 
            $departure_time, 
            $arrival_time, 
            $price, 
            $capacity,
            $trip_id
        ]);
    }

    public function delete($trip_id){
        $sql = "DELETE FROM trips WHERE id = ?";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$trip_id]);
    }
}