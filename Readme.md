# Architecture 
````
RestoCampus
├── app/
│   ├── controllers/       # Contrôleurs (logique métier)
│   ├── models/            # Modèles (accès aux données)
│   ├── views/             # Vues (interface utilisateur)
│   └── core/              # Routeur, base Controller et Model
├── public/                # Point d’entrée (index.php, assets)
│   ├── css/
│   ├── js/
│   └── index.php
├── config/                # Configuration (BDD, constantes)
├── routes/                # Fichier de routes (optionnel)
├── .htaccess              # Redirection vers public/index.php
└── README.md`
````
<<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Réservation de plats | RéserveTonPlat</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root{
      --brand:#7c3aed; /* violet élégant */
      --brand-2:#22c55e; /* vert d'accent */
      --ink:#0f172a;
    }

    body{font-family: system-ui, -apple-system, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji"; color:var(--ink)}

    /* Hero */
    .hero{
      position:relative;
      min-height:68vh;
      display:grid;
      place-items:center;
      background:linear-gradient(to bottom, rgba(12,10,29,.75), rgba(12,10,29,.85)),
                 url('https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1600&auto=format&fit=crop');
      background-size:cover; background-position:center;
      color:#fff;
    }
    .hero .content{max-width:920px; text-align:center}
    .badge-soft{
      background: rgba(124,58,237,.15);
      color:#fff; border:1px solid rgba(255,255,255,.2);
      backdrop-filter: blur(6px);
    }

    /* Cards */
    .menu-card img{height: 190px; object-fit: cover;}
    .menu-card{transition: transform .2s ease, box-shadow .2s ease}
    .menu-card:hover{transform: translateY(-4px); box-shadow: 0 1.25rem 2rem rgba(16,24,40,.12)}

    /* Section titles */
    .section-title{font-weight:800; letter-spacing:.2px}
    .divider{
      width:64px; height:6px; border-radius:999px; background:linear-gradient(90deg,var(--brand),var(--brand-2));
    }

    /* Form */
    .form-wrapper{
      background: #fff; border:1px solid #e9ecef; border-radius:1.25rem;
      box-shadow: 0 .75rem 1.5rem rgba(2, 6, 23, .06);
    }

    /* Footer */
    footer a{ color:inherit; text-decoration:none }
    footer a:hover{text-decoration:underline}
  </style>
</head>
<body>
   <section id="menu" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="section-title">Notre sélection</h2>
        <div class="divider mx-auto my-3"></div>
        <p class="text-muted">Des plats signatures préparés par notre chef. Cliquez pour pré-remplir le formulaire de réservation.</p>
      </div>
      
    <?php foreach ($menus as $menu):?>
      
      <!-- Card 1 -->
      <div class="col-md-6 col-lg-4">
        <div class="card menu-card h-100">
          <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200&auto=format&fit=crop" class="card-img-top" alt="Salade César gourmet" />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php $menu["libelleArt"]?></h5>
            <p class="card-text text-muted mb-2">Poulet fermier, copeaux de parmesan, croûtons maison, sauce crémeuse.</p>
            <div class="d-flex align-items-center justify-content-between mt-auto">
              <span class="fw-semibold">12,90 €</span>
              <button class="btn btn-outline-primary btn-sm reserve-btn" data-plat="Salade César gourmet"><i class="bi bi-bag-plus me-1"></i>Réserver</button>
            </div>
          </div>
        </div>
      </div>
       
    <?php endforeach; ?>
  </section>

 

    // Année courante footer
    document.getElementById('year').textContent = new Date().getFullYear();

    // Date min = aujourd'hui
    const dateInput = document.getElementById('date');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth()+1).padStart(2,'0');
    const dd = String(today.getDate()).padStart(2,'0');
    dateInput.min = `${yyyy}-${mm}-${dd}`;

    // Validation Bootstrap + récapitulatif + toast
    (function(){
      const form = document.getElementById('reservationForm');
      const toastEl = document.getElementById('toastOk');
      const toast = bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 3000 });
      const recapModal = new bootstrap.Modal(document.getElementById('recapModal'));
      const recapContent = document.getElementById('recapContent');

      form.addEventListener('submit', (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (!form.checkValidity()) {
          form.classList.add('was-validated');
          return;
        }
        // Construire le récapitulatif simple
        const data = {
          plat: document.getElementById('plat').value,
          date: document.getElementById('date').value,
          heure: document.getElementById('heure').value,
          quantite: document.getElementById('quantite').value,
          nom: document.getElementById('nom').value,
          tel: document.getElementById('tel').value,
          email: document.getElementById('email').value,
          commentaires: document.getElementById('commentaires').value || '—'
        };

        recapContent.innerHTML = `
          <div class="row g-2">
            <div class="col-6"><strong>Plat</strong><br>${data.plat}</div>
            <div class="col-3"><strong>Qté</strong><br>${data.quantite}</div>
            <div class="col-3"><strong>Heure</strong><br>${data.heure}</div>
            <div class="col-6"><strong>Date</strong><br>${data.date}</div>
            <div class="col-6"><strong>Client</strong><br>${data.nom}</div>
            <div class="col-6"><strong>Téléphone</strong><br>${data.tel}</div>
            <div class="col-6"><strong>Email</strong><br>${data.email}</div>
            <div class="col-12"><strong>Commentaires</strong><br>${data.commentaires}</div>
          </div>`;

        recapModal.show();
        form.reset();
        form.classList.remove('was-validated');
        toast.show();
      }, false);
    })();
  </script>
</body>
</html>
