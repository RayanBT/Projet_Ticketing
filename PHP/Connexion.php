<?php
session_start();
// Inclure le fichier de configuration des logs
require_once('Config.php');
require_once ('RC4.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Vérifie si le formulaire a été soumis
if (isset($_POST["connexion"])) {
    if (isset($_POST['captcha']) && isset($_SESSION['captcha'])) {
        $userAnswer = intval($_POST['captcha']);
        $correctAnswer = $_SESSION['captcha'];

        if ($userAnswer === $correctAnswer) {
            $host = "localhost";
            $user = "root";
            $password = "";
            $key_rc4 = "Groupe1";
            if (!empty($_POST['login_connexion']) and !empty($_POST['mot_de_passe'])) {
                // Récupérez l'e-mail et le mot de passe saisis dans le formulaire de connexion
                $login = $_POST["login_connexion"];
                $_SESSION['login'] = $login;
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
                    if ($mot_de_passe === rc4_decrypt($hashed_password, $key_rc4)) {
                        logMessage("Connexion réussie pour l'utilisateur avec le login : $login");
                        $query = "SELECT user_role FROM $tab WHERE Login = '$login'";
                        $resultat = mysqli_query($connection, $query);
                        if ($resultat) {
                            $row = mysqli_fetch_assoc($resultat);
                            $_SESSION['user_role'] = $row['user_role'];
                            $_SESSION['message'] = "Connexion réussie. Bienvenue $login !";
                            $_SESSION['couleur'] = true;
                            header("Location: ../PHP/authentification.php");
                        } else {
                            logMessage("User_role est vide pour l'utilisateur avec le login : $login");
                            header('Location: ../PHP/form_connexion_inscription.php');
                        }
                    } else {
                        logMessage("Tentative de connexion échouée pour l'utilisateur avec le login : $login", 'error');
                        $_SESSION['message'] = "Échec de connexion. Le mot de passe est incorrect.";
                        $_SESSION['couleur'] = false;
                        header('Location: ../PHP/form_connexion_inscription.php');
                        exit();
                    }
                } else {
                    logMessage("Le login n'existe pas dans la base de données.", 'error');
                    $_SESSION['message'] = "Échec de connexion. Login incorrect.";
                    $_SESSION['couleur'] = false;
                    header('Location: ../PHP/form_connexion_inscription.php');
                    exit();
                }
                // Fermeture de la connexion à la base de données
                mysqli_close($connection);
            } else {
                logMessage("Tentative de connexion. Champs manquants", 'error');
                $_SESSION['message'] = "Échec de connexion. Veuillez remplir tous les champs.";
                $_SESSION['couleur'] = false;
                header('Location: ../PHP/form_connexion_inscription.php');
                exit();
            }
        }else{
            logMessage("Tentative de connexion. Captcha incorrect. Veuillez réessayer.", 'error');
            $_SESSION['message'] = "Captcha incorrect. Veuillez réessayer.";
            $_SESSION['couleur'] = false;
            header('Location: ../PHP/form_connexion_inscription.php');
            exit();
        }
    }else{
        logMessage("Tentative de connexion. Veuillez remplir le captcha.", 'error');
        $_SESSION['message'] = "Veuillez remplir le captcha.";
        $_SESSION['couleur'] = false;
        header('Location: ../PHP/form_connexion_inscription.php');
        exit();
    }
}
?>