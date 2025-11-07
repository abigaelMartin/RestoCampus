<?php


// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
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
        require(__DIR__ . '/../views/reservation.php'); // vue à créer pour afficher les menus
        break;

    case 'reserver':
        // Traitement de la réservation
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $id_menu = intval($_POST['id_menu']);
        //     $id_user = $_SESSION['id'];

        //     // Appel au modèle pour enregistrer la réservation
        //     require_once(__DIR__ . '/../models/Reservation.php');
        //     Reservation::reserverMenu($id_user, $id_menu);

        //     header("Location: /RestoCampus/public/?controleur=reservation&action=liste");
        //     exit;
        // }
        // break;
    case 'mesreservations':
        // Récupère les réservations de l'utilisateur connecté
        $id_user = $_SESSION['user']['id'];
        $reservations = Reservation::mesReservations($id_user); // méthode à créer dans Reservation.php
        require(__DIR__ . '/../views/mesreservations.php'); // vue à créer pour afficher les réservations
        break;

    default:
        echo "Action non reconnue.";
        break;
}
