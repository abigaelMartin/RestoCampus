<?php
// Affichage d'erreurs uniquement si nécessaire (dev)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/db.php'; // adapte le chemin si besoin

try {
    $pdo = Db::getDb();

    $sql = "SELECT id_user, email, nom, prenom, login, statut FROM Utilisateur ORDER BY id_user";
    $stmt = $pdo->query($sql);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$users || count($users) === 0) {
        echo "<p>Aucun utilisateur trouvé.</p>";
        exit;
    }

    echo "<table border='1' cellpadding='6' style='border-collapse:collapse;'>";
    echo "<thead><tr><th>ID</th><th>Login</th><th>Prénom</th><th>Nom</th><th>Email</th><th>Statut</th></tr></thead><tbody>";
    foreach ($users as $u) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($u['id_user']) . "</td>";
        echo "<td>" . htmlspecialchars($u['login']) . "</td>";
        echo "<td>" . htmlspecialchars($u['prenom']) . "</td>";
        echo "<td>" . htmlspecialchars($u['nom']) . "</td>";
        echo "<td>" . htmlspecialchars($u['email']) . "</td>";
        echo "<td>" . htmlspecialchars($u['statut']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";

} catch (PDOException $e) {
    // en dev, afficher l'erreur. En production, logguer et afficher message générique.
    echo "<strong>Erreur PDO :</strong> " . htmlspecialchars($e->getMessage());
}

