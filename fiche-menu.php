<?php
require 'includes/db.php';
$id = (int) ($_GET['id'] ?? 0);
$requete = $pdo->prepare(
    'SELECT menu.id, menu.titre, menu.description, menu.nombre_personnes_min, menu.prix_minimum, menu.conditions, menu.precautions, menu.stock,
            theme.nom AS theme, regime.nom AS regime 
     FROM menu
     JOIN theme ON theme.id = menu.theme_id
     JOIN regime ON regime.id = menu.regime_id WHERE menu.id = ?'
);
$requete->execute([$id]);
$menu = $requete->fetch();
$plats = [];
if ($menu) {
    $reqPlats = $pdo->prepare(
        'SELECT plat.nom, plat.type, plat.id
 FROM plat
 JOIN menu_plat ON menu_plat.plat_id = plat.id
 WHERE menu_plat.menu_id = ?
 ORDER BY FIELD(plat.type, \'entree\', \'plat\', \'dessert\'), plat.nom'
    );
    $reqPlats->execute([$id]);
    $plats = $reqPlats->fetchAll();
    $reqImages = $pdo->prepare('SELECT chemin FROM image_menu WHERE menu_id = ?');
    $reqImages->execute([$id]);
    $images = $reqImages->fetchAll();
}
if (!$menu) {
    include 'includes/header.php';
    echo '<main><h1>Menu introuvable</h1></main>';
    include 'includes/footer.php';
    exit;
}
?>
<?php include 'includes/header.php'; ?>
<main>
    <h1><?php echo $menu['titre']; ?></h1>
    <p><?php echo $menu['description']; ?></p>
    <?php foreach ($images as $img) { ?>
        <img src="<?php echo htmlspecialchars($img['chemin']); ?>" alt="">
    <?php } ?>
    <h2>Thème</h2>
    <p><?php echo $menu['theme']; ?></p>
    <h2>Régime</h2>
    <p><?php echo $menu['regime']; ?></p>
    <h2>Nombre de personnes</h2>
    <p><?php echo $menu['nombre_personnes_min']; ?> personnes</p>
    <h2>Prix minimum</h2>
    <p><?php echo $menu['prix_minimum']; ?> €</p>
    <h2>Stock</h2>
    <p><?php echo $menu['stock']; ?></p>
    <h2>Plats</h2>
    <?php
    $reqAllergenes = $pdo->prepare(
        'SELECT allergene.nom
     FROM plat_allergene
     JOIN allergene ON allergene.id = plat_allergene.allergene_id
     WHERE plat_allergene.plat_id = ?'
    );
    ?>
    <?php
    $labelsType = [
        'entree' => 'Entrée',
        'plat' => 'Plat',
        'dessert' => 'Dessert',
    ];
    ?>
    <?php foreach ($plats as $plat) {
        $reqAllergenes->execute([$plat['id']]);
        $allergenes = $reqAllergenes->fetchAll();
    ?>
        <p>
            <?php
            $typeAffiche = $labelsType[$plat['type']] ?? ucfirst($plat['type']);
            echo htmlspecialchars($typeAffiche);
            ?> :
            <?php echo htmlspecialchars($plat['nom']); ?>
            <?php if ($allergenes) { ?>
                — Allergènes :
                <?php
                $noms = [];
                foreach ($allergenes as $a) {
                    $noms[] = $a['nom'];
                }
                echo htmlspecialchars(implode(', ', $noms));
                ?>
            <?php } ?>
        </p>
    <?php } ?>
    <h2>Conditions de commande</h2>
    <p><?php echo $menu['conditions']; ?></p>
    <h2>Précautions</h2>
    <p><?php echo $menu['precautions']; ?></p>
    <p><a href="commander.php?menu_id=<?php echo (int) $menu['id']; ?>">Commander ce menu</a></p>
</main>
<?php include 'includes/footer.php'; ?>