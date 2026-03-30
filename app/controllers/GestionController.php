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
$action = $_GET['action'] ?? 'panel';

// Contrôleur basé sur switch
switch ($action) {

    case 'panel':
        // affiche la page de gestion
        require(__DIR__ . '/../views/gestion.php');
        break;

    default:
        echo "Action non reconnue pour gestion.";
        break;
}