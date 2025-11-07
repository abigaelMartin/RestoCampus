<?php

// --- Sécurité : accès réservé aux gestionnaires / admins ---
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user'])) {
  header('Location: /public/?controleur=auth&action=login');
  exit();
}

$user = $_SESSION['user'];
$statut = strtolower($user['statut'] ?? '');
if (!in_array($statut, ['gestionnaire', 'admin'])) {
  header('Location: /public/?controleur=reservation&action=liste');
  exit();
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom'] ?? '');
  $ingredients = trim($_POST['ingredients'] ?? '');

  if ($nom && $ingredients) {
    require_once __DIR__ . '/../../../config/bdd.php'; // adapte le chemin selon ton arborescence

    $stmt = $pdo->prepare("INSERT INTO menu (nom, ingredients) VALUES (:nom, :ingredients)");
    $stmt->execute([
      ':nom' => $nom,
      ':ingredients' => $ingredients,
    ]);

    $success = "Le menu a bien été ajouté ✅";
  } else {
    $error = "Merci de remplir tous les champs.";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un menu - RestoCampus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4 text-center"><i class="bi bi-plus-circle me-2"></i>Ajouter un menu</h1>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="card p-4 shadow-sm bg-white">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom du menu</label>
        <input type="text" id="nom" name="nom" class="form-control" placeholder="Ex : Poulet basquaise" required>
      </div>

      <div class="mb-3">
        <label for="ingredients" class="form-label">Ingrédients</label>
        <textarea id="ingredients" name="ingredients" class="form-control" rows="4" placeholder="Liste des ingrédients..." required></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <a href="?controleur=menu&action=liste" class="btn btn-outline-secondary">← Retour</a>
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
      </div>
    </form>
  </div>
</body>
</html>