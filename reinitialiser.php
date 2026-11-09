<?php
require 'includes/db.php';

$erreur = '';
$lienValide = false;
$jeton = $_GET['jeton'] ?? $_POST['jeton'] ?? '';

$requete = $pdo->prepare(
    'SELECT utilisateur_id, expire_le, utilise FROM reinitialisation WHERE jeton = ?'
);
$requete->execute([$jeton]);
$ligne = $requete->fetch();

if ($ligne && $ligne['utilise'] == 0 && strtotime($ligne['expire_le']) >= time()) {
    $lienValide = true;
}

if (!$lienValide) {
    $erreur = "Ce lien n'est plus valide.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if ($mot_de_passe !== $confirmation) {
        $erreur = "Les mots de passe ne sont pas identiques.";
    } elseif (
        strlen($mot_de_passe) < 10
        || !preg_match('/[A-Z]/', $mot_de_passe)
        || !preg_match('/[a-z]/', $mot_de_passe)
        || !preg_match('/[0-9]/', $mot_de_passe)
        || !preg_match('/[^A-Za-z0-9]/', $mot_de_passe)
    ) {
        $erreur = 'Mot de passe trop faible (10 caractères, majuscule, minuscule, chiffre, caractère spécial).';
    } else {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE utilisateur SET mot_de_passe = ? WHERE id = ?')
            ->execute([$hash, $ligne['utilisateur_id']]);
        $pdo->prepare('UPDATE reinitialisation SET utilise = 1 WHERE jeton = ?')
            ->execute([$jeton]);
        header('Location: connexion.php');
        exit;
    }
}

require 'includes/header.php';
?>
<main>
    <h1>Nouveau mot de passe</h1>
    <?php if ($erreur !== '') { ?>
        <p><?php echo $erreur; ?></p>
    <?php } ?>
    <?php if ($lienValide) { ?>
        <form method="post">
            <p>
                <label for="mot_de_passe">Nouveau mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </p>
            <p>
                <label for="confirmation">Confirmation du mot de passe</label>
                <input type="password" id="confirmation" name="confirmation" required>
            </p>
            <input type="hidden" name="jeton" value="<?php echo htmlspecialchars($jeton); ?>">
            <button type="submit">Enregistrer</button>
        </form>
    <?php } ?>
</main>
<?php require 'includes/footer.php'; ?>
