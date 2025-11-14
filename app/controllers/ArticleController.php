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
        
    case 'AjouterMenu': 
        include __DIR__ . '/../views/ajouterMenu.php';
        break; 
    default:
        echo "Action non reconnue.";
        break;
}