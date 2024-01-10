<?php
// Démarrez la session (si elle n'est pas déjà démarrée)
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();
if (isset($_GET['erreur'])) {
    $message = $_GET['erreur'];
    header("Location: ../PHP/accueil.php?message=$message");
    exit();
}
// Rediriger vers la page d'accueil
header("Location: accueil.php");
exit();
?>
