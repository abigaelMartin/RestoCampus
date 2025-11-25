<?php

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
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
        // affiche la liste des reservation
        $articles = Proposition::getProposition();
        require(__DIR__ . '/../views/propositionMenu.php'); 
        break;

    case 'addproposition':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            
            echo $date_du_jour = filter_input(INPUT_POST, 'date_du_jour', FILTER_SANITIZE_SPECIAL_CHARS)."<br>";
            echo $heure_deb    = filter_input(INPUT_POST, 'heure_deb',FILTER_SANITIZE_SPECIAL_CHARS)."<br>";
            echo $heure_fin    = filter_input(INPUT_POST, 'heure_fin',FILTER_SANITIZE_SPECIAL_CHARS)."<br>";
            
            $articles        = $_POST['articles'] ?? [];
            foreach ($articles as $idArticle => $data) {

                echo $id_article = isset($data['propose'])."<br>";
                echo $qte     = isset($data['qte']) ? (int)$data['qte'] : 0 ;

                // Ici tu fais ton insertion SQL :
                // INSERT INTO PropositionArticleJour (id_article, date_jour, heure_debut, heure_fin, qte) ...
            }

            // Valider les données (ajouter des validations supplémentaires si nécessaire)
            if ($id_article && $date_du_jour && $heure_deb && $heure_fin && $qte) {
                // Appeler la méthode pour ajouter la proposition
                Proposition::addPropostion($id_article, $date_du_jour, $heure_deb, $heure_fin, $qte_max);
                // Rediriger ou afficher un message de succès
               
                exit;
            } else {
                $error = "Tous les champs sont requis.";
            }
        }
        require(__DIR__ . '/../views/propositionMenu.php');
        break;
        
    default:
        echo "Action non reconnue pour gestion.";
        break;
}