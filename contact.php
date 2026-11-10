<?php
require 'includes/db.php';
require 'vendor/autoload.php';

$message = '';
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($titre === '' || $description === '' || $email === '') {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse e-mail invalide.';
    } elseif (empty($_POST['rgpd'])) {
        $erreur = 'Vous devez accepter l’utilisation de vos données.';
    } else {
        $date = date('Y-m-d H:i:s');
        $insert = $pdo->prepare(
            'INSERT INTO message_contact (titre, description, email, date) VALUES (?, ?, ?, ?)'
        );
        $insert->execute([$titre, $description, $email, $date]);

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = '127.0.0.1';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->setFrom('contact@vite-et-gourmand.test', 'Vite & Gourmand');
        $mail->addAddress('jose.garcia@example.com');
        $mail->Subject = 'Contact : ' . $titre;
        $mail->Body = "E-mail du visiteur : $email\n\n$description";
        $mail->send();

        $message = 'Votre message a bien été envoyé. Nous vous répondrons rapidement.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<main>
    <h1>Nous contacter</h1>
    <?php if ($erreur !== '') { ?>
        <p><?php echo htmlspecialchars($erreur); ?></p>
    <?php } ?>
    <?php if ($message !== '') { ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>
    <form action="contact.php" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="objet">Objet</label>
        <input type="text" name="titre" id="objet" required>

        <label for="message">Message</label>
        <textarea name="description" id="message" required></textarea>
        <input type="checkbox" id="rgpd" name="rgpd" required>
        <label for="rgpd">J’accepte que mes données servent à me recontacter.</label>
        <button type="submit" name="submit">Envoyer</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>