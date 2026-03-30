<?php

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

// Connexion au modèle
require_once(__DIR__ . '/../models/Reservation.php');

// Récupération de l'action
$action = $_GET['action'] ?? 'liste';

// Contrôleur basé sur switch
switch ($action) {
    case 'liste':
        
        // Récupère tous les menus disponibles
        $menus = Reservation::getMenusDisponibles(); // méthode à créer dans Menu.php
        require(__DIR__ . '/../views/reservation.php');
        break;

    case 'reserver':
        // Traitement de la réservation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_menu = filter_input(INPUT_POST, 'id_plat', FILTER_VALIDATE_INT);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
            $id_user = $_SESSION['user']['id'];

            // Appel au modèle pour enregistrer la réservation
            require_once(__DIR__ . '/../models/Reservation.php');
            $reservation = Reservation::reservermenu($id_user, $id_menu);
            if ($reservation) {
                echo "Réservation enregistrée ✅";
                header("Location: /RestoCampus/public/?controleur=Reservation&action=mesreservations");
                 exit;
            } else {
                echo "Erreur lors de la réservation ❌";
                 exit;

            }
        }
        break;
    case 'mesreservations':
        // Récupère les réservations de l'utilisateur connecté
        $id_user = $_SESSION['user']['id'];
        $reservations = Reservation::mesReservations($id_user); // méthode à créer dans Reservation.php
        require(__DIR__ . '/../views/mesreservations.php'); // vue à créer pour afficher les réservations
        break;

    case 'annuler':
        $id = filter_input(INPUT_POST, 'id_cmd', FILTER_VALIDATE_INT);
        $id_Art = filter_input(INPUT_POST, 'id_Art', FILTER_VALIDATE_INT);


        $act = Reservation::annuler($id, $id_Art);
        header("Location: /RestoCampus/public/?controleur=Reservation&action=mesreservations");
        break;

    default:
        echo "Action non reconnue.";
        break;
}
