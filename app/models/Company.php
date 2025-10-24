<?php

class Company{
    private $db;

    public function __construct(){
        $this->db = getDatabaseConnection();
    }

    public function findById($id){
        $sql = "SELECT * FROM bus_companies WHERE id = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCompanies(){
        $sql = "SELECT * FROM bus_companies ORDER BY name ASC";
        $statement = $this->db->query($sql); 

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name){
        $id = generateUuid();
        $sql = "INSERT INTO bus_companies (id, name) VALUES (?, ?)";
        $statement = $this->db->prepare($sql);

        return $statement->execute([$id, $name]);
    }

    public function update($id, $name){
        $sql = 'UPDATE bus_companies SET name = ? WHERE id = ?';
        $statement = $this->db->prepare($sql);

        return $statement->execute([$name, $id]);
    }

    public function delete($id){
        $sql = 'DELETE FROM bus_companies WHERE id = ?';
        $statement = $this->db->prepare($sql);

        return $statement->execute([$id]);
    }
}