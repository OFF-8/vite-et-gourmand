<?php
require 'includes/db.php';
$requete = $pdo->prepare ('SELECT note, commentaire FROM avis WHERE etat = ?');
$requete->execute(['valide']);
$avis = $requete->fetchAll();

?>

<?php include 'includes/header.php'; ?>
<main>
    <section class="hero">
        <h1>Vite & Gourmand</h1>
        <p>Découvrez nos menus</p>
        <a href="menus.php">Nos menus</a>
    </section>
    <section class="equipe">
        <div>
        <h2>L'équipe</h2>
        <h3>José Responsable de l'établissement</h3>
        <p>José coordonne l'organisation et le service afin de garantir une expérience fiable, agréable et adaptée à chaque client.</p>
        </div>
        <div>
        <h3>Julie Responsable de cuisine</h3>
        <p>Passionnée par la gastronomie, Julie veille à la qualité des plats et à la sélection de produits frais pour vous proposer une cuisine généreuse et soignée.</p>
        </div>
        </section>
        <section class="avis">
            <h2>Avis clients</h2>
            <?php foreach ($avis as $unAvis) { ?>
    <p><?php echo $unAvis['note']; ?>/5 — <?php echo $unAvis['commentaire']; ?></p>
<?php } ?>
</section>

</main>
<?php include 'includes/footer.php'; ?>