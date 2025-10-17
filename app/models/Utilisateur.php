<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Utilisateur {
    public static function getByLogin($login) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE login = :login");
        $stmt->execute(['login' => $login]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}