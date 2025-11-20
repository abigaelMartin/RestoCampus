<?php

require_once '../app/models/Menu.php';

$action = $_GET['action'] ?? 'liste';

switch ($action) {

    // Liste des menus / propositions du jour
    case 'liste':
        $menus = Menu::getAllMenus();
        require '../app/views/listeMenu.php';
        break;

    // Ajouter un menu / proposer des articles
    case 'ajouter':
        // Récupérer tous les articles pour les afficher en checkbox
        require_once '../app/models/Article.php';
        $articles = Article::getAllArticles(); // méthode à créer dans ton modèle Article si pas déjà

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propositions = [];

            // $_POST['articles'] = tableau d'IDs cochés
            foreach ($_POST['articles'] ?? [] as $id_article) {
                $propositions[(int)$id_article] = [
                    'propose' => 1,   // on considère coché = proposé
                    'qte'     => $_POST['qte'][$id_article] ?? 1 // quantité facultative
                ];
            }

            // Enregistrer les propositions dans la base
            Menu::proposeMenu($propositions);

            header("Location: ?controleur=menu&action=liste");
            exit;
        }

        require '../app/views/ajouterMenu.php';
        break;

    default:
        echo "Action inconnue.";
        break;
}
