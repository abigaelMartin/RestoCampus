<?php
// Sécurité : accès gestionnaire ou admin
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['statut'] ?? '', ['gestionnaire', 'Admin'])) {
    header("Location: /RestoCampus/public/?controleur=Auth&action=login");
    exit;
}

$title = $title ?? 'Liste des utilisateurs';

// $users attendu depuis le contrôleur :
// $users = [
//   [
//     'id_user'  => 1,
//     'nom'      => 'DUPONT',
//     'prenom'   => 'Lucas',
//     'login'    => 'lucas.dupont',
//     'password' => '...hash...',
//     'statut'   => 'etudiant', // ou 'gestionnaire', 'Admin', ...
//     'is_active'=> 1
//   ],
//   ...
// ];

// Helper d'échappement
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

include '../app/views/layout/header.php';
?>

<style>
  .page-head{
    background:linear-gradient(to bottom right, rgba(14,165,233,.10), rgba(34,197,94,.10));
    border-bottom:1px solid #eef2f7;
  }
  .badge-soft{
    background:#eff6ff;
    color:#1d4ed8;
    border-radius:999px;
    padding:.15rem .6rem;
    font-size:.78rem;
  }
  .user-avatar{
    width:32px; height:32px; border-radius:50%;
    background:#e5e7eb;
    display:inline-flex; align-items:center; justify-content:center;
    font-weight:700; font-size:.85rem;
    color:#374151;
  }
</style>

<header class="page-head py-4 mb-3">
  <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Utilisateurs</h1>
      <p class="mb-0 text-muted">
        Gestion des comptes (étudiants, gestionnaires, administrateurs) de la plateforme.
      </p>
    </div>
    <div class="text-end">
      <span class="badge-soft">
        <i class="bi bi-people me-1"></i>
        <?= !empty($users) ? count($users) . ' utilisateur(s)' : 'Aucun utilisateur' ?>
      </span>
      <!-- Bouton pour créer un user si tu gères ça -->
      <div class="mt-1">
        <a href="/RestoCampus/public/?controleur=utilisateur&action=ajouter" class="btn btn-primary btn-sm">
          <i class="bi bi-person-plus me-1"></i>Importer un utilisateur
        </a>
      </div>
    </div>
  </div>
</header>

<section class="py-3">
  <div class="container">

    <?php if (empty($users)): ?>
      <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <div>Aucun utilisateur trouvé dans la base de données.</div>
      </div>
    <?php else: ?>

      <!-- Barre de recherche simple (à brancher côté contrôleur si tu veux filtrer en GET) -->
      <form class="row gy-2 gx-3 align-items-center mb-3" method="get" action="">
        <input type="hidden" name="controleur" value="utilisateur">
        <input type="hidden" name="action" value="liste">
        <div class="col-sm-8 col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search"
                id="searchUsers"
                class="form-control"
                placeholder="Rechercher par nom, prénom ou login…">
        </div>
        </div>

        <!-- <div class="col-sm-4 col-md-3">
          <select class="form-select" name="statut">
            <?php $s = $_GET['statut'] ?? ''; ?>
            <option value="">Tous les statuts</option>
            <option value="etudiant"     <?= $s === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
            <option value="gestionnaire" <?= $s === 'gestionnaire' ? 'selected' : '' ?>>Gestionnaire</option>
            <option value="Admin"        <?= $s === 'Admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>
        <div class="col-sm-12 col-md-3 d-flex gap-2">
          <button class="btn btn-outline-secondary flex-fill" type="submit">Filtrer</button>
          <a class="btn btn-outline-light border flex-fill" href="/RestoCampus/public/?controleur=utilisateur&action=liste">
            Réinitialiser
          </a>
        </div> -->
      </form>

      <div class="table-responsive">
        <table class="table align-middle table-hover" id="usersTable">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Utilisateur</th>
              <th>Login</th>
              <th>Statut</th>
              <th>Actif</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <?php
                $id     = $u['id_user'];
                $nom    = $u['nom'] ?? '';
                $prenom = $u['prenom'] ?? '';
                $login  = $u['login'] ?? '';
                $statut = $u['statut'] ?? '';
                $active = (int)($u['is_active'] ?? 0) === 1;

                $initials = strtoupper(
                  mb_substr($prenom, 0, 1, 'UTF-8') .
                  mb_substr($nom, 0, 1, 'UTF-8')
                );

                // Badge statut
                $statutBadge = match($statut) {
                  'Admin'        => '<span class="badge text-bg-danger"><i class="bi bi-shield-lock me-1"></i>Admin</span>',
                  'gestionnaire' => '<span class="badge text-bg-primary"><i class="bi bi-gear me-1"></i>Gestionnaire</span>',
                  'etudiant'     => '<span class="badge text-bg-success"><i class="bi bi-mortarboard me-1"></i>Étudiant</span>',
                  default        => '<span class="badge text-bg-secondary">'.e($statut ?: 'Autre').'</span>',
                };

                // Badge actif
                $activeBadge = $active
                  ? '<span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Actif</span>'
                  : '<span class="badge text-bg-secondary"><i class="bi bi-slash-circle me-1"></i>Inactif</span>';
              ?>
              <tr>
                <td><?= e($id) ?></td>

                <!-- Colonne utilisateur (avatar + nom) -->
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <span class="user-avatar"><?= e($initials ?: 'U') ?></span>
                    <div>
                      <div class="fw-semibold"><?= e($prenom . ' ' . $nom) ?></div>
                      <small class="text-muted">ID #<?= e($id) ?></small>
                    </div>
                  </div>
                </td>

                <!-- Login -->
                <td>
                  <code><?= e($login) ?></code>
                </td>

                <!-- Statut -->
                <td><?= $statutBadge ?></td>

                <!-- Actif -->
                <td><?= $activeBadge ?></td>

                <!-- Actions -->
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <!-- Modifier -->
                    <a href="/RestoCampus/public/?controleur=utilisateur&action=modifier&id=<?= e($id) ?>"
                       class="btn btn-sm btn-outline-warning"
                       title="Modifier">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <!-- Activer/désactiver -->
                    <form method="post"
                          action="/RestoCampus/public/?controleur=utilisateur&action=toggleActive"
                          class="d-inline"
                          onsubmit="return confirm('Confirmer ce changement d\\\'état du compte ?');">
                      <?php if (!empty($csrf_token)): ?>
                        <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
                      <?php endif; ?>
                      <input type="hidden" name="id" value="<?= e($id) ?>">
                      <button class="btn btn-sm <?= $active ? 'btn-outline-secondary' : 'btn-outline-success' ?>"
                              type="submit"
                              title="<?= $active ? 'Désactiver' : 'Activer' ?>">
                        <?php if ($active): ?>
                          <i class="bi bi-person-slash"></i>
                        <?php else: ?>
                          <i class="bi bi-person-check"></i>
                        <?php endif; ?>
                      </button>
                    </form>

                    <!-- (Optionnel) Suppression -->
                    <form method="post"
                          action="/RestoCampus/public/?controleur=utilisateur&action=supprimer"
                          class="d-inline"
                          onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                      <?php if (!empty($csrf_token)): ?>
                        <input type="hidden" name="csrf" value="<?= e($csrf_token) ?>">
                      <?php endif; ?>
                      <input type="hidden" name="id" value="<?= e($id) ?>">
                      <button class="btn btn-sm btn-outline-danger" type="submit" title="Supprimer">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("searchUsers");
    const table = document.getElementById("usersTable");
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

