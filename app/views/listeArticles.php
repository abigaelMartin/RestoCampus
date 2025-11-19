<?php
// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

$title = 'Liste des articles';
?>

<?php include '../app/views/layout/header.php'; ?>

<section class="py-4">
    <div class="container">

        <header class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 fw-bold mb-0">Articles</h1>
            <a href="?controleur=article&action=AjouterUnArticle" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Ajouter un article
            </a>
        </header>

        <?php if (empty($articles)): ?>
            <div class="alert alert-info">Aucun article trouvé dans la base de données.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $art): ?>
                            <tr>
                                <td><?= e($art['id']) ?></td>
                                <td><?= e($art['libelleArt']) ?></td>
                                <td><?= e($art['Description']) ?></td>
                                <td class="text-end">
                                    <a href="?controleur=article&action=modifier&id=<?= e($art['id']) ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?controleur=article&action=supprimer&id=<?= e($art['id']) ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Confirmer la suppression ?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>