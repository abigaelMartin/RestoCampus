<?php
// Accès réservé
if (!isset($user['statut']) || !in_array($user['statut'], ['gestionnaire', 'Admin'])) {
  header("Location: index.php");
  exit;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
  :root { --brand:#0ea5e9; --brand-2:#22c55e; --ink:#0f172a; }

  .panel-head{
    background:linear-gradient( to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));
    border-bottom:1px solid #eef2f7;
  }
  .panel-title{
    font-weight:800; letter-spacing:.2px;
    background: linear-gradient(90deg,var(--brand),var(--brand-2));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  }

  .tile{
    text-decoration:none; color:inherit;
  }
  .tile .card{
    border:1px solid rgba(15,23,42,.08); border-radius:1rem;
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    height:100%;
  }
  .tile .card:hover{
    transform: translateY(-4px);
    box-shadow: 0 .75rem 1.5rem rgba(2,6,23,.08);
    border-color: rgba(14,165,233,.35);
  }

  .icon-badge{
    width:48px; height:48px; border-radius:999px;
    display:inline-flex; align-items:center; justify-content:center;
    background: radial-gradient(120% 150% at 0% 0%, rgba(14,165,233,.18), transparent 60%),
                radial-gradient(120% 150% at 100% 0%, rgba(34,197,94,.18), transparent 60%),
                #fff;
    border:1px solid rgba(14,165,233,.25);
    color: var(--brand);
  }
  .tile h3{
    font-size:1.05rem; font-weight:700; margin:0;
  }
  .tile p{
    margin:0; color:#6b7280; font-size:.9rem;
  }
  .tile .chev{
    color:#9aa3af; transition: transform .18s ease;
  }
  .tile .card:hover .chev{ transform: translateX(3px); color: var(--brand-2); }

  /* 3 colonnes desktop, 2 tablette, 1 mobile => via Bootstrap classes dans le HTML */
</style>
<?php include '../app/views/layout/header.php'?>


<!-- En-tête -->
<header class="panel-head py-4">
  <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="panel-title h3 mb-1">Panel de gestion</h1>
      <p class="text-muted mb-0">Choisissez une action à effectuer sur le self du lycée.</p>
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
  
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="h5 mb-0">Actions disponibles</h2>
        <div class="input-group" style="max-width: 320px;">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search"
                id="searchActions"
                class="form-control"
                placeholder="Rechercher une action... (ex. menu, stock, horaires)">
        </div>
    </div>


    <div class="row g-3" id="actionsGrid">
      <!-- 1 Ajouter un article -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=article&action=AjouterUnArticle">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-plus-circle fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Ajouter un article</h3>
              <p>Créer un nouvel ingrédient/produit du stock.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- 2 Ajouter un menu -->
      <div class="col-12 col-sm-6 col-lg-4">
    <a class="tile" href="?controleur=menu&action=ajouter">
        <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
                <span class="icon-badge">
                    <i class="bi bi-plus-square fs-5"></i>
                </span>
                <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
                <h3>Ajouter un menu</h3>
                <p>Composer le plat du jour et le publier.</p>
            </div>
        </div>
    </a>
</div>

      <!-- 3 Voir les réservations -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=GestionReservation&action=liste">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-list-check fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Voir les réservations</h3>
              <p>Filtrer, trier et consulter le détail.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=Proposition&action=liste">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-bag-plus me-1"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Gérer les menus du jour</h3>
              <p>Modifier, désactiver, ...</p>
            </div>
          </div>
        </a>
      </div>

      <!-- 4 Gérer les réservations (statuts) -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=Reservation&action=backoffice">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-clipboard-check fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Gérer les statuts</h3>
              <p>Confirmer, préparer, marquer retirée, annuler.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- 5 Stocks & approvisionnement -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=article&action=liste">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-box-seam fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Stocks & appro</h3>
              <p>Suivre quantités, seuils et réassort.</p>
            </div>
          </div>
        </a>
      </div>


      
      <!-- 6 Créneaux de retrait -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=proposition&action=proposer">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-clock-history fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Proposition de menu du jour</h3>
              <p>Rendre disponible les menus, pour la reservation.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- 7 Statistiques -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=stats&action=index">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-graph-up-arrow fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Statistiques</h3>
              <p>Volumes, heures de pointe, plats favoris.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- 8 Exportations -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=user&action=liste">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-filetype-csv fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Exportations</h3>
              <p>Export CSV / PDF des réservations.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- 9 Paramètres -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="tile" href="?controleur=parametres&action=index">
          <div class="card p-3">
            <div class="d-flex align-items-start justify-content-between">
              <span class="icon-badge"><i class="bi bi-gear fs-5"></i></span>
              <i class="bi bi-chevron-right chev"></i>
            </div>
            <div class="pt-2">
              <h3>Paramètres</h3>
              <p>Infos cantine, moyens de contact, droits.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>
<script>
  (function() {
    const input = document.getElementById('searchActions');
    const grid  = document.getElementById('actionsGrid');
    if (!input || !grid) return;

    const tiles = grid.querySelectorAll('.tile');

    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();

      tiles.forEach(tile => {
        const text = tile.innerText.toLowerCase(); // titre + description
        const match = !q || text.includes(q);
        tile.closest('.col-12, .col-sm-6, .col-lg-4')?.classList.toggle('d-none', !match);
      });
    });
  })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

