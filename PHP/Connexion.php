<?php
session_start();
// Inclure le fichier de configuration des logs
require_once('Config.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Vérifie si le formulaire a été soumis
if (isset($_POST["connexion"])){
    $host = "localhost";
    $user = "root";
    $password = "";
    if (!empty($_POST['login_connexion']) and !empty($_POST['mot_de_passe'])) {
        // Récupérez l'e-mail et le mot de passe saisis dans le formulaire de connexion
        $login = $_POST["login_connexion"];
        $mot_de_passe = $_POST["mot_de_passe"];

// Connexion à la base de données
        $connection = mysqli_connect($host, $user, $password) or die("Erreur de connexion à la base de données");
        $namedb = "BD_Ticketing";
        $db = mysqli_select_db($connection, $namedb) or die("Erreur de sélection de la base de données");
        $tab = "User";

// Requête SQL pour vérifier si le login existe dans la table User
        $query = "SELECT Mdp FROM $tab WHERE Login = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $login);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Le login existe, maintenant vérifiez le mot de passe
            mysqli_stmt_bind_result($stmt, $hashed_password);
            mysqli_stmt_fetch($stmt);

            // Vérifiez le mot de passe (en MD5)
            if (md5($mot_de_passe) === $hashed_password) {
                logMessage("Connexion réussie pour l'utilisateur avec le login : $login");
                header("Location: ../PHP/utilisateur.php");
            } else {
                logMessage("Tentative de connexion échouée pour l'utilisateur avec le login : $login", 'error');
                $_SESSION['message'] = "Échec de connexion. Le mot de passe est incorrect.";
                $_SESSION['couleur'] = false;
                header('Location: ../PHP/form_connexion_inscription.php');
                exit();
            }
        } else {
            logMessage("Le login n'existe pas dans la base de données.", 'error');
            echo "Le login n'existe pas dans la base de données. Veuillez vous inscrire.";
            $_SESSION['message'] = "Échec de connexion. Login incorrect.";
            $_SESSION['couleur'] = false;
            header('Location: ../PHP/form_connexion_inscription.php');
            exit();
        }

        // Fermeture de la connexion à la base de données
        mysqli_close($connection);
    }else {
        $_SESSION['message'] = "Échec de connexion. Veuillez remplir tous les champs.";
        $_SESSION['couleur'] = false;
        header('Location: ../PHP/form_connexion_inscription.php');
        exit();
    }
    $_SESSION['login'] = $login; // pour afficher le login sur la page user
}
?>
