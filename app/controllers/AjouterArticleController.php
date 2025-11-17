<?php
session_start();

// --- Sécurité : accès réservé aux gestionnaires / admins ---
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit();
}

$user = $_SESSION['user'];
$statut = strtolower($user['statut'] ?? '');
if (!in_array($statut, ['gestionnaire', 'admin'])) {
    header("Location: /RestoCampus/public/?controleur=reservation&action=liste");
    exit();
}

// Connexion à la base
require_once __DIR__ . '/../../../config/bdd.php';

// Récupération de l'action
$action = $_GET['action'] ?? 'AjouterArticle';

// --- Switch sur l'action ---
switch ($action) {

    // Affiche le formulaire
    case 'AjouterArticle':
        $menus = $pdo->query("SELECT id, nom FROM menu ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
        $success = null;
        $error = null;
        require(__DIR__ . '/../views/ajouterArticle.php');
        break;

    default:
        echo "Action inconnue.";
        break;
}