<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Menu {

    /**
     * Récupère tous les menus (Propositions du jour)
     */
    public static function getAllMenus(): array {
        global $conn;
        $stmt = $conn->query("
            SELECT pa.id_ArtJour, a.id_article, a.libelleArt, a.Description, pa.propose, pa.qte
            FROM PropositionArticleJour pa
            JOIN Article a ON pa.id_article = a.id_article
            ORDER BY pa.id_ArtJour DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un menu précis avec ses articles
     */
    public static function getMenuById(int $id): ?array {
        global $conn;

        // Récupération des articles proposés pour ce menu
        $stmt = $conn->prepare("
            SELECT pa.id_ArtJour, a.id_article, a.libelleArt, a.Description, pa.propose, pa.qte
            FROM PropositionArticleJour pa
            JOIN Article a ON pa.id_article = a.id_article
            WHERE pa.id_ArtJour = :id
        ");
        $stmt->execute([':id' => $id]);
        $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menu ?: null;
    }

    /**
     * Proposer un ou plusieurs articles pour un menu
     * @param array $articles : tableau ['id_article' => propose/qte]
     */
    public static function proposeMenu(array $articles): bool {
        global $conn;

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("
                INSERT INTO PropositionArticleJour (id_article, propose, qte)
                VALUES (:id_article, :propose, :qte)
                ON DUPLICATE KEY UPDATE propose = :propose, qte = :qte
            ");

            foreach ($articles as $id_article => $data) {
                $stmt->execute([
                    ':id_article' => (int)$id_article,
                    ':propose'    => (int)($data['propose'] ?? 0),
                    ':qte'        => (int)($data['qte'] ?? 0),
                ]);
            }

            $conn->commit();
            return true;

        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Erreur proposeMenu : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer un menu / proposition d'article
     */
    public static function deleteProposition(int $id_ArtJour): bool {
        global $conn;
        try {
            $stmt = $conn->prepare("DELETE FROM PropositionArticleJour WHERE id_ArtJour = :id");
            return $stmt->execute([':id' => $id_ArtJour]);
        } catch (PDOException $e) {
            error_log("Erreur deleteProposition : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer toutes les propositions pour un article spécifique
     */
    public static function getPropositionsByArticle(int $id_article): array {
        global $conn;
        $stmt = $conn->prepare("
            SELECT *
            FROM PropositionArticleJour
            WHERE id_article = :id_article
        ");
        $stmt->execute([':id_article' => $id_article]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}