<?php
// Si la session n'est pas déjà démarrée par ton contrôleur :
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$user = $_SESSION['id'] ?? null; // ex: ['id'=>1,'login'=>'Tanzila','nom'=>'K.','email'=>'tanzila@lycee.fr']
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? $title . ' | Resto+' : 'Resto+ — Réservation de plats'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --brand:#0ea5e9; --brand-2:#22c55e; }
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", Arial; }
    .navbar { background: linear-gradient(90deg, var(--brand), var(--brand-2)); }
    .navbar-brand { font-weight:700; color:#fff !important; font-size:1.4rem; letter-spacing:.5px; }
    .navbar-nav .nav-link { color:#fff; font-weight:500; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color:#f8fafc; text-decoration:underline; }
    .btn-login { background:#fff; color:var(--brand); font-weight:600; border-radius:50px; padding:.4rem 1rem; }
    .avatar-circle {
      width:32px; height:32px; border-radius:50%; background:#ffffff22; color:#fff;
      display:inline-flex; align-items:center; justify-content:center; font-weight:700; text-transform:uppercase;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-egg-fried me-1"></i> RestoCampus
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door me-1"></i>Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="reservation.php"><i class="bi bi-bag me-1"></i>Réserver</a></li>
        <li class="nav-item"><a class="nav-link" href="mes_reservations.php"><i class="bi bi-calendar-check me-1"></i>Mes réservations</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="bi bi-envelope me-1"></i>Contact</a></li>
      </ul>

      <?php if ($user): ?>
        <!-- Dropdown Mon compte -->
        <?php
          $initials = strtoupper(substr($user['login'] ?? '', 0, 1) . substr($user['login'] ?? '', 0, 1));
          $displayName = trim(($user['login'] ?? '') . ' ' . ($user['login'] ?? ''));
          echo $displayName;
        ?>
        <div class="dropdown ms-lg-3">
          <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar-circle"><?= $initials ?: '👤' ?></span>
            <span class="d-none d-sm-inline"><?= htmlspecialchars($displayName ?: ($user['email'] ?? 'Mon compte')) ?></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><h6 class="dropdown-header"><?= htmlspecialchars($displayName ?: 'Mon compte') ?></h6></li>
            <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
            <li><a class="dropdown-item" href="mes_reservations.php"><i class="bi bi-receipt-cutoff me-2"></i>Mes réservations</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
          </ul>
        </div>
      <?php else: ?>
        <!-- Bouton Connexion -->
        <a href="../../public/index.php?controleur=auth&&action=login" class="btn btn-login ms-lg-3"><i class="bi bi-person-circle me-1"></i>Connexion</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
