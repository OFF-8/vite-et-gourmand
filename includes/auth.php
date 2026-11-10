<?php
function exigeRole($rolesAutorises)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id'])) {
        header('Location: connexion.php');
        exit;
    }

    if (!in_array($_SESSION['role'], $rolesAutorises, true)) {
        header('Location: acces-refuse.php');
        exit;
    }
}
