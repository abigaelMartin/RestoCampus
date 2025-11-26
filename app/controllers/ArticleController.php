<?php

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

// Connexion aux modèles
require_once(__DIR__ . '/../models/Reservation.php');
require_once(__DIR__ . '/../models/Article.php'); // pour pouvoir lister les articles

// Récupération de l'action
$action = $_GET['action'] ?? 'liste';

// Contrôleur basé sur switch
switch ($action) {
    
    case 'AjouterUnArticle':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = filter_input(INPUT_POST, 'nom_menu', FILTER_SANITIZE_SPECIAL_CHARS);
            $ing     = filter_input(INPUT_POST, 'ingredients', FILTER_SANITIZE_SPECIAL_CHARS);
            $img = filter_input(INPUT_POST, 'img', FILTER_SANITIZE_SPECIAL_CHARS);

            $addMenu = Article::AjouterUnArticle($libelle, $ing, $img);
            if ($addMenu) {
                header("Location: /RestoCampus/public/?controleur=article&action=liste");
                exit;
            } else {
                echo "Erreur lors de l'ajout de l'article ❌";
                exit;
            }

        } else {
            require(__DIR__ . '/../views/ajouterArticle.php');
        }
        break;

    case 'liste':  // ← Nouvelle case pour lister les articles
        $articles = Article::getAllArticles(); // Méthode à créer dans le modèle
        require(__DIR__ . '/../views/listeArticles.php'); // Vue qui affichera le tableau
        break;

    default:
        echo "Action non reconnue.";
        break;
}