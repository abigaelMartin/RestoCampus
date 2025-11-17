<?php include '../app/views/layout/header.php'?>
<div class="container py-5">
    <h1 class="mb-4 text-center">
        <i class="bi bi-plus-square me-2"></i>Ajouter un article
    </h1>

    <!-- Messages succès / erreur -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="?controleur=AjouterArticle&action=EnregistrerArticle" class="card p-4 shadow-sm bg-white">

        <div class="mb-3">
            <label for="nom_article" class="form-label">Nom de l'article</label>
            <input type="text" id="nom_article" name="nom_article" class="form-control" placeholder="Ex : Poulet rôti" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Description de l'article..." required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Associer aux menus</label>
            <div class="d-flex flex-column gap-1">
                <?php if (!empty($menus)): ?>
                    <?php foreach ($menus as $menu): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="menus[]" value="<?= $menu['id'] ?>" id="menu<?= $menu['id'] ?>">
                            <label class="form-check-label" for="menu<?= $menu['id'] ?>">
                                <?= htmlspecialchars($menu['nom']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-muted">Aucun menu disponible. Ajoutez d'abord un menu.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="?controleur=Gestion&action=index" class="btn btn-outline-secondary">← Retour</a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i>Enregistrer
            </button>
        </div>

    </form>
</div>

<style>
.card {
    border-radius: 1rem;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 3px 10px rgba(0,0,0,.04);
}

.form-check-input, .form-check-label {
    cursor: pointer;
}
</style>