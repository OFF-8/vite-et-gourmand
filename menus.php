<?php require 'includes/db.php';
$menus = $pdo->query(
    'SELECT menu.id, menu.titre, menu.description, menu.nombre_personnes_min, menu.prix_minimum,
            theme.nom AS theme, regime.nom AS regime
     FROM menu
     JOIN theme ON theme.id = menu.theme_id
     JOIN regime ON regime.id = menu.regime_id'
)->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<main>
    <h1>Nos menus</h1>

    <form id="filtres">
    <label for="prix-min">Prix min</label>
    <input type="number" id="prix-min">
    <label for="prix-max">Prix max</label>
    <input type="number" id="prix-max">
    <label for="filtre-theme">Thème</label>
    <select id="filtre-theme">
        <option value="">Tous</option>
        <option value="Noël">Noël</option>
        <option value="Pâques">Pâques</option>
        <option value="Anniversaire">Anniversaire</option>
        <option value="Evenement Professionnel">Evenement Professionnel</option>
    </select>
    <label for="filtre-regime">Régime</label>
    <select id="filtre-regime">
        <option value="">Tous</option>
        <option value="Classique">Classique</option>
        <option value="Végétarien">Végétarien</option>
        <option value="Vegan">Vegan</option>
    </select>
    <label for="filtre-personnes">Personnes (min du menu)</label>
    <input type="number" id="filtre-personnes">
</form>
<p id="aucun-menu" hidden>Aucun menu ne correspond.</p>
    
        <?php foreach ($menus as $unMenu) { ?>
            <article class="carte-menu" data-prix="<?php echo $unMenu['prix_minimum']; ?>" data-theme="<?php echo $unMenu['theme']; ?>" data-regime="<?php echo $unMenu['regime']; ?>" data-personnes="<?php echo $unMenu['nombre_personnes_min']; ?>">
            <p><?php echo $unMenu['titre']; ?></p>
            <p><?php echo $unMenu['description']; ?></p>
            <p><?php echo $unMenu['nombre_personnes_min']; ?></p>
            <p><?php echo $unMenu['prix_minimum']; ?></p>
            <a href="fiche-menu.php?id=<?php echo $unMenu['id']; ?>">Voir le détail</a>
            </article>
        <?php } ?>

</main>
<?php include 'includes/footer.php'; ?>

