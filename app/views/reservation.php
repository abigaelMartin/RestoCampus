<h2>Menus disponibles</h2>
<ul>
  <?php foreach ($menus as $menu): ?>
    <li>
      <?= htmlspecialchars($menu['libelleArt']) ?> - <?= htmlspecialchars($menu['libelleIng']) ?>
      <form method="POST" action="?controleur=reservation&action=reserver">
        <input type="hidden" name="id_menu" value="<?= $menu['id_article'] ?>">
        <button type="submit">Réserver</button>
      </form>
    </li>
  <?php endforeach; ?>
</ul>
