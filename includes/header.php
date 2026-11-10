<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite et Gourmand</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <a class="logo" href="index.php">Vite & Gourmand</a>
        <button class="menu-btn" id="menu-btn">Menu</button>
        <nav id="menu-nav">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="menus.php">Nos menus</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['id'])) { ?>
                    <?php if ($_SESSION['role'] === 'utilisateur') { ?>
                        <li><a href="espace-utilisateur.php">Mon espace</a></li>
                    <?php } ?>
                    <?php if ($_SESSION['role'] === 'employe' || $_SESSION['role'] === 'administrateur') { ?>
                        <li><a href="espace-employe.php">Espace employé</a></li>
                    <?php } ?>
                    <?php if ($_SESSION['role'] === 'administrateur') { ?>
                        <li><a href="espace-admin.php">Administration</a></li>
                    <?php } ?>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                <?php } else { ?>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php">Inscription</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>