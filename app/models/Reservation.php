<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Reservation {

    

    public static function getMenusDisponibles() {
        global $conn;
        $today = date('Y-m-d');
       
        $sql = ("SELECT *
        FROM PropositionArticleJour 
        INNER JOIN Article  ON Article.id_article = PropositionArticleJour.id_article
        WHERE date_du_jour = :today  AND heure_deb <= CURTIME() AND heure_fin >= CURTIME()
        ORDER BY heure_deb, libelleArt");
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        // $stmt->bindValue(':now', $now, PDO::PARAM_STR);
        $stmt->execute();
        return $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Fonction pour récupérer les réservations d’un utilisateur
    public static function mesReservations($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT *
                                FROM Commande 
                                JOIN Commander  ON Commande.id_commande = Commander.id_commande 
                                JOIN PropositionArticleJour ON PropositionArticleJour.id_ArtJour = Commander.id_ArtJour 
                                JOIN Article ON Article.id_article = PropositionArticleJour.id_article
                                WHERE Commande.id_user = :id 
                                ORDER BY date_de_commande DESC");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Fonction pour effectuer une réservation
    public static function reservermenu($id_utilisateur, $id_proposition) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO Commande (id_user) 
                                VALUES (:id_utilisateur)");
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->execute();
        echo $id_proposition;
        $id_commande = $conn->lastInsertId();

        $stmt1 = $conn->prepare("INSERT INTO Commander (id_commande, id_ArtJour) VALUES (:id_commande, :id_ArtJour)");
        $stmt1->bindParam(':id_commande', $id_commande, PDO::PARAM_INT);
        $stmt1->bindParam(':id_ArtJour', $id_proposition,PDO::PARAM_INT);
        $stmt1->execute();

        $stmt2 = $conn->prepare("UPDATE PropositionArticleJour
        SET qte_max = qte_max - 1
        WHERE id_ArtJour = ?");
        $stmt2->execute([$id_proposition]);

        if ($stmt1){
            return true;
        }else{
            return false;
        }

    }

    public static function annuler($id, $id_Art){
        global $conn;
        $stmt = $conn->prepare("UPDATE Commande SET statut='Annulée' WHERE id_commande = ?");
        $stmt->execute([$id]);

        $stmt1 = $conn->prepare("UPDATE PropositionArticleJour SET qte_max = qte_max + 1
        WHERE id_ArtJour = ? ");
        $stmt1->execute([$id_Art]);
        
    }

    public static function getDetails($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT *
                                FROM Commande 
                                JOIN Commander  ON Commande.id_commande = Commander.id_commande 
                                JOIN PropositionArticleJour ON PropositionArticleJour.id_ArtJour = Commander.id_ArtJour 
                                JOIN Article ON Article.id_article = PropositionArticleJour.id_article
                                WHERE Commande.id_commande = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}