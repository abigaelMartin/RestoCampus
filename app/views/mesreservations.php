<?php


$title = $title ?? 'Mes réservations';


function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function euro($n){ return number_format((float)$n, 2, ',', ' ') . ' €'; }
function badgeStatut($s){
  switch($s){
    case 'confirmee': return '<span class="badge text-bg-success"><i class="bi bi-check2-circle me-1"></i>Confirmée</span>';
    case 'en_attente': return '<span class="badge text-bg-warning"><i class="bi bi-hourglass-split me-1"></i>En attente</span>';
    case 'annulee': return '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulée</span>';
    case 'retiree': return '<span class="badge text-bg-primary"><i class="bi bi-bag-check me-1"></i>Retirée</span>';
    default: return '<span class="badge text-bg-light text-dark">'.e($s).'</span>';
  }
}
?>

<style>
  .page-head{background:linear-gradient( to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));}
  .kpi-card{border:1px solid #eef2f7;border-radius:1rem}
  .table td, .table th{vertical-align: middle}
  .empty{
    border:1px dashed rgba(2,6,23,.15); border-radius:1rem; padding:2rem; background:#fff
  }
  
</style>


<?php include '../app/views/layout/header.php'?>
<header class="page-head py-4 border-bottom">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Mes réservations</h1>
      <p class="mb-0 text-muted">Historique et réservations actives. Vous pouvez annuler avant l'heure limite fixée par le lycée.</p>
    </div>
    <a href="?controleur=Reservation&action=liste" class="btn btn-primary"><i class="bi bi-bag-plus me-1"></i>Nouvelle réservation</a>
  </div>
</header>

<section class="py-4">
  <div class="container">
    <!-- Filtres / recherche -->
    <form class="row gy-2 gx-3 align-items-end mb-3" method="get" action="mesreservations.php">
      <div class="col-sm-6 col-md-3">
        <label class="form-label">Recherche</label>
        <input type="search" class="form-control" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Plat, référence…">
      </div>
      <div class="col-sm-6 col-md-3">
        <label class="form-label">Date</label>
        <input type="date" class="form-control" name="date" value="<?= e($_GET['date'] ?? '') ?>">
      </div>
      <div class="col-sm-6 col-md-3">
        <label class="form-label">Statut</label>
        <select class="form-select" name="statut">
          <?php $s = $_GET['statut'] ?? ''; ?>
          <option value="">Tous</option>
          <option value="Confirmée"   <?= $s==='Confirmée'?'selected':'' ?>>Confirmée</option>
          <option value="retiree"      <?= $s==='retiree'?'selected':'' ?>>Retirée</option>
          <option value="annulee"      <?= $s==='annulee'?'selected':'' ?>>Annulée</option>
        </select>
      </div>
      <div class="col-sm-6 col-md-3 d-grid d-md-flex gap-2">
        <button class="btn btn-outline-secondary" type="reset" onclick="window.location='mes_reservations.php'">
          <i class="bi bi-x-circle me-1"></i>Réinitialiser
        </button>
        <button class="btn btn-primary" type="submit">
          <i class="bi bi-search me-1"></i>Filtrer
        </button>
      </div>
    </form>

    <!-- KPIs rapides -->
    <div class="row g-3 mb-3">
      <?php
        $total = count($reservations ?? []);
        $actives = array_sum(array_map(fn($r)=> in_array($r['statut']??'', ['en_attente','confirmee']) ? 1:0, $reservations ?? []));
      ?>
      <div class="col-6 col-md-3"><div class="p-3 kpi-card"><div class="small text-muted">Total</div><div class="h4 mb-0"><?= $total ?></div></div></div>
      <div class="col-6 col-md-3"><div class="p-3 kpi-card"><div class="small text-muted">Actives</div><div class="h4 mb-0"><?= $actives ?></div></div></div>
      <div class="col-6 col-md-3"><div class="p-3 kpi-card"><div class="small text-muted">Dernière</div><div class="h4 mb-0"><?= e(($reservations[0]['date'] ?? '') ?: '—') ?></div></div></div>
    </div>

    <?php if (empty($reservations)): ?>
      <div class="empty text-center">
        <div class="display-6 mb-2">🍽️</div>
        <h2 class="h5">Aucune réservation trouvée</h2>
        <p class="text-muted mb-3">Tu n'as pas encore réservé de plat, ou tes filtres sont trop restrictifs.</p>
        <a href="reservation.php" class="btn btn-primary"><i class="bi bi-bag-plus me-1"></i>Faire une réservation</a>
      </div>
    <?php else: ?>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Réf.</th>
              <th>Date</th>
              <th>Plat</th>
              <th class="text-center">Qté</th>
              <th>Retrait</th>
              <th>Statut</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r):
              $ref   = '#R-' . e($r['id_commande'] ?? '?');
              $plat  = e($r['libelleArt'] ?? '—');
              $qte   = (int)($r['quantite'] ?? 1);  
              $date  = e($r['date_de_commande'] ?? '');
              $heure = e($r['heure_retrait'] ?? '');
              $stat  = $r['statut'] ?? '';
              $cancelAllowed = in_array($stat, ['en_attente','confirmee']);
            ?>
              <tr>
                <td><?= $ref ?></td>
                <td>
                  <?= $date ?>
                </td>
                <td>
                  <div class="fw-semibold"><?= $plat ?></div>
                </td>
                
                <td class="text-center"><?= $qte ?></td>
                <td><i class="bi bi-clock me-1"></i><?= $heure ?></td>
              
                <td><?= badgeStatut($stat) ?></td>
                
                <td class="text-end ">
                  <div class="btn-group" role="group">
                    <a class="btn btn-sm btn-outline-secondary" href="reservation_details.php?id=<?= e($r['id_commande']) ?>">
                      <i class="bi bi-eye me-1"></i>Voir
                    </a>
                    <form method="post" action="?controleur=Reservation&action=annuler" onsubmit="return confirm('Confirmer l\'annulation de cette réservation ?');">
                      <input type="hidden" name="id_cmd" value="<?= e($r['id_commande']) ?>">
                      <input type="hidden" name="id_Art" value="<?= e($r['id_ArtJour']) ?>">
                      
                      <?php if($r['statut'] !='Annulée'){?>
                        <button class="btn btn-sm btn-outline-danger" type="submit" > <?/*= $cancelAllowed? '' : 'disabled' */?> 
                          <i class="bi bi-x-circle me-1"></i>Annuler
                        </button>

                      <?php }?>

                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination simple -->
      <?php if (!empty($total_pages) && ($total_pages > 1)): $page = max(1, (int)($page ?? 1)); ?>
        <nav aria-label="Pagination">
          <ul class="pagination justify-content-center">
            <li class="page-item <?= $page<=1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?page=<?= $page-1 ?>" tabindex="-1" aria-disabled="true">Précédent</a>
            </li>
            <?php for($i=1; $i<=$total_pages; $i++): ?>
              <li class="page-item <?= $i===$page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <li class="page-item <?= $page>=$total_pages ? 'disabled' : '' ?>">
              <a class="page-link" href="?page=<?= $page+1 ?>">Suivant</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>

    <?php endif; ?>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



