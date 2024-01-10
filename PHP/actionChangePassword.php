<?php
session_start();

// Inclut le fichier de configuration des logs
require_once('Config.php');
require_once ('RC4.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";

// Vérifie si le formulaire de changement de mot de passe a été soumis
if (isset($_POST["changer_mot_de_passe"])) {
    // Récupère l'ID de l'utilisateur connecté
    if (isset($_SESSION['login'])) {
        //récupère le login
        $login = $_SESSION['login'];
        // Récupère le mot de passe actuel depuis le formulaire
        $mot_de_passe_actuel = $_POST['mot_de_passe_actuel'];
        $key_rc4 = "Groupe1";
        $mot_de_passe_actuel_chiffre = rc4_encrypt($mot_de_passe_actuel, $key_rc4);

        // Récupère le nouveau mot de passe depuis le formulaire
        $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
        $nouveau_mot_de_passe_chiffre = rc4_encrypt($nouveau_mot_de_passe, $key_rc4);



        // Connexion à la base de données
        $connection = mysqli_connect($host, $user, $password) or die("erreur");
        $namedb = "BD_Ticketing";
        $db = mysqli_select_db($connection, $namedb) or die("erreur");
        $tab = "user";

    } else {
        // Rediriger vers la page de connexion si le login de l'utilisateur n'est pas disponible
        header('Location: form_connexion_inscription.php');
        exit();
    }




    // Vérifie le mot de passe actuel
    $check_password_query = "SELECT login FROM $tab WHERE login = ? AND Mdp = ?";
    $check_password_stmt = mysqli_prepare($connection, $check_password_query);
    mysqli_stmt_bind_param($check_password_stmt, 'ss', $login, $mot_de_passe_actuel_chiffre);
    mysqli_stmt_execute($check_password_stmt);
    mysqli_stmt_store_result($check_password_stmt);

    if (mysqli_stmt_num_rows($check_password_stmt) > 0) {
        // Le mot de passe actuel est correct, mise à jour du mot de passe
        $update_password_query = "UPDATE $tab SET Mdp = ? WHERE login = ?";
        $update_password_stmt = mysqli_prepare($connection, $update_password_query);
        mysqli_stmt_bind_param($update_password_stmt, 'ss', $nouveau_mot_de_passe_chiffre, $login);
        $result = mysqli_stmt_execute($update_password_stmt);

        if ($result) {
            logMessage("Mot de passe changé avec succès pour l'utilisateur avec le login : $login");
            $_SESSION['message'] = 'Mot de passe changé avec succès';
            $_SESSION['couleur'] = true;
            header("Location: authentification.php");
        } else {
            logMessage("Échec du changement de mot de passe pour l'utilisateur avec le login : $login", 'error');
            $_SESSION['message'] = 'Échec du changement de mot de passe';
            $_SESSION['couleur'] = false;
            header("Location: ChangePassword.php");
        }

        // Fermeture de la requête préparée
        mysqli_stmt_close($update_password_stmt);

    } else {
        // Mot de passe actuel incorrect
        logMessage("Mot de passe actuel incorrect pour l'utilisateur avec le login : $login", 'error');
        $_SESSION['message'] = 'Mot de passe actuel incorrect';
        $_SESSION['couleur'] = false;
        header("Location: ChangePassword.php");
    }

    // Fermeture de la requête préparée
    mysqli_stmt_close($check_password_stmt);
}

// Fermeture de la connexion à la base de données
mysqli_close($connection);
exit();
?>
