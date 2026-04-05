<?php
// Vue : gestion_reservations.php (Interface gestionnaire)
// Attendu côté contrôleur :
// - $title (string)
// - $reservations (array d'assoc)
//    Chaque réservation : [
//      'id'=>int, 'ref'=>string(optionnel), 'date'=>'YYYY-MM-DD', 'heure_retrait'=>'HH:MM',
//      'plat'=>string, 'quantite'=>int, 'prix'=>float, 'statut'=>'en_attente|confirmee|retiree|annulee|preparee',
//      'eleve_nom'=>string, 'eleve_prenom'=>string, 'classe'=>string(optionnel), 'email'=>string(optionnel)
//    ]
// - $csrf_token (string, optionnel)
// - $page (int, optionnel) et $total_pages (int, optionnel)
// - $stats (array, optionnel) ex: ['total'=>..,'aujourd_hui'=>..,'en_attente'=>..,'confirmees'=>..,'preparees'=>..,'retirees'=>..]

$title = $title ?? 'Gestion des réservations';


function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function euro($n){ return number_format((float)$n, 2, ',', ' ') . ' €'; }
function badge($s){
  switch($s){
    case 'confirmee': return '<span class="badge text-bg-success"><i class="bi bi-check2-circle me-1"></i>Confirmée</span>';
    case 'en_attente': return '<span class="badge text-bg-warning"><i class="bi bi-hourglass-split me-1"></i>En attente</span>';
    case 'retiree': return '<span class="badge text-bg-primary"><i class="bi bi-bag-check me-1"></i>Retirée</span>';
    case 'preparee': return '<span class="badge text-bg-info text-dark"><i class="bi bi-clipboard-check me-1"></i>Préparée</span>';
    case 'annulee': return '<span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulée</span>';
    default: return '<span class="badge text-bg-light text-dark">'.e($s).'</span>';
  }
}
?>

<style>
  .page-head{background:linear-gradient(to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));}
  .kpi-card{border:1px solid #eef2f7;border-radius:1rem}
  .table td, .table th{vertical-align: middle}
  .sticky-actions{position:sticky; right:0; background:#fff}
  .chip{border:1px solid #e9ecef;border-radius:999px;padding:.35rem .75rem;font-size:.875rem}
  .chip.active{background:linear-gradient(90deg,#0ea5e9,#22c55e);border-color:transparent;color:#fff}
</style>
<?php include '../app/views/layout/header.php'?>
<header class="page-head py-4 border-bottom">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Gestion des réservations</h1>
      <p class="mb-0 text-muted">Vue gestionnaire — rechercher, filtrer, confirmer, marquer préparée/retirée, ou annuler.</p>
    </div>
    <div class="d-flex gap-2">
      <!-- <a href="/public/?controleur=Reservation&action=exportCsv" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>Exporter CSV</a> -->
      <a href="?controleur=Proposition&action=proposer" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Créer une réservation</a>
    </div>
  </div>
</header>

<div class="my-3">
  <a href="<?= $_SERVER['HTTP_REFERER'] ?? '?controleur=gestion&action=panel' ?>" class="btn btn-outline-primary align-items-center" style="display: inline-flex; margin-left: 70px;">
    <i class="bi bi-arrow-left-circle me-2 fs-5"></i>
    Retour 
  </a>
</div>

<section class="py-4">
  <div class="container">
    <!-- KPIs -->
    <?php $stats = $stats ?? [];?>
    <div class="row g-3 mb-3">
      <div class="col-6 col-md-2"><div class="p-3 kpi-card"><div class="small text-muted">Total</div><div class="h4 mb-0"><?= (int)($stats['total'] ?? count($reservations ?? [])) ?></div></div></div>
      <!-- <div class="col-6 col-md-2"><div class="p-3 kpi-card"><div class="small text-muted">Aujourd'hui</div><div class="h4 mb-0"><?= (int)($stats['aujourd_hui'] ?? 0) ?></div></div></div>
      <div class="col-6 col-md-2"><div class="p-3 kpi-card"><div class="small text-muted">Confirmées</div><div class="h4 mb-0"><?= (int)($stats['confirmees'] ?? 0) ?></div></div></div>
      <div class="col-6 col-md-2"><div class="p-3 kpi-card"><div class="small text-muted">Préparées</div><div class="h4 mb-0"><?= (int)($stats['preparees'] ?? 0) ?></div></div></div>
      <div class="col-6 col-md-2"><div class="p-3 kpi-card"><div class="small text-muted">Retirées</div><div class="h4 mb-0"><?= (int)($stats['retirees'] ?? 0) ?></div></div></div> -->
    </div>

    <!-- Filtres -->
    <form class="row gy-2 gx-3 align-items-end mb-3" method="get" action="gestion_reservations.php">
      <div class="col-sm-6 col-md-3">
        <label class="form-label">Recherche</label>
       <input type="search"
                id="searchCommandes"
                class="form-control"
                placeholder="Rechercher par nom, prénom ou login…">
      </div>
      <!-- <div class="col-sm-6 col-md-3">
        <label class="form-label">Date (du)</label>
        <input type="date" class="form-control" name="du" value="<?= e($_GET['du'] ?? '') ?>">
      </div>
      <div class="col-sm-6 col-md-3">
        <label class="form-label">Date (au)</label>
        <input type="date" class="form-control" name="au" value="<?= e($_GET['au'] ?? '') ?>">
      </div>
      <div class="col-sm-6 col-md-3">
        <label class="form-label">Statut</label>
        <?php $s=$_GET['statut'] ?? '';?>
        <select class="form-select" name="statut">
          <option value="">Tous</option>
          <option value="en_attente"   <?= $s==='en_attente'?'selected':'' ?>>En attente</option>
          <option value="confirmee"    <?= $s==='confirmee'?'selected':'' ?>>Confirmée</option>
          <option value="preparee"     <?= $s==='preparee'?'selected':'' ?>>Préparée</option>
          <option value="retiree"      <?= $s==='retiree'?'selected':'' ?>>Retirée</option>
          <option value="annulee"      <?= $s==='annulee'?'selected':'' ?>>Annulée</option>
        </select>
      </div>
      <div class="col-12 d-flex gap-2">
        <button class="btn btn-outline-secondary" type="reset" onclick="window.location='gestion_reservations.php'">
          <i class="bi bi-x-circle me-1"></i>Réinitialiser
        </button>
        <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i>Filtrer</button>
      </div> -->
    </form>

    <!-- Actions groupées -->
    <form method="post" action="/public/?controleur=Reservation&action=bulkUpdate" id="bulkForm" class="mb-3">
      <?php if (!empty($csrf_token)): ?>
        <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
      <?php endif; ?>
      <!-- <div class="d-flex flex-wrap gap-2 align-items-center">
        <div class="btn-group" role="group" aria-label="Sélection">
          <button class="btn btn-outline-secondary" type="button" id="checkAll"><i class="bi bi-square me-1"></i>Tout</button>
          <button class="btn btn-outline-secondary" type="button" id="uncheckAll"><i class="bi bi-square-fill me-1"></i>Rien</button>
        </div>
        <select class="form-select w-auto" name="action" required>
          <option value="" selected>Action groupée…</option>
          <option value="confirmer">Marquer confirmée</option>
          <option value="preparer">Marquer préparée</option>
          <option value="retirer">Marquer retirée</option>
          <option value="annuler">Annuler</option>
        </select>
        <button class="btn btn-primary" type="submit"><i class="bi bi-clipboard-check me-1"></i>Appliquer</button>
      </div> -->

      <div class="table-responsive mt-3">
        <table class="table align-middle" id="CommandeTable">
          <thead class="table-light">
            <tr>
              <th style="width:36px"><input class="form-check-input" type="checkbox" id="checkMaster"></th>
              <th>Réf.</th>
              <th>Nom Élève</th>
              <th>Plat</th>
              <th class="text-center">Qté</th>
              <th>Retrait</th>
              <th>Statut</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($reservations)): ?>
            <tr><td colspan="10" class="text-center text-muted py-5">Aucune réservation pour les critères sélectionnés.</td></tr>
          <?php else: ?>
            <?php foreach ($reservations as $r):
              $id    = $r['id_commande'] ?? null;
              $ref   = e($r['ref'] ?? ('R-'.$id));
              $nom   = e(($r['login'] ?? '').' '.($r['login'] ?? ''));
              $plat  = e($r['libelleArt'] ?? '—');
              $qte   = (int)($r['qte'] ?? 1);
              $date  = e($r['date'] ?? '');
              $heure = e($r['date_du_jour'] ?? '');
              $stat  = $r['statutReserv'] ?? '';
            ?>
            <tr>
              <td><input class="form-check-input" type="checkbox" name="ids[]" value="<?= e($id) ?>"></td>
              <td><span class="fw-semibold"><?= $ref ?></span></td>
              <td>
                <div class="fw-semibold"><?= $nom ?></div>
                <?php if (!empty($r['email'])): ?><small class="text-muted"><i class="bi bi-envelope me-1"></i><?= e($r['email']) ?></small><?php endif; ?>
              </td>
              <td><?= $plat ?></td>
              <td class="text-center"><?= $qte ?></td>
              <td><i class="bi bi-calendar-date me-1"></i><?= $date ?> · <i class="bi bi-clock ms-1 me-1"></i><?= $heure ?></td>
             
              <td><?= badge($stat) ?></td>
              <td class="text-end">
                <div class="btn-group" role="group">
                  <a class="btn btn-sm btn-outline-secondary" href="?controleur=GestionReservation&action=detail&id=<?= $id ?>"><i class="bi bi-eye"></i></a>

                  <form method="post" action="/public/?controleur=reservation&action=updateStatus" class="d-inline">
                    <?php if (!empty($csrf_token)): ?><input type="hidden" name="csrf" value="<?= e($csrf_token) ?>"><?php endif; ?>
                    <input type="hidden" name="id" value="<?= e($id) ?>">
                    <input type="hidden" name="status" value="preparee">
                    <button class="btn btn-sm btn-outline-info" type="submit" title="Marquer préparée"><i class="bi bi-clipboard-check"></i></button>
                  </form>

                  <form method="post" action="/public/?controleur=Reservation&action=updateStatus" class="d-inline">
                    <?php if (!empty($csrf_token)): ?><input type="hidden" name="csrf" value="<?= e($csrf_token) ?>"><?php endif; ?>
                    <input type="hidden" name="id" value="<?= e($id) ?>">
                    <input type="hidden" name="status" value="retiree">
                    <button class="btn btn-sm btn-outline-success" type="submit" title="Marquer retirée"><i class="bi bi-bag-check"></i></button>
                  </form>

                  <form method="post" action="/public/?controleur=Reservation&action=cancel" class="d-inline" onsubmit="return confirm('Confirmer l\'annulation ?');">
                    <?php if (!empty($csrf_token)): ?><input type="hidden" name="csrf" value="<?= e($csrf_token) ?>"><?php endif; ?>
                    <input type="hidden" name="id" value="<?= e($id) ?>">
                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Annuler"><i class="bi bi-x-circle"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </form>

    <!-- Pagination -->
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
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("searchCommandes");
    const table = document.getElementById("CommandeTable");
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
<script>
  // Sélection master
  const master = document.getElementById('checkMaster');
  const checkAllBtn = document.getElementById('checkAll');
  const uncheckAllBtn = document.getElementById('uncheckAll');

  function setAll(checked){
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = checked);
  }
  if(master){ master.addEventListener('change', ()=> setAll(master.checked)); }
  if(checkAllBtn){ checkAllBtn.addEventListener('click', ()=> setAll(true)); }
  if(uncheckAllBtn){ uncheckAllBtn.addEventListener('click', ()=> setAll(false)); }
</script>
