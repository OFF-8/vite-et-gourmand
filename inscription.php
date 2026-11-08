<?php
require 'includes/db.php';
require 'vendor/autoload.php';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $gsm = trim($_POST['gsm'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if ($mot_de_passe !== $confirmation) {
        $erreur = 'Les mots de passe ne sont pas identiques.';
    } elseif (
        strlen($mot_de_passe) < 10
        || !preg_match('/[A-Z]/', $mot_de_passe)
        || !preg_match('/[a-z]/', $mot_de_passe)
        || !preg_match('/[0-9]/', $mot_de_passe)
        || !preg_match('/[^A-Za-z0-9]/', $mot_de_passe)
    ) {
        $erreur = 'Mot de passe trop faible (10 caractères, majuscule, minuscule, chiffre, caractère spécial).';
    } else {
        $deja = $pdo->prepare('SELECT id FROM utilisateur WHERE email = ?');
        $deja->execute([$email]);

        if ($deja->fetch()) {
            $erreur = 'Cet e-mail est déjà utilisé.';
        } else {
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $ajout = $pdo->prepare(
                'INSERT INTO utilisateur (nom, prenom, email, gsm, adresse, code_postal, ville, mot_de_passe, role, actif)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $ajout->execute([
                $nom, $prenom, $email, $gsm, $adresse, $code_postal, $ville,
                $hash, 'utilisateur', 1
            ]);
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
$mail->isSMTP();
$mail->Host = '127.0.0.1';
$mail->Port = 1025;
$mail->SMTPAuth = false;
$mail->setFrom('contact@vite-et-gourmand.test', 'Vite & Gourmand');
$mail->addAddress($email);
$mail->Subject = 'Bienvenue chez Vite & Gourmand';
$mail->Body = 'Bonjour ' . $prenom . ",\n\nVotre compte a bien été créé.\n\nL'équipe Vite & Gourmand";
$mail->send();

header('Location: connexion.php');
exit;
        }
    }
}
include 'includes/header.php';
?>
<main>
    <h1>Créer un compte</h1>
    <?php if ($erreur !== '') { echo '<p>' . $erreur . '</p>'; } ?>
    <form method="post">
    <p>
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required>
    </p>
    <p>
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required>
    </p>
    <p>
        <label for="gsm">Téléphone</label>
        <input type="tel" id="gsm" name="gsm" required>
    </p>
    <p>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
    </p>
    <p>
        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" required>
    </p>
    <p>
        <label for="code_postal">Code postal</label>
        <input type="text" id="code_postal" name="code_postal" required>
    </p>
    <p>
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville" required>
    </p>
    <p>
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    </p>
    <p>
        <label for="confirmation">Confirmer le mot de passe</label>
        <input type="password" id="confirmation" name="confirmation" required>
    </p>
    <p>
        <input type="checkbox" id="rgpd" name="rgpd" required>
        <label for="rgpd">J’accepte que mes données servent à créer mon compte et à commander.</label>
    </p>
    <button type="submit">Créer mon compte</button>
</form>
</main>
<?php include 'includes/footer.php'; ?>