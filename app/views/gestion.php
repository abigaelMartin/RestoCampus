<?php
// Vérifie si l'utilisateur a le droit d'accès
if (!isset($user['statut']) || !in_array($user['statut'], ['gestionnaire', 'Admin'])) {
    // Redirection si accès non autorisé
    header("Location: index.php");
    exit;
}
?>

<div class="gestion-container">

    <h2 class="gestion-title">Panel de gestion</h2>

    <div class="gestion-list">

        <a class="gestion-item" href="?controleur=Menu&action=AjouterMenu">
            <i class="bi bi-plus-circle fs-4"></i>
            <span>Ajouter un menu</span>
        </a>

        <a class="gestion-item" href="?controleur=AjouterArticle&action=AjouterArticle">
            <i class="bi bi-plus-square fs-4"></i>
            <span>Ajouter un article</span>
        </a>

        <a class="gestion-item" href="?controleur=VoiReservation&action=lister">
            <i class="bi bi-list-check fs-4"></i>
            <span>Voir les réservations</span>
        </a>

    </div>

</div>

<style>
/* --- CONTENEUR GLOBAL --- */
.gestion-container {
    padding: 2rem;
    max-width: 700px;
    margin: 0 auto;
}

/* --- TITRE --- */
.gestion-title {
    font-size: 1.9rem;
    font-weight: 800;
    letter-spacing: .5px;
    margin-bottom: 1.8rem;
    background: linear-gradient(90deg, var(--brand), var(--brand-2));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* --- LISTE D'ÉLÉMENTS --- */
.gestion-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* --- BOUTONS DE GESTION --- */
.gestion-item {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: 1rem 1.2rem;
    border-radius: 1rem;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, .08);
    text-decoration: none;
    font-weight: 600;
    color: var(--ink);
    box-shadow: 0 3px 10px rgba(0,0,0,.04);
    transition: .2s ease;
}

/* --- Icônes --- */
.gestion-item i {
    font-size: 1.3rem;
    color: var(--brand);
    transition: .2s ease;
}

/* --- Hover --- */
.gestion-item:hover {
    background: #f8fafc;
    border-color: rgba(14,165,233,.25);
    transform: translateX(4px);
}

.gestion-item:hover i {
    color: var(--brand-2);
}

/* --- Responsive --- */
@media (max-width: 600px) {
    .gestion-container { padding: 1.4rem; }
    .gestion-item { padding: .9rem .9rem; }
}
</style>