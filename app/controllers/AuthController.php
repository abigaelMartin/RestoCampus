<?php
require_once(__DIR__ . '/../models/Utilisateur.php');
session_start();

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST['password'];

            $user = Utilisateur::getByLogin($login);

            if ($user && $user['password'] === $password) {
                session_start();
                $_SESSION['id'] = $user['id_user'];
                $_SESSION['login'] = $user['login'];
                $_SESSION['statut'] = $user['statut'];

                header("Location: /RestoCampus/app/views/home.php");
                exit;
            } else {
                $message = "Identifiants incorrects.";
            }
        }
        require(__DIR__ . '/../views/login.php');
        break;

    case 'logout':
        session_start();
        session_unset();
        session_destroy();
        header("Location: /RestoCampus/public/?action=login");
        exit;
        break;

    default:
        echo "Action inconnue.";
        break;
}

?>