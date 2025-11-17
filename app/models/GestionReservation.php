<?php
require_once(__DIR__ . '/../../config/bdd.php');

class GestionReservation {

    public static function getReservation() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM PropositionArticleJour 
                              JOIN Article ON PropositionArticleJour.id_article = Article.id_article ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}