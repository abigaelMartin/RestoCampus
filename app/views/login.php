<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion - RestoCampus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../public/css/auth.css">
  <style>
    /* --- Fond avec image + overlay pour lisibilité --- */
    body {
        position: relative;
        min-height: 100vh;
        min-height: 100dvh;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", "Times New Roman", Times, serif;
        margin: 0;
    }
    .bg-cover {
        position: fixed;
        inset: 0;
        background:
        linear-gradient(135deg, rgba(248, 249, 251, 0.4), rgba(102,16,242,.4)),
        url('../../public/images/bg_resto1.jpg') center/cover no-repeat fixed;
        z-index: -2;
    }
    .bg-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.35);
        z-index: -1;
        backdrop-filter: blur(2px);
    }

    /* --- Carte de login responsive --- */
    .login-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        box-shadow: 0 10px 28px rgba(0,0,0,.15);
        width: clamp(300px, 92vw, 420px);
        animation: fadeIn .6s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .brand-logo {
        width: 64px;
        height: 64px;
        object-fit: cover;
    }

    .form-label {
        font-weight: 700;
        font-size: 1rem;
    }

    .form-control {
        border-radius: 12px;
        padding: .8rem .9rem;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }

    .btn-primary {
        border-radius: 999px;
        padding: .8rem 1rem;
        font-weight: 600;
        background: linear-gradient(90deg, #007bff, #6610f2);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #0056d2, #520dc2);
    }

    .muted-sm { color: #6c757d; font-size: .95rem; }

    @media (max-width: 400px){
        h2 { font-size: 1.4rem; }
        .muted-sm { font-size: .9rem; }
    }

    @media (prefers-reduced-motion: reduce){
        .login-card { animation: none; }
    }
  </style>
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


      <form action="/RestoCampus/public/?action=login" method="POST" novalidate>
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