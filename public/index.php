<?php
session_start();
require_once(__DIR__ . '/../app/controllers/AuthController.php');

// Si la page est appelée avec ?action=login ou ?action=logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    AuthController::logout();
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'login') {
    AuthController::login();
    exit;
}

// Si l'utilisateur n'est pas connecté, afficher directement la page de login
if (!isset($_SESSION['user'])) {
    require(__DIR__ . '/../app/views/login.php');
    exit;
}

// Sinon, afficher la page d'accueil
require(__DIR__ . '/../app/views/home.php');
exit;