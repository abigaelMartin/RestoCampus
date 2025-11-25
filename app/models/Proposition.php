<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Proposition {

    public static function getProposition() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM PropositionArticleJour 
                              JOIN Article ON PropositionArticleJour.id_article = Article.id_article ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addPropostion($id_article, $date_du_jour, $heure_deb, $heure_fin, $qte_max){
        global $conn;
        
        $stmt = $conn->query("INSERT INTO PropositionArticleJour (id_article, date_du_jour, heure_deb, heure_fin, qte_max) 
                              VALUES (:id_article, :date_du_jour, :heure_deb, :heure_fin, :qte_max)");
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':date_du_jour', $date_du_jour);
        $stmt->bindParam(':heure_deb', $heure_deb);
        $stmt->bindParam(':heure_fin', $heure_fin);
        $stmt->bindParam(':qte_max', $qte_max);
        $stmt->execute();

        
        
    }

}