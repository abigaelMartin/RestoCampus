<?php
// Vue : gestion_reservation_show.php
// Attendu côté contrôleur :
// $title = "Détail réservation";
// $reservation = [
//   'id' => 123,
//   'ref' => 'R-123',
//   'date' => '2025-11-05',
//   'heure_retrait' => '12:15',
//   'statut' => 'en_attente', // en_attente | confirmee | preparee | retiree | annulee
//   'prix_total' => 7.5,
//   'eleve_nom' => 'Dupont',
//   'eleve_prenom' => 'Lucas',
//   'classe' => 'TSTMG1',
//   'email' => 'lucas.dupont@lycee.fr',
// ];
// $lignes = [
//   ['plat'=>'Salade César','quantite'=>1,'prix'=>5.5],
//   ['plat'=>'Dessert yaourt','quantite'=>1,'prix'=>2.0],
// ];
// $csrf_token (optionnel)

$title = $title ?? 'Détail réservation';
include 'header.php';

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function euro($n){ return number_format((float)$n, 2, ',', ' ') . ' €'; }
function badgeStatut($s){
  return match($s){
    'confirmee' => '<span class="badge text-bg-success"><i class="bi bi-check2-circle me-1"></i>Confirmée</span>',
    'en_attente'=> '<span class="badge text-bg-warning"><i class="bi bi-hourglass-split me-1"></i>En attente</span>',
    'retiree'   => '<span class="badge text-bg-primary"><i class="bi bi-bag-check me-1"></i>Retirée</span>',
    'preparee'  => '<span class="badge text-bg-info text-dark"><i class="bi bi-clipboard-check me-1"></i>Préparée</span>',
    'annulee'   => '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulée</span>',
    default     => '<span class="badge text-bg-light text-dark">'.e($s).'</span>',
  };
}

$r = $reservation;
?>
<style>
  .page-head{
    background:linear-gradient(to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));
    border-bottom:1px solid #eef2f7;
  }
  .card-soft{
    border-radius:1rem;
    border:1px solid #e5e7eb;
  }
</style>
<?php include '../app/views/layout/header.php'?>
<header class="page-head py-4">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Réservation #<?= e($r['ref'] ?? ('R-'.$r['id'])) ?></h1>
      <p class="mb-0 text-muted">
        Date : <?= e($r['date'] ?? '—') ?> · Retrait : <?= e($r['heure_retrait'] ?? '—') ?>
      </p>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
      <span><?= badgeStatut($r['statut'] ?? '') ?></span>
      <a href="/public/?controleur=reservation&action=liste" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour à la liste
      </a>
    </div>
  </div>
</header>

<section class="py-4">
  <div class="container">
    <div class="row g-4">
      <!-- Colonne de gauche : infos élève + réservation -->
      <div class="col-lg-5">
        <!-- Infos élève -->
        <div class="card card-soft mb-3">
          <div class="card-header bg-white border-0 pb-0">
            <h2 class="h6 mb-0"><i class="bi bi-person me-1"></i> Élève</h2>
          </div>
          <div class="card-body">
            <p class="mb-1"><strong><?= e(($r['eleve_prenom'] ?? '').' '.($r['eleve_nom'] ?? '')) ?></strong></p>
            <p class="mb-1 text-muted">
              <i class="bi bi-mortarboard me-1"></i><?= e($r['classe'] ?? '—') ?>
            </p>
            <?php if (!empty($r['email'])): ?>
              <p class="mb-0 text-muted"><i class="bi bi-envelope me-1"></i><?= e($r['email']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Infos réservation -->
        <div class="card card-soft">
          <div class="card-header bg-white border-0 pb-0">
            <h2 class="h6 mb-0"><i class="bi bi-info-circle me-1"></i> Informations réservation</h2>
          </div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-5">Référence</dt>
              <dd class="col-7">#<?= e($r['ref'] ?? ('R-'.$r['id'])) ?></dd>

              <dt class="col-5">Date réservation</dt>
              <dd class="col-7"><?= e($r['date'] ?? '—') ?></dd>

              <dt class="col-5">Créneau</dt>
              <dd class="col-7"><?= e($r['heure_retrait'] ?? '—') ?></dd>

              <dt class="col-5">Statut</dt>
              <dd class="col-7"><?= badgeStatut($r['statut'] ?? '') ?></dd>

              <dt class="col-5">Montant total</dt>
              <dd class="col-7"><strong><?= euro($r['prix_total'] ?? 0) ?></strong></dd>
            </dl>
          </div>
        </div>
      </div>

      <!-- Colonne de droite : détails + actions -->
      <div class="col-lg-7">
        <!-- Détail des plats -->
        <div class="card card-soft mb-3">
          <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0"><i class="bi bi-bag me-1"></i> Détail des plats</h2>
          </div>
          <div class="card-body">
            <?php if (empty($lignes)): ?>
              <p class="text-muted mb-0">Aucune ligne trouvée pour cette réservation.</p>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Plat</th>
                      <th class="text-center">Qté</th>
                      <th>Prix unitaire</th>
                      <th>Total ligne</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($lignes as $ligne):
                      $plat = e($ligne['plat'] ?? '—');
                      $qte  = (int)($ligne['quantite'] ?? 1);
                      $pu   = (float)($ligne['prix'] ?? 0);
                      $pt   = $pu * $qte;
                    ?>
                      <tr>
                        <td><?= $plat ?></td>
                        <td class="text-center"><?= $qte ?></td>
                        <td><?= euro($pu) ?></td>
                        <td><?= euro($pt) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Actions sur la réservation -->
        <div class="card card-soft">
          <div class="card-header bg-white border-0 pb-0">
            <h2 class="h6 mb-0"><i class="bi bi-tools me-1"></i> Actions</h2>
          </div>
          <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
              <?php
                $id = e($r['id']);
                $canCancel   = in_array($r['statut'], ['en_attente','confirmee']);
                $canPrepare  = in_array($r['statut'], ['confirmee','en_attente']);
                $canRetire   = in_array($r['statut'], ['preparee','confirmee']);
              ?>

              <!-- Confirmer -->
              <form method="post" action="/public/?controleur=reservation&action=updateStatus">
                <?php if (!empty($csrf_token)): ?>
                  <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
                <?php endif; ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="status" value="confirmee">
                <button class="btn btn-outline-primary btn-sm" type="submit" <?= $r['statut']==='confirmee' ? 'disabled' : '' ?>>
                  <i class="bi bi-check2-circle me-1"></i>Confirmer
                </button>
              </form>

              <!-- Préparée -->
              <form method="post" action="/public/?controleur=reservation&action=updateStatus">
                <?php if (!empty($csrf_token)): ?>
                  <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
                <?php endif; ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="status" value="preparee">
                <button class="btn btn-outline-info btn-sm" type="submit" <?= !$canPrepare ? 'disabled' : '' ?>>
                  <i class="bi bi-clipboard-check me-1"></i>Marquer préparée
                </button>
              </form>

              <!-- Retirée -->
              <form method="post" action="/public/?controleur=reservation&action=updateStatus">
                <?php if (!empty($csrf_token)): ?>
                  <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
                <?php endif; ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="status" value="retiree">
                <button class="btn btn-outline-success btn-sm" type="submit" <?= !$canRetire ? 'disabled' : '' ?>>
                  <i class="bi bi-bag-check me-1"></i>Marquer retirée
                </button>
              </form>

              <!-- Annuler -->
              <form method="post" action="/public/?controleur=reservation&action=cancel" onsubmit="return confirm('Confirmer l\'annulation de cette réservation ?');">
                <?php if (!empty($csrf_token)): ?>
                  <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
                <?php endif; ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <button class="btn btn-outline-danger btn-sm" type="submit" <?= !$canCancel ? 'disabled' : '' ?>>
                  <i class="bi bi-x-circle me-1"></i>Annuler
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
