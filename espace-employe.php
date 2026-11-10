<?php
require 'includes/auth.php';
exigeRole(['employe', 'administrateur']);
include 'includes/header.php';
?>
<main>
    <h1>Espace employé</h1>
</main>
<?php include 'includes/footer.php'; ?>
