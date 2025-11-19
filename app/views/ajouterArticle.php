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

// ------------------------------------------------------
// TRAITEMENT DU FORMULAIRE
// ------------------------------------------------------

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $nom = trim($_POST['nom'] ?? '');
  $ingredients = trim($_POST['ingredients'] ?? '');

  if ($nom === '' || $ingredients === '') {
    $error = "Merci de remplir tous les champs.";
  } else {

    require_once __DIR__ . '/../../../config/bdd.php';

    try {
      $stmt = $pdo->prepare("
        INSERT INTO menu (nom, ingredients)
        VALUES (:nom, :ingredients)
      ");

      $stmt->execute([
        ':nom' => $nom,
        ':ingredients' => $ingredients
      ]);

      $success = "Le menu a bien été ajouté ! ✅";

    } catch (PDOException $e) {
      $error = "Erreur SQL : " . $e->getMessage();
    }
  }
}
?>
<?php include '../app/views/layout/header.php'?>
<div class="container py-5">
    <h1 class="mb-4 text-center">
      <i class="bi bi-plus-circle me-2"></i>Ajouter un article
    </h1>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>



  <form method="post" enctype="multipart/form-data" action="?controleur=Article&action=AjouterUnArticle"  class="card p-4 shadow-sm bg-white">

    <div class="mb-3">
      <label for="nom" class="form-label">Nom du menu</label>
      <input type="text" id="nom" name="nom_menu" class="form-control" placeholder="Ex : Poulet basquaise" required>
    </div>

    <div class="mb-3">
      <label for="ingredients" class="form-label">Ingrédients / Descriptions</label>
      <textarea id="ingredients" name="ingredients" class="form-control" rows="4" placeholder="Liste des ingrédients..." required></textarea>
    </div>

    <div class="mb-3">
      <label for="photo" class="form-label">Photo du menu</label>
      <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
      <small class="text-muted">Formats acceptés : jpg, png, jpeg. Taille max : 2 Mo</small>
    </div>

    <div class="d-flex justify-content-between">
      <a href="?controleur=gestion&action=panel" class="btn btn-outline-secondary">← Retour</a>
      <button type="submit" class="btn btn-success">
        <i class="bi bi-check-lg me-1"></i>Enregistrer
      </button>
    </div>

  </form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
