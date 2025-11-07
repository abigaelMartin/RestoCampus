<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - RestoCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Bienvenue, <?= htmlspecialchars($_SESSION['login']) ?> <?= htmlspecialchars($_SESSION['login']) ?> 👋</h2>
    <p>Statut : <strong><?= htmlspecialchars($_SESSION['statut']) ?></strong></p>

    <button class="btn btn-danger mt-3"> <a href="../controllers/authController.php?action=logout" > Se déconnecter </a></button>
    <button class="btn btn-primary mt-3"><a href="../controllers/reservationController.php?action=liste">Afficher le menu</a></button>
</body>
</html>