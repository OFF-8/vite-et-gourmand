<?php
session_start();
require 'includes/db.php';
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $requete = $pdo->prepare('SELECT id, prenom, mot_de_passe, role, actif FROM utilisateur WHERE email = ?');
    $requete->execute([$email]);
    $utilisateur = $requete->fetch();
    if (!$utilisateur || !password_verify($mot_de_passe, $utilisateur['mot_de_passe']) || $utilisateur['actif'] == 0) {
        $erreur = 'Email ou mot de passe incorrect';
    } else {
        $_SESSION['id'] = $utilisateur['id'];
        $_SESSION['role'] = $utilisateur['role'];
        $_SESSION['prenom'] = $utilisateur['prenom'];
        header('Location: index.php');
        exit;
    }
}

include 'includes/header.php';
?>
<main>
    <h1>Se connecter</h1>
    <?php if ($erreur !== '') {
        echo '<p>' . $erreur . '</p>';
    } ?>
    <p><a href="inscription.php">Créer un compte</a></p>
    <form method="post">
        <p>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
        </p>
        <p>
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        </p>
        <button type="submit">Se connecter</button>
    </form>
    <p><a href="mot-de-passe-oublie.php">Mot de passe oublié</a></p>
</main>
<?php include 'includes/footer.php'; ?>