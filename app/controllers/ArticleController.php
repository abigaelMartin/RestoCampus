<?php

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
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

            // 1) Récupération des champs texte
            $libelle = filter_input(INPUT_POST, 'nom_menu', FILTER_SANITIZE_SPECIAL_CHARS);
            $ing     = filter_input(INPUT_POST, 'ingredients', FILTER_SANITIZE_SPECIAL_CHARS);

            // 2) Gestion de l'image uploadée
            $imgName = null;
            
            if (!empty($_FILES['photo']['name'])) {
               
                $allowed = ['jpg','jpeg','png','gif','webp'];

                $fileName  = $_FILES['photo']['name'];
                $fileTmp   = $_FILES['photo']['tmp_name'];
                $fileError = $_FILES['photo']['error'];

                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($fileError === UPLOAD_ERR_OK && in_array($ext, $allowed)) {

                    // Nom unique pour éviter les collisions
                    $imgName = 'article_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;

                    // Dossier de destination (à adapter si besoin)
                    $uploadDir = __DIR__ . '/../../public/uploads/articles/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $destination = $uploadDir . $imgName;

                    if (!move_uploaded_file($fileTmp, $destination)) {
                        // si le move échoue, on peut décider de mettre null ou de bloquer
                        $imgName = null;
                    }
                }
            }
            
           
            // 3) Appel du modèle en passant le nom de fichier ou null
            $addMenu = Article::AjouterUnArticle($libelle, $ing, $imgName);

            if ($addMenu) {
                header("Location: /RestoCampus/public/?controleur=Article&action=liste");
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
        require_once(__DIR__ . '/../views/listeArticles.php'); // Vue qui affichera le tableau
    break;

    case 'supprimer':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $delete = Article::supprimerArticle($id); // Méthode à créer dans le modèle
            header("Location: ?controleur=Article&action=liste");
            exit;
        } else {
            echo "ID d'article invalide.";
            exit;
        }

    default:
        echo "Action non reconnue.";
        break;
}