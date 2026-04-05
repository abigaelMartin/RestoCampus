<?php
$title = $title ?? 'Détail de la réservation';

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function euro($n){ return number_format((float)$n, 2, ',', ' ') . ' €'; }
function badgeStatut($s){
  switch($s){
    case 'confirmee': return '<span class="badge text-bg-success"><i class="bi bi-check2-circle me-1"></i>Confirmée</span>';
    case 'en_attente': return '<span class="badge text-bg-warning"><i class="bi bi-hourglass-split me-1"></i>En attente</span>';
    case 'annulee': return '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulée</span>';
    case 'retiree': return '<span class="badge text-bg-primary"><i class="bi bi-bag-check me-1"></i>Retirée</span>';
    case 'Annulée': return '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulée</span>';
    default: return '<span class="badge text-bg-light text-dark">'.e($s).'</span>';
  }
}
?>

<style>
  .page-head{background:linear-gradient( to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));}
  .detail-card{border:1px solid #eef2f7;border-radius:1rem; padding:1.5rem; background:#fff;}
  .info-row{display:flex; justify-content:space-between; align-items:center; padding:.5rem 0; border-bottom:1px solid #f1f5f9;}
  .info-row:last-child{border-bottom:none;}
  .info-label{font-weight:600; color:#64748b;}
  .info-value{color:#0f172a;}
</style>

<?php include '../app/views/layout/header.php'?>
<header class="page-head py-4 border-bottom">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Détail de la réservation</h1>
      <p class="mb-0 text-muted">Informations complètes sur votre réservation.</p>
    </div>
    <a href="?controleur=Reservation&action=mesreservations" class="btn btn-outline-primary">
      <i class="bi bi-arrow-left me-1"></i>Retour à mes réservations
    </a>
  </div>
</header>

<section class="py-4">
  <div class="container">
    <?php if (empty($reservation)): ?>
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Réservation non trouvée.
      </div>
    <?php else: ?>
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="detail-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h2 class="h5 fw-bold mb-0">Réservation #<?= e($reservation['id_commande'] ?? '') ?></h2>
              <?= badgeStatut($reservation['statut'] ?? '') ?>
            </div>

            <div class="info-row">
              <span class="info-label">Date de commande :</span>
              <span class="info-value"><?= e($reservation['date_de_commande'] ?? '') ?></span>
            </div>

            <div class="info-row">
              <span class="info-label">Plat réservé :</span>
              <span class="info-value fw-semibold"><?= e($reservation['libelleArt'] ?? '') ?></span>
            </div>

            <div class="info-row">
              <span class="info-label">Description :</span>
              <span class="info-value"><?= e($reservation['Description'] ?? '') ?></span>
            </div>

            <div class="info-row">
              <span class="info-label">Quantité :</span>
              <span class="info-value"><?= e($reservation['quantite'] ?? 1) ?></span>
            </div>

            <div class="info-row">
              <span class="info-label">Heure de retrait :</span>
              <span class="info-value"><?= e($reservation['heure_deb'] ?? '') ?> - <?= e($reservation['heure_fin'] ?? '') ?></span>
            </div>

            <div class="info-row">
              <span class="info-label">Date du jour :</span>
              <span class="info-value"><?= e($reservation['date_du_jour'] ?? '') ?></span>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>


