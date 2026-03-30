<?php

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

$user = $_SESSION['user'];

// Sécurité : accès réservé gestionnaire/Admin
if (!isset($user['statut']) || !in_array($user['statut'], ['gestionnaire', 'Admin'])) {
    header("Location: /RestoCampus/public/");
    exit;
}

// Récupération de l’action
$action = $_GET['action'] ?? 'liste';

// fichier models
require_once(__DIR__ . '/../models/Proposition.php');
// Contrôleur basé sur switch
switch ($action) {

    case 'liste':
        $propositions = Proposition::getPropositions();
        require (__DIR__ . '/../views/GestionProposition.php');
        break;

    case 'proposer':
        // affiche la liste des reservation
        require_once(__DIR__ . '/../models/Article.php');
        $articles = Article::getAllArticles();
        require(__DIR__ . '/../views/propositionMenu.php'); 
        break;

    case 'addproposition':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1. Récupérer les données du formulaire
            $date_du_jour = filter_input(INPUT_POST, 'date_du_jour', FILTER_SANITIZE_SPECIAL_CHARS);
            $heure_deb    = filter_input(INPUT_POST, 'heure_deb', FILTER_SANITIZE_SPECIAL_CHARS);
            $heure_fin    = filter_input(INPUT_POST, 'heure_fin', FILTER_SANITIZE_SPECIAL_CHARS);

            // Sécurité simple : vérifier que tout est bien là
            if (!$date_du_jour || !$heure_deb || !$heure_fin) {
                // Tu peux stocker un message en session si tu veux
                // $_SESSION['flash_error'] = "Date ou créneau invalide.";
                header("Location: /RestoCampus/public/?controleur=proposition&action=proposer");
                exit;
            }

            // 2. Récupérer les articles
            $articles = $_POST['articles'] ?? [];

            // 3. Boucle sur les articles sélectionnés
            foreach ($articles as $idArticle => $data) {

                // Ne garder que les articles cochés
                if (!isset($data['propose'])) {
                    continue;
                }

                $idArticle = (int)$idArticle;
                if ($idArticle <= 0) {
                    continue;
                }

                // Récupérer la quantité
                $qte = (int)($data['qte'] ?? 0);

                // On ignore les quantités nulles ou négatives
                if ($qte <= 0) {
                    continue;
                }

                // 4. Appel du modèle pour insérer
                Proposition::addProposition($date_du_jour, $heure_deb, $heure_fin, $qte, $idArticle);
            }

            // 5. Redirection après avoir traité TOUS les articles
            header("Location: /RestoCampus/public/?controleur=Reservation&action=liste");
            exit;
        }
        
        
        require(__DIR__ . '/../views/propositionMenu.php');
        break;
    case 'deleteProposition':
        // Code pour supprimer une proposition (à implémenter)
        break;    
    default:
        echo "Action non reconnue pour gestion.";
        break;
}