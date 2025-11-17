<?php
require_once(__DIR__ . '/../models/Utilisateur.php');


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
                $_SESSION['user'] = [
                    'id' => $user['id_user'],
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'login' => $user['login'],
                    'statut' => $user['statut']
                ];
                header("Location: ../public/?controleur=reservation&action=liste");
                exit;
            } else {
                $message = "Identifiants incorrects.";
            }
        }
        require(__DIR__ . '/../views/login.php');
        break;
        
    case 'profil':
        require(__DIR__ . '/../views/profil.php');
        break;
      
    case 'logout':
        session_start();
        session_unset();
        session_destroy();
        header("Location: /RestoCampus/public/?action=login");
        break;
    
    default:
        echo "Action inconnue.";
        break;
}

?>