<?php
// --- Titre de la page (utilisé dans header.php) ---
$title = 'Mon profil';

// On suppose que le header gère déjà la sécurité + session
// Adapter le chemin si besoin :
include 'layout/header.php'; // protège l'accès + navbar

// Récupération de l'utilisateur connecté
$user = $_SESSION['user'] ?? [];

// Petites fonctions utilitaires
function h(string $value = null) {
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<main class="py-4 py-lg-5">
  <div class="container">

    <!-- Fil d'Ariane / Intro -->
    <div class="row mb-4">
      <div class="col-12 col-lg-8">
        <h1 class="h3 mb-1">Mon profil</h1>
        <p class="text-muted mb-0">
          Consulte et mets à jour tes informations personnelles liées à ton compte RestoCampus.
        </p>
      </div>
    </div>

    <div class="row g-4">
      <!-- Carte résumé profil -->
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <?php
              $prenom = $user['prenom'] ?? '';
              $nom = $user['nom'] ?? '';
              $email = $user['email'] ?? '';
              $displayName = trim($prenom . ' ' . $nom);
              $initials = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
            ?>

            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                   style="width:64px;height:64px;background:#0ea5e9;color:#fff;font-size:1.4rem;">
                <?= $initials ?: '👤' ?>
              </div>
              <div>
                <h2 class="h5 mb-1"><?= h($displayName ?: 'Mon compte') ?></h2>
                <?php if (!empty($email)): ?>
                  <div class="text-muted small">
                    <i class="bi bi-envelope me-1"></i><?= h($email) ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <hr>

            <ul class="list-unstyled small mb-0">
              <li class="mb-2">
                <i class="bi bi-shield-lock me-1"></i>
                Compte étudiant RestoCampus
              </li>
              <li class="mb-2">
                <i class="bi bi-clock-history me-1"></i>
                Dernière mise à jour : <span class="fw-semibold">aujourd’hui</span> (après enregistrement)
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Formulaire de mise à jour -->
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h2 class="h5 mb-3">Informations personnelles</h2>
            <p class="text-muted small">
              Ces informations sont utilisées pour tes réservations sur RestoCampus.
            </p>

            <?php if (!empty($_SESSION['profil_success'])): ?>
              <div class="alert alert-success py-2 small">
                <?= h($_SESSION['profil_success']); unset($_SESSION['profil_success']); ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['profil_error'])): ?>
              <div class="alert alert-danger py-2 small">
                <?= h($_SESSION['profil_error']); unset($_SESSION['profil_error']); ?>
              </div>
            <?php endif; ?>

            <form method="post" action="?controleur=auth&action=profil">
              <!-- TODO : ajoute ici ton jeton CSRF si tu en utilises un -->
              <!-- <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token'] ?? '') ?>"> -->

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="prenom" class="form-label">Prénom</label>
                  <input type="text"
                         class="form-control"
                         id="prenom"
                         name="prenom"
                         value="<?= h($prenom) ?>"
                         required>
                </div>

                <div class="col-md-6">
                  <label for="nom" class="form-label">Nom</label>
                  <input type="text"
                         class="form-control"
                         id="nom"
                         name="nom"
                         value="<?= h($nom) ?>"
                         required>
                </div>

                <div class="col-md-6">
                  <label for="email" class="form-label">Adresse e-mail</label>
                  <input type="email"
                         class="form-control"
                         id="email"
                         name="email"
                         value="<?= h($email) ?>"
                         required>
                  <div class="form-text">
                    Utilisée pour tes confirmations de réservation.
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="telephone" class="form-label">Téléphone (optionnel)</label>
                  <input type="tel"
                         class="form-control"
                         id="telephone"
                         name="telephone"
                         value="<?= h($user['telephone'] ?? '') ?>"
                         placeholder="Ex : 06 12 34 56 78">
                </div>

                <div class="col-12">
                  <label for="regime" class="form-label">Préférences / régime alimentaire (optionnel)</label>
                  <textarea class="form-control"
                            id="regime"
                            name="regime"
                            rows="2"
                            placeholder="Ex : végétarien, sans porc, allergies…"><?= h($user['regime'] ?? '') ?></textarea>
                  <div class="form-text">
                    Ces informations peuvent aider le resto à tenir compte de tes besoins.
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="?controleur=reservation&action=liste" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left me-1"></i>Retour aux réservations
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save me-1"></i> Enregistrer les modifications
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Bloc changement de mot de passe -->
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h2 class="h6 mb-3">Sécurité du compte</h2>
            <p class="text-muted small mb-3">
              Modifie ton mot de passe pour sécuriser davantage ton compte.
            </p>

            <?php if (!empty($_SESSION['password_success'])): ?>
              <div class="alert alert-success py-2 small">
                <?= h($_SESSION['password_success']); unset($_SESSION['password_success']); ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['password_error'])): ?>
              <div class="alert alert-danger py-2 small">
                <?= h($_SESSION['password_error']); unset($_SESSION['password_error']); ?>
              </div>
            <?php endif; ?>

            <form method="post" action="?controleur=auth&action=changepassword" class="row g-3">
              <!-- TODO : jeton CSRF si besoin -->
              <div class="col-12">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password"
                       class="form-control"
                       id="current_password"
                       name="current_password"
                       required>
              </div>

              <div class="col-md-6">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password"
                       class="form-control"
                       id="new_password"
                       name="new_password"
                       required>
              </div>

              <div class="col-md-6">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password"
                       class="form-control"
                       id="confirm_password"
                       name="confirm_password"
                       required>
              </div>

              <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-outline-primary">
                  <i class="bi bi-shield-lock me-1"></i> Mettre à jour le mot de passe
                </button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>

  </div>
</main>

<?php
// Adapter le chemin si besoin :
require __DIR__ . '/layout/footer.php';
?>
