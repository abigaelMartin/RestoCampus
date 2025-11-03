<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation - RestoCampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width:500px;">
        <h2 class="text-center mb-4">Réserver une table</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="/SLAM/RestoCampus/public/?action=reservation">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="heure" class="form-label">Heure</label>
                <input type="time" name="heure" id="heure" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="personnes" class="form-label">Nombre de personnes</label>
                <input type="number" name="personnes" id="personnes" class="form-control" value="1" min="1" max="10">
            </div>

            <button type="submit" class="btn btn-primary w-100">Réserver</button>
        </form>

        <a href="/RestoCampus/public/?action=logout" class="d-block mt-3 text-center text-secondary">Se déconnecter</a>
    </div>
</div>

</body>
</html>