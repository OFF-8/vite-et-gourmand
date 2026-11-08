<?php 
require 'includes/db.php';
require 'vendor/autoload.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $requete = $pdo->prepare('SELECT id, prenom FROM utilisateur WHERE email = ?');
    $requete->execute([$email]);
    $utilisateur = $requete->fetch();
    $message = 'Un email a été envoyé pour réinitialiser votre mot de passe.';
if ($utilisateur) {
    $jeton = bin2hex(random_bytes(32));
    $expire_le = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $requete = $pdo->prepare('INSERT INTO reinitialisation (utilisateur_id, jeton, expire_le, utilise) VALUES (?, ?, ?, ?)');
    $requete->execute([$utilisateur['id'], $jeton, $expire_le, 0]);
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = '127.0.0.1';
            $mail->Port = 1025;
            $mail->SMTPAuth = false;
            $mail->setFrom('contact@vite-et-gourmand.test', 'Vite & Gourmand');
            $mail->addAddress($email);
            $mail->Subject = 'Réinitialisation du mot de passe';
            $mail->Body = 'Bonjour ' . $utilisateur['prenom'] . "\n\nCliquez sur ce lien:\nhttp://localhost:8000/reinitialiser.php?jeton=" . $jeton;
            $mail->send();
    $message = 'Un email a été envoyé pour réinitialiser votre mot de passe.';

}}
include 'includes/header.php'; ?>
<main>
    <h1>Mot de passe oublié</h1>
    <?php if ($message !== '') {
        echo '<p>' . $message . '</p>';
    } ?>
    <form method="post">
        <p>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
        </p>
        <button type="submit">Envoyer</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>