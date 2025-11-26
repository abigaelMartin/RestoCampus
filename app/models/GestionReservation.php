<?php
require_once(__DIR__ . '/../../config/bdd.php');

class GestionReservation {

    public static function getReservation() {
        global $conn;
        $stmt = $conn->query("SELECT *, Commande.statut as statutReserv FROM PropositionArticleJour 
                              JOIN Article ON PropositionArticleJour.id_article = Article.id_article JOIN Commander ON PropositionArticleJour.id_ArtJour = Commander.id_ArtJour
                              JOIN Commande ON Commander.id_commande = Commande.id_commande
                              JOIN Utilisateur ON Commande.id_user = Utilisateur.id_user");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getProposition() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM PropositionArticleJour 
                              JOIN Article ON PropositionArticleJour.id_article = Article.id_article ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function propositionMenu($id_article, $propose, $qte) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO PropositionArticleJour (id_article, propose, qte) 
                                VALUES (:id_article, :propose, :qte)
                                ON DUPLICATE KEY UPDATE propose = :propose, qte = :qte");
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':propose', $propose);
        $stmt->bindParam(':qte', $qte);
        return $stmt->execute();
    }

    public static function getReservationById($id) {
        global $conn;
        $sql = "SELECT * , Commande.statut as statutCmd FROM PropositionArticleJour 
                                JOIN Article ON PropositionArticleJour.id_article = Article.id_article 
                                JOIN Commander ON PropositionArticleJour.id_ArtJour = Commander.id_ArtJour
                                JOIN Commande ON Commander.id_commande = Commande.id_commande
                                JOIN Utilisateur ON Commande.id_user = Utilisateur.id_user
                                WHERE Commande.id_commande = :id";
       
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
       
  }
    }