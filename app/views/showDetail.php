<?php
// Sécurité : accès gestionnaire ou admin
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['statut'] ?? '', ['gestionnaire','Admin'])) {
    header("Location: /RestoCampus/public/?controleur=auth&action=login");
    exit;
}

$title = $title ?? 'Détail de la réservation';

// Helper
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}


// Sécurisation minimum
$code        = $resa['id_commande']          ?? ('#'.$resa['id_reservation'] ?? '');
$dateResa    = $resa['date_du_jour']     ?? null;

$heureRetrait= $resa['heure_deb'] ?? null;
$statut      = $resa['statutCmd']        ?? 'en_attente';
$statutUser = $resa['statut'] ?? '';
$commentaire = $resa['commentaire']   ?? '';

$nomComplet  = trim(($resa['prenom'] ?? '').' '.($resa['nom'] ?? ''));
$login       = $resa['login']  ?? '';
$classe      = $resa['classe'] ?? 'BTS SIO 2';

// Badge de statut
$statutLabel = 'En attente';
$statutClass = 'badge text-bg-warning';
switch ($statut) {
    case 'Confirmée':
        $statutLabel = 'Confirmée';
        $statutClass = 'badge text-bg-primary';
        break;
    case 'preparee':
        $statutLabel = 'Préparée';
        $statutClass = 'badge text-bg-info';
        break;
    case 'retiree':
        $statutLabel = 'Retirée';
        $statutClass = 'badge text-bg-success';
        break;
    case 'Annulée':
        $statutLabel = 'Annulée';
        $statutClass = 'badge text-bg-danger';
        break;
}

include '../app/views/layout/header.php';
?>

<style>
  .page-head{
    background:linear-gradient(to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));
    border-bottom:1px solid #eef2f7;
  }
  .kpi-chip{
    border-radius:.75rem;
    border:1px solid rgba(15,23,42,.06);
    padding:.6rem .9rem;
    background:#fff;
  }
</style>

<header class="page-head py-4 mb-4">
  
    <?php
      // Sécurisation/normalisation
      $id         = isset($res['id_reservation']) ? (int)$res['id_reservation'] : 0;
      $libelle    = isset($res['libelleArt'])      ? trim($res['libelleArt']) : 'Plat';
      $qte        = isset($res['qte'])             ? (int)$res['qte'] : 0;
      $prixUnit   = isset($res['prix_unit'])      ? (float)$res['prix_unit'] : 0.0;
    ?>
 
  <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <a href="/RestoCampus/public/?controleur=GestionReservation&action=liste" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
      </a>
      <h1 class="h4 fw-bold mb-1">
        Réservation <?= e($code) ?>
      </h1>
      <p class="mb-0 text-muted">
        Gérée par le self du lycée – détail complet de la commande de l'étudiant.
      </p>
    </div>
    <div class="text-end">
      <span class="<?= $statutClass ?> mb-1 d-inline-block">
        <i class="bi bi-circle-fill me-1" style="font-size:.55rem;"></i> <?= e($statut) ?>
      </span>
      <div class="small text-muted">
        Créée le
        <?= $dateResa ? e(date('d/m/Y H:i', strtotime($dateResa))) : '—' ?>
      </div>
    </div>
  </div>
</header>

<section class="pb-5">
  <div class="container">
    <div class="row g-4">

      <!-- Colonne gauche : infos élève + retrait -->
      <div class="col-lg-5">
        <div class="card mb-3">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-person-badge me-1"></i> <?=  $statutUser ?></span>
          </div>
          <div class="card-body">
            <p class="mb-1 fw-semibold"><?= e($nomComplet ?: $login) ?></p>
            <?php if ($statutUser=="Etudiant"){ ?>
              <p class="mb-1 text-muted"><i class="bi bi-mortarboard me-1"></i><?= e($classe) ?></p>
            <?php }; ?>
            <?php if ($login): ?>
              <p class="mb-0 text-muted"><i class="bi bi-at me-1"></i><?= e($login) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-clock-history me-1"></i> Retrait</span>
          </div>
          <div class="card-body">
            <div class="kpi-chip mb-2 d-flex justify-content-between align-items-center">
              <span class="text-muted small">Date</span>
              <span class="fw-semibold">
                <?= $dateResa ? e(date('d/m/Y', strtotime($dateResa))) : '—' ?>
              </span>
            </div>
            <div class="kpi-chip d-flex justify-content-between align-items-center">
              <span class="text-muted small">Créneau</span>
              <span class="fw-semibold">
                <?= $heureRetrait ? e(substr($heureRetrait,0,5)) : '—' ?>
              </span>
            </div>
          </div>
        </div>

        <?php if ($commentaire): ?>
          <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <span class="fw-semibold"><i class="bi bi-chat-left-text me-1"></i> Commentaire</span>
            </div>
            <div class="card-body">
              <p class="mb-0"><?= nl2br(e($commentaire)) ?></p>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Colonne droite : détail des plats -->
      <div class="col-lg-7">
        <div class="card mb-3">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-bag-check me-1"></i> Détail de la commande</span>
          </div>
          <div class="card-body p-0">
            <?php if (empty($resa)): ?>
              <div class="p-3 text-muted">
                Aucun article associé à cette réservation.
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table mb-0 align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Plat</th>
                      <th>Description</th>
                      <th class="text-center">Qté</th>
                    
                    </tr>
                  </thead>
                  <tbody>
                    
                      <?php
                        $libelle = $resa['libelleArt'] ?? '';
                        $description = $resa['Description'] ?? '';
                        $qte     = (int)($resa['qte_cmd'] ?? 0);
                       
                       
                      ?>
                      <tr>
                        <td><?= e($libelle) ?></td>
                        <td><?= e($description) ?></td>
                        <td class="text-center"><?= $qte ?></td>
                        
                      </tr>
                    
                  </tbody>
                  <tfoot class="table-light">
                    <tr>
                      <th colspan="3" class="text-end">Total réservation</th>
                     
                    </tr>
                  </tfoot>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Actions rapides sur la réservation -->
        <div class="card">
          <div class="card-header bg-white">
            <span class="fw-semibold"><i class="bi bi-tools me-1"></i> Actions</span>
          </div>
          <div class="card-body d-flex flex-wrap gap-2">
            <?php if ($statut=="Confirmée"): ?>
              <form method="post" action="/RestoCampus/public/?controleur=GestionReservation&action=marquerPreparee" class="me-2">
                <input type="hidden" name="id_reservation" value="<?= (int)$resa['id_commande'] ?>">
                <button class="btn btn-outline-primary btn-sm" type="submit">
                  <i class="bi bi-clipboard-check me-1"></i> Marquer comme préparée
                </button>
              </form>



              <form method="post" action="/RestoCampus/public/?controleur=GestionReservation&action=annuler" onsubmit="return confirm('Annuler cette réservation ?');">
                <input type="hidden" name="id_commande" value="<?= (int)$resa['id_commande'] ?>">
                <button class="btn btn-outline-danger btn-sm" type="submit">
                  <i class="bi bi-x-circle me-1"></i> Annuler
                </button>
              </form>
            <?php elseif ($statut === 'Préparé'): ?>
              <form method="post" action="/RestoCampus/public/?controleur=GestionReservation&action=marquerRetiree">
                <input type="hidden" name="id_commande" value="<?= (int)$resa['id_commande'] ?>">
                <button class="btn btn-success btn-sm" type="submit">
                  <i class="bi bi-bag-check me-1"></i> Marquer comme retirée
                </button>
              </form>
            <?php else: ?>
              <span class="text-muted small">
                Aucune action disponible pour ce statut.
              </span>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
