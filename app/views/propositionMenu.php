<?php
// Sécurité : accès gestionnaire ou admin
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['statut'] ?? '', ['gestionnaire', 'Admin'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

$title = $title ?? 'Proposer les articles du jour';

// Helper
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

// Valeurs par défaut
$dateJour   = $dateJour   ?? date('Y-m-d');
$heureDebut = $heureDebut ?? '08:00';
$heureFin   = $heureFin   ?? '11:30';

// $articles attendu depuis le contrôleur :
// $articles = [
//   ['id_article'=>1,'libelleArt'=>'Salade César','description'=>'...','img'=>'salade.jpg'],
//   ...
// ];
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

<header class="page-head py-4 mb-3">
  <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Proposer des articles pour un créneau</h1>
      <p class="mb-0 text-muted">
        Choisissez la date et le créneau horaire, puis cochez les articles à rendre disponibles.
      </p>
    </div>
    <div class="text-end">
      <span class="badge-soft">
        <a href="/RestoCampus/public?controleur=proposition&action=liste" class="btn btn-primary mt-3 mt-md-0"> 
          <i class="bi bi-eye me-1"></i>Voir propositions en cours</a>
      </span>
    </div>
  </div>
</header>

<section class="py-3">
  <div class="container">

    <form method="post" action="/RestoCampus/public/?controleur=proposition&action=addproposition">
      <?php if (!empty($_SESSION['csrf'])): ?>
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
      <?php endif; ?>

      <!-- Date + créneau -->
      <div class="row g-3 mb-4 align-items-end">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Date</label>
          <input type="date"
                 class="form-control"
                 name="date_du_jour"
                 value="<?= e($dateJour) ?>"
                 required>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Heure début</label>
          <input type="time"
                 class="form-control"
                 name="heure_deb"
                 value="<?= e($heureDebut) ?>"
                 required>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Heure fin</label>
          <input type="time"
                 class="form-control"
                 name="heure_fin"
                 value="<?= e($heureFin) ?>"
                 required>
        </div>
        <div class="col-md-2 text-md-end">
          <button type="submit" class="btn btn-primary mt-3 mt-md-0">
            <i class="bi bi-floppy me-1"></i>Enregistrer
          </button>
        </div>
      </div>

      <?php if (empty($articles)): ?>
        <div class="alert alert-info">
          Aucun article trouvé. Ajoutez d’abord des articles dans la base.
        </div>
      <?php else: ?>

        <div class="mb-2 small text-muted">
          <i class="bi bi-lightbulb me-1"></i>
          Cochez les articles à proposer pour ce créneau et indiquez la quantité max réservable (<code>qte</code>).
        </div>

        <!-- Boutons tout cocher/décocher -->
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
            <span id="selectedCount">0</span> article(s) sélectionné(s) pour ce créneau.
          </div>
        </div>

        <!-- Liste des articles -->
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
                  $description = $art['description']  ?? ($art['Description'] ?? '');
                  $img         = $art['img']          ?? null;

                  $imageUrl = !empty($img)
                    ? '/RestoCampus/public/uploads/articles/' . $img
                    : '/RestoCampus/public/assets/img/article-placeholder.jpg';
                ?>
                <tr>
                  <!-- Checkbox -->
                  <td>
                    <input class="form-check-input article-check"
                           type="checkbox"
                           name="articles[<?= e($id) ?>][propose]"
                           value="1">
                  </td>
                  
                  

                  <!-- Image -->
                  <td>
                    <img src="<?= e($imageUrl) ?>" alt="Image de <?= e($libelle) ?>" class="thumb-article">
                  </td>

                  <!-- Libellé -->
                  <td>
                    <div class="fw-semibold"><?= e($libelle) ?></div>
                    <small class="text-muted">ID : <?= e($id) ?></small>
                  </td>

                  <!-- Description -->
                  <td>
                    <span class="desc-trunc" title="<?= e($description) ?>">
                      <?= e($description) ?>
                    </span>
                  </td>

                  <!-- Quantité : ATTENTION, liée à chaque article -->
                  <td>
                    <input type="number"
                           class="form-control form-control-sm"
                           name="articles[<?= e($id) ?>][qte]"
                           min="0"
                           value="10"
                           placeholder="ex : 20">
                           
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
  // Gestion Tout cocher/décocher + compteur
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

    updateCount();
  })();
</script>
