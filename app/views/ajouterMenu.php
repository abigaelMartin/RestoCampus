<?php 
// Sécurité
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

$title = "Ajouter un menu";

// petite fonction d'échappement
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
?>

<?php include '../app/views/layout/header.php'; ?>

<div class="container py-4">

    <!-- Bouton Retour -->
    <a href="<?= $_SERVER['HTTP_REFERER'] ?? '?controleur=menu&action=liste' ?>" 
       class="btn btn-outline-primary mb-4" style="display:inline-flex;align-items:center;">
        <i class="bi bi-arrow-left-circle me-2 fs-5"></i> Retour
    </a>

    <h1 class="mb-4 text-center">
    <i class="bi bi-plus-circle me-2"></i>Ajouter un menu
  </h1>

    <form action="?controleur=menu&action=ajouter" method="POST" class="card p-4 shadow-sm">

        <!-- Nom du menu -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Nom du menu :</label>
            <input type="text" name="nom_menu" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Description :</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <!-- Articles / ingrédients -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Sélection des articles du menu :</label>

            <?php if (empty($articles)): ?>
                <p class="text-muted">Aucun article disponible. <a href="?controleur=article&action=AjouterUnArticle">Créer un article</a></p>
            <?php else: ?>

            <div class="row g-3">
                <?php foreach ($articles as $a): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="card p-3 shadow-sm border rounded d-flex gap-3" 
                               style="cursor:pointer;">
                            
                            <input type="checkbox" 
                                   name="articles[]" 
                                   value="<?= e($a['id_article']) ?>" 
                                   class="form-check-input mt-1">

                            <div>
                                <strong><?= e($a['libelleArt']) ?></strong><br>
                                <small class="text-muted"><?= e($a['Description']) ?></small>
                            </div>

                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
        </div>

        <!-- Bouton -->
        <button type="submit" class="btn btn-success mt-3">
            <i class="bi bi-check-circle me-1"></i> Créer le menu
        </button>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>