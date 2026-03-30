<?php 
// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

$title = 'Liste des articles';

// petite fonction d'échappement réutilisable
if (!function_exists('e')) {
    function e($v) {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}
?>

<?php include '../app/views/layout/header.php'; ?>

<style>
  .page-head{
    background:linear-gradient(to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));
    border-bottom:1px solid #eef2f7;
  }
  .badge-soft{
    background:#eff6ff;
    color:#1d4ed8;
    border-radius:999px;
    padding:.15rem .6rem;
    font-size:.78rem;
  }
  .desc-trunc{
    max-width: 380px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .thumb-article{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:.5rem;
    border:1px solid rgba(15,23,42,.06);
    background:#f1f5f9;
  }
</style>

<header class="page-head py-4 mb-3">
  <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Articles</h1>
      <p class="mb-0 text-muted">Gestion des ingrédients / produits utilisés pour composer les menus.</p>
    </div>
    <div class="d-flex flex-column align-items-end">
      <span class="badge-soft mb-1">
        <i class="bi bi-box-seam me-1"></i>
        <?= !empty($articles) ? count($articles) . ' article(s)' : 'Aucun article' ?>
      </span>
      <a href="?controleur=article&action=AjouterUnArticle" class="btn btn-primary mt-1">
        <i class="bi bi-plus-circle me-1"></i>Ajouter un article
      </a>
    </div>
  </div>
</header>

<section class="py-2">
  <div class="container">

    <?php if (empty($articles)): ?>
      <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <div>Aucun article trouvé dans la base de données. Commencez par en créer un.</div>
      </div>
    <?php else: ?>

      <!-- Barre de recherche (optionnelle, filtre côté serveur à brancher si tu veux) -->
      <div class="col-sm-8 col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search"
                id="searchUsers"
                class="form-control"
                placeholder="Rechercher par Libelle, Desc, ID">
        </div>
    </div>      

    <div class="table-responsive">
        <table class="table align-middle table-hover" id="ArtcileTable">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Libellé</th>
              <th>Description</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($articles as $art): ?>
              <?php
                // récupération des champs
                $id          = $art['id_article'] ?? $art['id'] ?? null;
                $libelle     = $art['libelleArt']   ?? '';
                $description = $art['Description']  ?? '';

                // image : adapte le nom du champ ci-dessous à ta BDD
                // par ex. $art['image'], $art['photo'], etc.
                $imageUrl = !empty($art['img'])
                  ? '/RestoCampus/public/uploads/articles/' . $art['img']
                  : '/RestoCampus/public/assets/img/article-placeholder.jpg';
 // image par défaut à créer si tu veux
              ?>
              <tr>
                <td><?= e($id) ?></td>

                <!-- Colonne image -->
                <td>
                  <img src="<?= $imageUrl ?>" alt="Image de <?= e($libelle) ?>" class="thumb-article">
                </td>

                <td class="fw-semibold"><?= e($libelle) ?></td>

                <!-- Description tronquée -->
                <td>
                  <span class="desc-trunc" title="<?= e($description) ?>">
                    <?= e($description) ?>
                  </span>
                </td>

                <td class="text-end">
                  <a href="?controleur=article&action=modifier&id=<?= e($id) ?>" 
                     class="btn btn-sm btn-outline-warning" title="Modifier">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="?controleur=article&action=supprimer&id=<?= e($id) ?>" 
                     class="btn btn-sm btn-outline-danger" title="Supprimer"
                     onclick="return confirm('Confirmer la suppression de cet article ?');">
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
<script>
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("searchUsers");
    const table = document.getElementById("ArtcileTable");
    const rows  = table.querySelectorAll("tbody tr");

    input.addEventListener("input", () => {
        const q = input.value.toLowerCase().trim();

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(q) ? "" : "none";
        });
    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
