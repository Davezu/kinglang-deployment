<?php
session_start();

class ClientInfoModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addClient($first_name, $last_name, $address, $contact_number, $company_name) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO clients (first_name, last_name, address, contact_number, company_name) VALUES (:first_name, :last_name, :address, :contact_number, :company_name)");
            $result = $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":address" => $address,
                ":contact_number" => $contact_number,
                ":company_name" => $company_name
            ]);

            if (!$result) return "Inserting client info failed";
    
            $client_id = $this->conn->lastInsertID();   
            $user_id = $_SESSION["user_id"];
            
            $stmt = $this->conn->prepare("UPDATE users SET client_id = :client_id WHERE user_id = :user_id");
            $result = $stmt->execute([
                ":client_id" => $client_id,
                ":user_id" => $user_id
            ]);

            if (!$result) return "Inserting client_id into users failed";
    
            return "Client info added successfully!";
        } catch (PDOException $e) {
            return "Database error";
        }
    }
}
?>