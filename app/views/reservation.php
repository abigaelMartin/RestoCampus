<link rel="stylesheet" href="css/reservation.css" />

<section id="menu" class="py-5 bg-light">
  <div class="container">

    <!-- Header -->
    <div class="text-center mb-4">
      <h2 class="section-title">Notre sélection du jour</h2>
      <div class="divider mx-auto my-3"></div>
      <p class="text-muted">Des plats signatures préparés par nos chefs. </p>
    </div>

    <!-- Tools: search -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-8">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="search" id="searchMenus" class="form-control" placeholder="Rechercher un plat ou un ingrédient… (ex. truffe, poulet)" aria-label="Recherche de menus">
        </div>
      </div>
    </div>

    <?php if (empty($menus)): ?>
      <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <div>Aucun menu disponible pour le moment. Revenez plus tard ou contactez-nous pour plus d’informations.</div>
      </div>
    <?php else: ?>

      <div class="row g-4" id="menusGrid">
        <?php foreach ($menus as $menu): ?>
          <?php
            // Sécuriser/normaliser
            
            $libelle = isset($menu['libelleArt']) ? trim($menu['libelleArt']) : 'Plat';
            $libelleEsc = htmlspecialchars($libelle, ENT_QUOTES, 'UTF-8');
            $ing = isset($menu['Description']) ? trim($menu['Description']) : '';
            $ingEsc = htmlspecialchars($ing, ENT_QUOTES, 'UTF-8');

            $img = !empty($menu['image_url'])
              ? $menu['image_url']
              // fallback Unsplash : on met le libellé en requête pour un visuel cohérent
              : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200&auto=format&fit=crop';

            $stock  = isset($menu['qte_max']) ? (int)$menu['qte_max'] : null;
            $idMenu = isset($menu['id_ArtJour']) ? (string)$menu['id_ArtJour'] : '';
          ?>


          <div class="col-sm-6 col-lg-4 menu-item"
               data-title="<?= $libelleEsc ?>"
               data-ingredients="<?= $ingEsc ?>">

            <div class="card menu-card h-100">
              <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>"
                class="card-img-top"
                alt="<?= $libelleEsc ?>"
                loading="lazy"
              />

              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-start justify-content-between gap-2">

                  <h5 class="card-title mb-1"><?= $libelleEsc ?></h5>
                  
                  <?php if ($stock > 0): ?>
                    <span class="badge text-bg-success"><i class="bi bi-check2-circle me-1"></i>Dispo</span>
                  <?php else: ?>
                    <span class="badge text-bg-secondary"><i class="bi bi-x-circle me-1"></i>Indispo</span>
                  <?php endif; ?>
                  
                </div>

                <p class="card-text text-muted text-truncate-2 mb-2"><?= $ingEsc ?></p>

               
                <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                  <?php if ($stock !== null): ?>
                    <small class="text-muted mb-1">
                      <i class="bi bi-box-seam me-1"></i>
                      Stock : <?= max(0, $stock) ?>
                    </small>
                  <?php endif; ?>
                    <form action="?controleur=reservation&action=reserver" method="POST">
                      <input type="hidden" name="id_plat" value="<?= htmlspecialchars($idMenu, ENT_QUOTES, 'UTF-8') ?>">
                      <?php if($stock >0 ){?>

                        <button class="btn btn-outline-primary btn-sm reserve-btn"><i class="bi bi-bag-plus me-1"></i>Réserver</button>

                      <?php }?>
                    </form>
                  
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
  // Filtre de recherche simple (libellé + ingrédients)
  (function(){
    const input = document.getElementById('searchMenus');
    const items = document.querySelectorAll('#menusGrid .menu-item');
    if (!input || !items.length) return;

    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      items.forEach(card => {
        const title = (card.getAttribute('data-title') || '').toLowerCase();
        const ing   = (card.getAttribute('data-ingredients') || '').toLowerCase();
        const visible = !q || title.includes(q) || ing.includes(q);
        card.style.display = visible ? '' : 'none';
      });
    });
  })();

  // Pré-remplissage d'un formulaire externe si présent (ex. #reservation)
  (function(){
    const buttons = document.querySelectorAll('.reserve-btn');
    buttons.forEach(btn => {
      btn.addEventListener('click', (ev) => {
        // Si tu as une section/formulaire de réservation sur la page :
        const formPlat = document.getElementById('plat');
        const formPrix = document.getElementById('prix');
        const formId   = document.getElementById('menu_id');

        const plat = btn.getAttribute('data-plat');
        const prix = btn.getAttribute('data-prix');
        const id   = btn.getAttribute('data-id');

        if (formPlat) {
          ev.preventDefault(); // on reste sur la page pour pré-remplir
          // Sélectionne l’option correspondante si elle existe
          let matched = false;
          if (formPlat.tagName === 'SELECT') {
            [...formPlat.options].forEach(opt => {
              if (opt.text.trim() === plat) { formPlat.value = opt.value || opt.text; matched = true; }
            });
            if (!matched) {
              // fallback : insère l’option si non présente
              const opt = document.createElement('option');
              opt.value = plat;
              opt.text = plat;
              opt.selected = true;
              formPlat.appendChild(opt);
            }
          } else {
            formPlat.value = plat;
          }
          if (formPrix) formPrix.value = prix || '';
          if (formId)   formId.value = id || '';

          // Fais défiler jusqu’au formulaire
          const target = document.getElementById('reservation') || formPlat.closest('form');
          if (target && target.scrollIntoView) target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  })();
</script>
