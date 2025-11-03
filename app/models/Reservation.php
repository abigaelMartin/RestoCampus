<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Reservation {

    public static function creer($login, $date, $heure, $personnes) {
        global $conn;

        try {
            $stmt = $conn->prepare("INSERT INTO Reservation (nom_utilisateur, date_reservation, heure, personnes) 
                                    VALUES (:nom, :date, :heure, :personnes)");
            return $stmt->execute([
                'nom' => $login,
                'date' => $date,
                'heure' => $heure,
                'personnes' => $personnes
            ]);
        } catch (PDOException $e) {
            error_log("Erreur Reservation::creer - " . $e->getMessage());
            return false;
        }
    }
}