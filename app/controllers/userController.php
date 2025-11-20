<?php
require_once(__DIR__ . '/../models/user.php');


$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'liste':
        $users = User::getUser();
        require(__DIR__ . '/../views/listeUser.php');
        break;

    default:
        echo "Action inconnue.";
        break;
}

?>