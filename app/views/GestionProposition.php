<?php
// Sécurité : accès gestionnaire ou admin
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['statut'] ?? '', ['gestionnaire', 'Admin'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

$title = $title ?? 'Propositions du jour';

// Helper
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

// Date sélectionnée (passée par le contrôleur ou GET)
$dateJour = $dateJour ?? ($_GET['date_jour'] ?? date('Y-m-d'));

// Date / heure actuelles (pour statut des créneaux)
$today   = date('Y-m-d');
$nowTime = date('H:i:s');


include '../app/views/layout/header.php';
?>

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
  .thumb-article{
    width:52px; height:52px; border-radius:.65rem;
    object-fit:cover;
    border:1px solid rgba(15,23,42,.06);
    background:#f1f5f9;
  }
  .desc-trunc{
    max-width: 260px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>

<header class="page-head py-4 mb-3">
  <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Propositions de menus</h1>
      <p class="mb-0 text-muted">
        Visualisez les plats proposés pour une date et un créneau, avec leur statut en temps réel.
      </p>
    </div>
    <div class="text-end">
      <span class="badge-soft">
        <i class="bi bi-calendar-event me-1"></i>
        Date : <?= e(date('d/m/Y', strtotime($dateJour))) ?>
      </span>
      <?php if (!empty($propositions)): ?>
        <div class="mt-1 small text-muted">
          <i class="bi bi-info-circle me-1"></i>
          <?= count($propositions) ?> proposition(s) trouvée(s).
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

<section class="py-3">
  <div class="container">

    <!-- Filtre par date -->
    <form method="get" action="/RestoCampus/public/index.php" class="row gy-2 gx-3 align-items-end mb-4">
      <input type="hidden" name="controleur" value="proposition">
      <input type="hidden" name="action" value="listeJour">
      <div class="col-sm-6 col-md-4">
        <label class="form-label fw-semibold">Date</label>
        <input type="date"
               name="date_jour"
               class="form-control"
               value="<?= e($dateJour) ?>">
      </div>
      <div class="col-sm-6 col-md-3">
        <button type="submit" class="btn btn-outline-secondary w-100">
          <i class="bi bi-search me-1"></i>Afficher
        </button>
      </div>
      <div class="col-md-5 text-md-end">
        <a href="/RestoCampus/public/?controleur=proposition&action=proposer"
           class="btn btn-primary">
          <i class="bi bi-plus-circle me-1"></i>Créer une nouvelle proposition
        </a>
      </div>
    </form>

    <?php if (empty($propositions)): ?>

      <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <div>
          Aucune proposition trouvée pour cette date.
          <br>
          Utilisez le bouton <strong>“Créer une nouvelle proposition”</strong> pour ajouter des menus.
        </div>
      </div>

    <?php else: ?>

      <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead class="table-light">
            <tr>
              <th>Plat</th>
              <th>Créneau</th>
              <th>Quantité max</th>
              <th>Statut</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($propositions as $p): ?>
            <?php
              $libelle     = $p['libelleArt']   ?? '';
              $description = $p['description']  ?? ($p['Description'] ?? '');
              $img         = $p['img']          ?? null;
              $qteMax      = (int)($p['qte_max'] ?? 0);
              $heureDeb    = substr($p['heure_deb'], 0, 5);
              $heureFin    = substr($p['heure_fin'], 0, 5);
              $dateProp    = $p['date_du_jour'] ?? $dateJour;

              $imageUrl = !empty($img)
                ? '/RestoCampus/public/uploads/articles/' . $img
                : '/RestoCampus/public/assets/img/article-placeholder.jpg';

              // Déterminer le statut du créneau
              $statutLabel = 'À venir';
              $statutClass = 'badge text-bg-info';
              if ($dateProp < $today || ($dateProp === $today && $p['heure_fin'] < $nowTime)) {
                  $statutLabel = 'Terminé';
                  $statutClass = 'badge text-bg-secondary';
              } elseif ($dateProp === $today && $p['heure_deb'] <= $nowTime && $p['heure_fin'] >= $nowTime) {
                  $statutLabel = 'En cours';
                  $statutClass = 'badge text-bg-success';
              }

              $idArtJour  = (int)($p['id_artJour']  ?? 0);
              $idArticle  = (int)($p['id_article']  ?? 0);
            ?>
            <tr>
              <!-- Plat -->
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="<?= e($imageUrl) ?>" alt="Image de <?= e($libelle) ?>" class="thumb-article">
                  <div>
                    <div class="fw-semibold"><?= e($libelle) ?></div>
                    <?php if (!empty($description)): ?>
                      <div class="desc-trunc text-muted small" title="<?= e($description) ?>">
                        <?= e($description) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </td>

              <!-- Créneau -->
              <td>
                <span class="fw-semibold"><?= e($heureDeb) ?> – <?= e($heureFin) ?></span><br>
                <small class="text-muted">Le <?= e(date('d/m/Y', strtotime($dateProp))) ?></small>
              </td>

              <!-- Quantité max -->
              <td>
                <span class="fw-semibold"><?= $qteMax ?></span>
                <small class="text-muted d-block">portion(s)</small>
              </td>

              <!-- Statut -->
              <td>
                <span class="<?= $statutClass ?>">
                  <?= e($statutLabel) ?>
                </span>
              </td>

              <!-- Actions -->
              <td class="text-end">
                <div class="btn-group" role="group">
                  <!-- Modifier -->
                  <a href="/RestoCampus/public/?controleur=proposition&action=editer&id=<?= $idArtJour ?>"
                     class="btn btn-sm btn-outline-warning"
                     title="Modifier cette proposition">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <!-- Supprimer -->
                  <form method="post"
                        action="/RestoCampus/public/?controleur=proposition&action=supprimer"
                        class="d-inline"
                        onsubmit="return confirm('Supprimer cette proposition pour ce créneau ?');">
                    <?php if (!empty($_SESSION['csrf'])): ?>
                      <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                    <?php endif; ?>
                    <input type="hidden" name="id_artJour" value="<?= $idArtJour ?>">
                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Supprimer">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
