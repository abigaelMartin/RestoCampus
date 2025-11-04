<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Reservation {

    public static function creer($login, $date, $heure, $personnes) {
        global $conn;

        try {
            $stmt = $conn->prepare("INSERT INTO Reservation (nom_utilisateur, date_reservation, heure, personnes) 
                                    VALUES (:nom, :date, :heure, :personnes)");
            return $stmt->execute([
                'nom' => $login,
                'date' => $date,
                'heure' => $heure,
                'personnes' => $personnes
            ]);
        } catch (PDOException $e) {
            error_log("Erreur Reservation::creer - " . $e->getMessage());
            return false;
        }
    }

    public static function getMenusDisponibles() {
       global $conn;
        $stmt = $conn->query("SELECT * FROM PropositionArticleJour JOIN Article ON PropositionArticleJour.id_article = Article.id_article JOIN composer ON Article.id_article = composer.id_article JOIN Ingredient ON composer.id_ingredient = Ingredient.id_ingredient");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function reserverMenu($id_user, $id_menu) {
        $pdo = new PDO("mysql:host=localhost;dbname=restocampus", "root", "");
        $stmt = $pdo->prepare("INSERT INTO reservations (id_user, id_menu, date_reservation) VALUES (?, ?, NOW())");
        $stmt->execute([$id_user, $id_menu]);
    }
}
