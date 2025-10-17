<?php
require_once(__DIR__ . '/../models/Utilisateur.php');
session_start();

class AuthController {

    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $user = Utilisateur::getByLogin($login);

            if ($user && $user['password'] === $password) {
                $_SESSION['user'] = [
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'statut' => $user['statut']
                ];
                header("Location: /SLAM/RestoCampus/public/");
                exit;
            } else {
                $error = "Identifiants incorrects.";
                require(__DIR__ . '/../views/login.php');
                exit;
            }
        } else {
            require(__DIR__ . '/../views/login.php');
            exit;
        }
    }

    public static function logout() {
        session_unset();
        session_destroy();
        header("Location: /SLAM/RestoCampus/public/?action=login");
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    AuthController::logout();
}