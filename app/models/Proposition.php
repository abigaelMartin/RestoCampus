<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Proposition {

    public static function getPropositions(){
        global $conn;
        $stmt = $conn->query("SELECT * FROM PropositionArticleJour 
                              JOIN Article ON PropositionArticleJour.id_article = Article.id_article ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addProposition($date_du_jour, $heure_deb, $heure_fin, $qte_max, $id_article){
        global $conn; // $conn doit être une instance de PDO

        // Sécurisation simple des types
        $id_article = (int)$id_article;
        $qte_max    = (int)$qte_max;

        if ($id_article <= 0 || $qte_max <= 0) {
            return false; // on ne tente rien si les données sont invalides
        }

        $sql = "INSERT INTO PropositionArticleJour (date_du_jour, heure_deb, heure_fin, qte_max, id_article)
                VALUES (:date_du_jour, :heure_deb, :heure_fin, :qte_max, :id_article)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':date_du_jour', $date_du_jour, PDO::PARAM_STR);
        $stmt->bindValue(':heure_deb',    $heure_deb,    PDO::PARAM_STR);
        $stmt->bindValue(':heure_fin',    $heure_fin,    PDO::PARAM_STR);
        $stmt->bindValue(':qte_max',      $qte_max,      PDO::PARAM_INT);
        $stmt->bindValue(':id_article',   $id_article,   PDO::PARAM_INT);

        if ($stmt->execute()) {
            return (int)$conn->lastInsertId(); // ou true si tu n’as pas besoin de l'ID
        }

        return false;
    }

    public static function deleteProposition($idArtJour) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM PropositionArticleJour WHERE id_artJour = :idArtJour");
        $stmt->bindValue(':idArtJour', $idArtJour, PDO::PARAM_INT);
        return $stmt->execute();
    }

}