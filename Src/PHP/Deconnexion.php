<?php
// Démarrez la session (si elle n'est pas déjà démarrée)
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil
header("Location: ../HTML/Accueil.html");
exit();
?>
