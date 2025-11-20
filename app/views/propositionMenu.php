<?php
// Sécurité : accès gestionnaire ou admin
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['statut'] ?? '', ['gestionnaire', 'Admin'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

$title = $title ?? 'Proposition des articles du jour';

// Helpers
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

// $dateJour : date sélectionnée (format Y-m-d) transmise par le contrôleur
$dateJour = $dateJour ?? date('Y-m-d');

// $csrf_token optionnel
?>

<?php include '../app/views/layout/header.php'; ?>

<style>
  .page-head{
    background:linear-gradient(to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));
    border-bottom:1px solid #eef2f7;
  }
  .thumb-article{
    width:56px; height:56px; border-radius:.7rem;
    object-fit:cover;
    border:1px solid rgba(15,23,42,.06);
    background:#f1f5f9;
  }
  .desc-trunc{
    max-width: 320px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .badge-soft{
    background:#eff6ff;
    color:#1d4ed8;
    border-radius:999px;
    padding:.15rem .6rem;
    font-size:.78rem;
  }
</style>

<!-- En-tête -->
<header class="page-head py-4 mb-3">
  <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Proposition des articles du jour</h1>
      <p class="mb-0 text-muted">
        Sélectionnez la date, puis cochez les articles qui seront réservable pour cette journée.
      </p>
    </div>
    <div class="text-end">
      <span class="badge-soft">
        <i class="bi bi-info-circle me-1"></i>
        Un article coché est ajouté dans <code>PropositionArticleJour</code> pour la date choisie.
      </span>
    </div>
  </div>
</header>

<section class="py-3">
  <div class="container">

    <!-- Formulaire principal -->
    <form method="post" action="/RestoCampus/public/?controleur=proposition&action=enregistrerJour">
      <?php if (!empty($csrf_token)): ?>
        <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
      <?php endif; ?>

      <!-- Date du jour -->
      <div class="row g-3 mb-4 align-items-end">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Date concernée</label>
          <input type="date"
                 class="form-control"
                 name="date_jour"
                 value="<?= e($date_du_Jour) ?>"
                 required>
        </div>

        <div class="col-md-8 text-md-end">
          <button type="submit" class="btn btn-primary mt-3 mt-md-0">
            <i class="bi bi-floppy me-1"></i>Enregistrer la proposition
          </button>
        </div>
      </div>

      <?php if (empty($articles)): ?>
        <div class="alert alert-info">
          Aucun article trouvé dans la base de données. Commencez par ajouter des articles.
        </div>
      <?php else: ?>

        <div class="mb-2 small text-muted">
          <i class="bi bi-lightbulb me-1"></i>
          Pour chaque article :
          cochez la case pour le rendre disponible à la date choisie,
          et indiquez la quantité réservable (<code>qte</code> dans <code>PropositionArticleJour</code>).
        </div>

        <!-- Boutons de sélection -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
          <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="checkAll">
              <i class="bi bi-check-square me-1"></i>Tout cocher
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="uncheckAll">
              <i class="bi bi-square me-1"></i>Tout décocher
            </button>
          </div>
          <div class="small text-muted">
            <span id="selectedCount">0</span> article(s) sélectionné(s) pour cette date.
          </div>
        </div>

        <!-- Table des articles -->
        <div class="table-responsive">
          <table class="table align-middle table-hover">
            <thead class="table-light">
              <tr>
                <th style="width:40px;"></th>
                <th>Image</th>
                <th>Libellé</th>
                <th>Description</th>
                <th style="width:150px;">Quantité (qte)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($articles as $art): ?>
                <?php
                  $id          = $art['id_article'];
                  $libelle     = $art['libelleArt']   ?? '';
                  $description = $art['Description']  ?? ($art['Description'] ?? '');
                  $img         = $art['img']          ?? null;

                  // bool : déjà proposé pour cette date (jointure faite en amont par le contrôleur)
                  $propose = !empty($art['propose']);

                  // qte actuelle dans PropositionArticleJour (ou vide s'il n'y a pas encore de ligne)
                  $qte = $art['qte_max'] ?? '';

                  // URL de l'image (à adapter selon ton arborescence)
                  $imageUrl = !empty($img)
                    ? '/RestoCampus/public/uploads/articles/' . $img
                    : '/RestoCampus/public/assets/img/article-placeholder.jpg';
                ?>
                <tr>
                  <!-- Checkbox proposer -->
                  <td>
                    <input class="form-check-input article-check"
                           type="checkbox"
                           name="articles[<?= e($id) ?>][propose]"
                           value="1"
                           <?= $propose ? 'checked' : '' ?>>
                  </td>

                  <!-- Image -->
                  <td>
                    <img src="<?= e($imageUrl) ?>" alt="Image de <?= e($libelle) ?>" class="thumb-article">
                  </td>

                  <!-- Libellé + ID -->
                  <td>
                    <div class="fw-semibold"><?= e($libelle) ?></div>
                    <small class="text-muted">ID : <?= e($id) ?></small>
                  </td>

                  <!-- Description tronquée -->
                  <td>
                    <span class="desc-trunc" title="<?= e($description) ?>">
                      <?= e($description) ?>
                    </span>
                  </td>

                  <!-- qte pour PropositionArticleJour -->
                  <td>
                    <input type="number"
                           class="form-control form-control-sm"
                           name="articles[<?= e($id) ?>][qte]"
                           min="0"
                           placeholder="ex : 20"
                           value="<?= e($qte) ?>">
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php endif; ?>
    </form>
  </div>
</section>

<script>
  // Gestion tout cocher / décocher + compteur
  (function() {
    const checkAllBtn   = document.getElementById('checkAll');
    const uncheckAllBtn = document.getElementById('uncheckAll');
    const checks        = document.querySelectorAll('.article-check');
    const counterSpan   = document.getElementById('selectedCount');

    function updateCount() {
      let count = 0;
      checks.forEach(cb => { if (cb.checked) count++; });
      if (counterSpan) counterSpan.textContent = count;
    }

    if (checkAllBtn) {
      checkAllBtn.addEventListener('click', () => {
        checks.forEach(cb => cb.checked = true);
        updateCount();
      });
    }
    if (uncheckAllBtn) {
      uncheckAllBtn.addEventListener('click', () => {
        checks.forEach(cb => cb.checked = false);
        updateCount();
      });
    }
    checks.forEach(cb => cb.addEventListener('change', updateCount));

    // initialisation
    updateCount();
  })();
</script>
