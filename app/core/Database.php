<?php

function getDatabaseConnection() {
    static $connection = null;

    if($connection === null){
        try{
            $path = __DIR__ . '/../../database/database.db';
            
            $connection = new PDO("sqlite:" . $path);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }
    return $connection;
}