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
    case 'AjouterUnMenu':
        // Traitement de la réservation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = filter_input(INPUT_POST, 'nom_menu',FILTER_SANITIZE_SPECIAL_CHARS );
            $ing = filter_input(INPUT_POST, 'ingredients', FILTER_SANITIZE_SPECIAL_CHARS);

            // Appel au modèle pour enregistrer la réservation
            require_once(__DIR__ . '/../models/menu.php');
            $addMenu = Menu::AjouterUnMenu($libelle, $ing);
            if ($addMenu) {
                echo "Réservation enregistrée ✅";
                header("Location: /RestoCampus/public/?controleur=reservation&action=liste");
                 exit;
            } else {
                echo "Erreur lors de la réservation ❌";
                 exit;

            }

           
           
        }
        break;
    default:
        echo "Action non reconnue.";
        break;
}