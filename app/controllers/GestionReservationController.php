<?php

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

$user = $_SESSION['user'];

// Sécurité : accès réservé gestionnaire/Admin
if (!isset($user['statut']) || !in_array($user['statut'], ['gestionnaire', 'Admin'])) {
    header("Location: /RestoCampus/public/");
    exit;
}

// Récupération de l’action
$action = $_GET['action'] ?? 'liste';

// fichier models
require_once(__DIR__ . '/../models/GestionReservation.php');
// Contrôleur basé sur switch
switch ($action) {

    case 'liste':
        // affiche la liste des reservation
        $reservations = GestionReservation::getReservation();
        require(__DIR__ . '/../views/ListeReservation.php'); 
        break;
    case 'listes':
        require(__DIR__ . '/../views/ajouterMenu.php');
        break;
    case 'detail':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: /RestoCampus/public/?controleur=gestionReservation&action=liste");
            exit;
        }   
        $resa = GestionReservation::getReservationById($id);
        
        require(__DIR__ . '/../views/showDetail.php');
       
        break;
    default :
        echo "Action non reconnue pour gestion.";
        break;
}