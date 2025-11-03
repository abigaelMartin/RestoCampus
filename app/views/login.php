<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion - RestoCampus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/auth.css ">
</head>
<body>
  <!-- Fond -->
  <div class="bg-cover"></div>
  <div class="bg-overlay"></div>

  <!-- Contenu centré -->
  <main class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card p-4 p-sm-5">
      <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3595/3595455.png" alt="Logo RestoCampus" class="brand-logo mb-2">
        <h2 class="mb-0">Connexion</h2>
        <p class="muted-sm mb-0">Bienvenue sur RestoCampus</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 text-center" role="alert">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" novalidate>
        <div class="mb-3">
          <label for="login" class="form-label">Identifiant</label>
          <input type="text" name="login" id="login" class="form-control" autocomplete="username" required>
        </div>

        <div class="mb-2">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="**********" autocomplete="current-password" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
            <label class="form-check-label" for="remember">Se souvenir de moi</label>
          </div>
          <a href="#" class="link-primary link-underline-opacity-0 link-underline-opacity-100-hover">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
      </form>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
