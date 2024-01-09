<?php
session_start();
// Inclure le fichier de configuration des logs
require_once('Config.php');

$login = $_SESSION['login'];
$user_role = $_SESSION['user_role'];
$message = $_SESSION['message'];
if (isset($user_role)) {
    if ($user_role == "utilisateur") {
        logMessage("Redirection vers la page utilisateur.php pour l'user avec le login : $login");
        header("Location: ../PHP/utilisateur.php");
    } elseif ($user_role == "admin_web") {
        logMessage("Redirection vers la page_adm_web pour l'user avec le login : $login");
        logMessage("Je passe dans l'authentification.php voici le message : $message");
        header("Location: ../PHP/page_adm_web.php");
    }else{
        echo "$user_role";
    }
}else{
    logMessage("Redirection vers la page accueil.php car il ne dispose pas de role pour l'user avec le login : $login");
    header("Location: ../PHP/accueil.php");
}
