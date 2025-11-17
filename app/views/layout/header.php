<?php
// --- Sécurité : accès réservé aux utilisateurs connectés ---
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user'])) {
  header('Location: /RestoCampus/public/?controleur=auth&action=login');
  exit();
}

$user = $_SESSION['user']; // ex : ['prenom'=>'Tanzila','nom'=>'K.','email'=>'tanzila@lycee.fr']
$initials = strtoupper(substr($user['prenom'] ?? '', 0, 1) . substr($user['nom'] ?? '', 0, 1));
$displayName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RestoCampus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --brand:#0ea5e9; --brand-2:#22c55e; --ink:#0f172a; }

    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", Arial; color:var(--ink); }

    .navbar { background: linear-gradient(90deg, var(--brand), var(--brand-2)); }
    .navbar-brand { font-weight:700; color:#fff !important; font-size:1.4rem; letter-spacing:.5px; }
    .navbar-nav .nav-link { color:#fff; font-weight:500; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color:#f8fafc; text-decoration:underline; }

    .avatar-circle {
      width:34px; height:34px; border-radius:50%; background:#0ea5e9; color:#fff;
      display:inline-flex; align-items:center; justify-content:center; font-weight:700; text-transform:uppercase;
    }
    .dropdown-toggle::after { display:none; }

    /* ---------- MENU COMPTE STYLÉ ---------- */
    .account-menu.dropdown-menu {
      padding:0;                /* on gère les paddings section par section */
      overflow:hidden;          /* arrondis propres */
      border-radius: .9rem;
      border: 1px solid rgba(15,23,42,.08);
      min-width: 18rem;
      animation: dropdownIn .15s ease both;
    }
    @keyframes dropdownIn {
      from { transform: translateY(6px); opacity: 0; }
      to   { transform: translateY(0);   opacity: 1; }
    }

    .account-head {
      background: radial-gradient(120% 150% at 0% 0%, rgba(14,165,233,.18), transparent 60%),
                  radial-gradient(120% 150% at 100% 0%, rgba(34,197,94,.18), transparent 60%),
                  linear-gradient(90deg, var(--brand), var(--brand-2));
      color:#fff;
      padding: .95rem 1rem;
    }
    .account-head .big-avatar {
      width:44px; height:44px; border-radius:50%; background:#ffffff22; color:#fff;
      display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:1.05rem;
      border: 1px solid #ffffff33;
    }
    .account-head .name { font-weight:700; line-height:1.1; }
    .account-head .mail { opacity:.95; font-size:.85rem; }

    .account-body { padding:.25rem; background:#fff; }
    .account-item {
      display:flex; align-items:center; gap:.6rem;
      padding:.75rem .9rem;
      border-radius:.6rem;
      color:#0f172a; text-decoration:none;
    }
    .account-item:hover {
      background:#f8fafc;
    }
    .account-item i { opacity:.8; }

    .account-footer {
      border-top:1px solid rgba(2,6,23,.06);
      background:#fff;
      padding:.6rem;
    }
    .btn-logout {
      width:100%;
    }

    /* ---- Mobile : plein écran horizontal, items larges ---- */
    @media (max-width: 991.98px) {
      .account-menu.dropdown-menu-end {
        position: static !important;
        transform: none !important;
        width: 100%;
        border-radius: 0;
        border-left: 0; border-right:0;
        box-shadow: none;
        margin-top:.5rem;
      }
      .account-item { padding:1rem 1rem; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">
      <i class="bi bi-egg-fried me-1"></i> RestoCampus
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

      
        <li class="nav-item"><a class="nav-link" href="?controleur=reservation&action=liste"><i class="bi bi-bag me-1"></i>Réserver</a></li>
      </ul>

      <!-- Dropdown Mon compte (stylé) -->
      <div class="dropdown ms-lg-3">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="avatar-circle"><?= $initials ?: '👤' ?></span>
          <span class="d-none d-sm-inline"><?= htmlspecialchars($displayName ?: ($user['email'] ?? 'Mon compte')) ?><i class=" bi-chevron-down m-2"></i></span>
          
        </button>

        <div class="dropdown-menu dropdown-menu-end account-menu">
          <!-- Tête -->
          <div class="account-head">
            <div class="d-flex align-items-center gap-3">
              <div class="big-avatar"><?= $initials ?: '👤' ?></div>
              <div>
                <div class="name"><?= htmlspecialchars($displayName ?: 'Mon compte') ?></div>
                <?php if (!empty($user['email'])): ?>
                  <div class="mail"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($user['email']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Corps : actions -->
          <div class="account-body">


          <?php if (isset($user['statut']) && in_array($user['statut'], ['gestionnaire', 'Admin'])): ?>
            <a class="account-item" href="?controleur=gestion&action=panel">
                <i class="bi bi-tools fs-5"></i> <span>Gestion</span>
            </a>
        <?php endif; ?>


            <a class="account-item" href="?controleur=auth&action=profil">
              <i class="bi bi-person fs-5"></i> <span>Profil</span>
            </a>
            <a class="account-item" href="?controleur=reservation&action=mesreservations">
              <i class="bi bi-receipt-cutoff fs-5"></i> <span>Mes réservations</span>
            </a>
            <a class="account-item" href="#">
              <i class="bi bi-gear fs-5"></i> <span>Paramètres</span>
            </a>
          </div>

          <!-- Pied : déconnexion -->
          <div class="account-footer">
            <a class="btn btn-outline-danger btn-logout" href="?controleur=auth&action=logout">
              <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
            </a>
          </div>
        </div>
      </div>
      <!-- /Dropdown -->
    </div>
  </div>
</nav>

<!-- SCRIPTS (ordre corrigé) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Initialiser explicitement les dropdowns (sécurité)
  document.querySelectorAll('.dropdown-toggle').forEach(el => {
    new bootstrap.Dropdown(el);
  });

  // Fermer automatiquement le menu burger après clic sur un lien
  const navMenu = document.getElementById('navMenu');
  const toggler = document.querySelector('.navbar-toggler');
  if (navMenu && toggler) {
    document.querySelectorAll('#navMenu .nav-link, #navMenu .dropdown-item, #navMenu .btn-logout, #navMenu .account-item').forEach(link => {
      link.addEventListener('click', () => {
        const togglerVisible = window.getComputedStyle(toggler).display !== 'none';
        const instance = bootstrap.Collapse.getOrCreateInstance(navMenu, { toggle: false });
        if (togglerVisible) instance.hide();
      });
    });
  }
</script>