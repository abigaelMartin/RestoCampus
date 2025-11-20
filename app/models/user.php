<?php
require_once(__DIR__ . '/../../config/bdd.php');

class User {
    
    public static function getUser() {
        global $conn;

         $stmt = $conn->prepare("SELECT * FROM Utilisateur ORDER BY id_user DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}