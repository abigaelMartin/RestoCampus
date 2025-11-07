<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Reservation {

    public static function getMenusDisponibles() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM PropositionArticleJour 
                              JOIN Article ON PropositionArticleJour.id_article = Article.id_article 
                              JOIN composer ON Article.id_article = composer.id_article 
                              JOIN Ingredient ON composer.id_ingredient = Ingredient.id_ingredient");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Fonction pour récupérer les réservations d’un utilisateur
    public static function mesReservations($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT *
                                FROM Commande 
                                JOIN commander  ON Commande.id_commande = commander.id_commande 
                                JOIN PropositionArticleJour ON PropositionArticleJour.id_ArtJour = commander.id_ArtJour JOIN Article ON Article.id_article = PropositionArticleJour.id_article
                                WHERE Commande.id_user = :id 
                                ORDER BY date_de_commande DESC");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Fonction pour effectuer une réservation
    public static function reserver($id_utilisateur, $id_proposition, $date_reservation) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO Reservation (id_utilisateur, id_proposition, date_reservation) 
                                VALUES (:id_utilisateur, :id_proposition, :date_reservation)");
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->bindParam(':id_proposition', $id_proposition, PDO::PARAM_INT);
        $stmt->bindParam(':date_reservation', $date_reservation);
        return $stmt->execute();
    }
}
