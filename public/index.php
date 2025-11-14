<?php
session_start();

$controleur = $_GET['controleur'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if (!isset($_SESSION['user']) && !($controleur === 'auth' && $action === 'login')) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}


include "../app/views/layout/header.php";


require_once "../app/controllers/".$controleur."Controller.php";

include "../app/views/layout/footer.php";