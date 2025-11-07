<?php
session_start();

$controleur = $_GET['controleur'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if (!isset($_SESSION['user']) && !($controleur === 'auth' && $action === 'login')) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

require_once "../app/controllers/".$controleur."Controller.php";