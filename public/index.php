<?php



session_start();

$controleur = $_GET['controleur'] ?? 'Auth';
$action = $_GET['action'] ?? 'login';

if (!isset($_SESSION['user']) && !($controleur === 'Auth' && $action === 'login')) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

require_once "../app/controllers/".$controleur."Controller.php";
include "../app/views/layout/footer.php";
