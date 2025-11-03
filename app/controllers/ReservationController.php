<?php
require_once(__DIR__ . '/../models/Reservation.php');
session_start();

class ReservationController {

    public static function reserver() {
        // Vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: /RestoCampus/public/?action=login");
            exit;
        }

        // Vérifie le statut "étudiant"
        if ($_SESSION['user']['statut'] !== 'etudiant') {
            $error = "Accès réservé aux étudiants.";
            require(__DIR__ . '/../views/error.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? null;
            $heure = $_POST['heure'] ?? null;
            $personnes = $_POST['personnes'] ?? 1;
            $login = $_SESSION['user']['prenom'] . " " . $_SESSION['user']['nom'];

            if ($date && $heure) {
                $ok = Reservation::creer($login, $date, $heure, $personnes);
                if ($ok) {
                    $success = "Réservation effectuée avec succès pour le $date à $heure.";
                } else {
                    $error = "Erreur lors de la réservation. Réessayez.";
                }
            } else {
                $error = "Veuillez remplir tous les champs.";
            }
        }

        require(__DIR__ . '/../views/reservation.php');
    }
}