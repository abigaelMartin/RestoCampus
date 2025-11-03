<?php
session_start();

// Inclusion des contrôleurs nécessaires
require_once(__DIR__ . '/../app/controllers/AuthController.php');
require_once(__DIR__ . '/../app/controllers/ReservationController.php');

// Vérifie si une action est passée dans l’URL
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            AuthController::login();
            exit;

        case 'logout':
            AuthController::logout();
            exit;

        case 'reservation':
            ReservationController::reserver();
            exit;

        default:
            echo "Page non trouvée.";
            exit;
    }
}

// Si aucune action n’est passée :
if (!isset($_SESSION['user'])) {
    // L’utilisateur n’est pas connecté → afficher le login
    require(__DIR__ . '/../app/views/login.php');
    exit;
}

// Si l’utilisateur est connecté :
if ($_SESSION['user']['statut'] === 'etudiant') {
    // Redirige vers la page de réservation si c’est un étudiant
    header("Location: /RestoCampus/public/?action=reservation");
    exit;
}

// Sinon, pour d’autres statuts (ex. personnel, admin, etc.)
require(__DIR__ . '/../app/views/home.php');
exit;