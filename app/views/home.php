<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - RestoCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?> 👋</h2>
    <p>Statut : <strong><?= htmlspecialchars($user['statut']) ?></strong></p>

    <form action="/RestoCampus/app/controllers/AuthController.php" method="GET">
        <input type="hidden" name="action" value="logout">
        <button type="submit" class="btn btn-danger mt-3">Déconnexion</button>
    </form>
</body>
</html>