<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Article {
    
    public static function AjouterUnArticle($libelle, $Desc, $img) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO Article (libelleArt, Description, img) 
                                VALUES (:libelle, :Description, :img)");
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt->bindParam(':Description', $Desc, PDO::PARAM_STR);
        $stmt->bindParam(':img', $img, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt ? true : false;
    }

    // Nouvelle méthode pour lister tous les articles
    public static function getAllArticles() {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM Article ORDER BY id_article DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}