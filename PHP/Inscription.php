<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Inclure le fichier de configuration des logs
require_once('config.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Vérifie si le formulaire a été soumis
if (isset($_POST["inscription"])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    if (!empty($_POST['nom']) and !empty($_POST['login']) and !empty($_POST['email']) and !empty($_POST['mot_de_passe'])) {
        // Récupère la valeur de l'input nom et la stocke dans la variable $nom
        $nom = $_POST["nom"];

        // Récupère la valeur de l'input login et la stocke dans la variable $login
        $login = $_POST["login"];

        // Récupère la valeur de l'input email et la stocke dans la variable $email
        $email = $_POST["email"];

        // Récupère la valeur de l'input mot_de_passe et la stocke dans la variable $mot_de_passe
        $mot_de_passe = $_POST["mot_de_passe"];

        /* connection serveur BD */
        $connection = mysqli_connect($host, $user, $password) or die("erreur");
        $namedb = "BD_Ticketing";
        $db = mysqli_select_db($connection, $namedb) or die("erreur");
        $tab = "User";

        // Requête préparée pour vérifier si l'e-mail existe déjà
        $query = "SELECT * FROM $tab WHERE Email = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Requête préparée pour vérifier si le login existe déjà
        $query2 = "SELECT * FROM $tab WHERE Login = ?";
        $stmt2 = mysqli_prepare($connection, $query2);
        mysqli_stmt_bind_param($stmt2, 's', $login);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_store_result($stmt2);

        if (mysqli_stmt_num_rows($stmt) > 0 and mysqli_stmt_num_rows($stmt2) > 0) {
            $_SESSION['inscription_message'] = 'Échec inscription. Adresse e-mail et le login existent déjà.';
            $_SESSION['inscription_reussie'] = false;
            header('Location: ../HTML/Connexion_Inscription.php');
            exit();
        } elseif (mysqli_stmt_num_rows($stmt) > 0){
            $_SESSION['inscription_message'] = 'Échec inscription. Adresse e-mail existent déjà.';
            $_SESSION['inscription_reussie'] = false;
            header('Location: ../HTML/Connexion_Inscription.php');
        } elseif(mysqli_stmt_num_rows($stmt2) > 0){
            $_SESSION['inscription_message'] = 'Échec de inscription. Le login existent déjà.';
            $_SESSION['inscription_reussie'] = false;
            header('Location: ../HTML/Connexion_Inscription.php');
        } else {
            // Requête SQL correcte avec des marqueurs de paramètres
            $requete = "INSERT INTO `User` (`id_User`,`Nom`, `Login`, `Email`, `Mdp`) VALUES (NULL,?, ?, ?, MD5(?))";

            // Préparation de la requête
            $reqprepare = mysqli_prepare($connection, $requete);

            if (!$reqprepare) {
                die("Erreur de préparation de la requête : " . mysqli_error($connection));
            }else{
                mysqli_stmt_bind_param($reqprepare, 'ssss', $nom, $login, $email, $mot_de_passe);

                // Exécution de la requête préparée
                $result = mysqli_stmt_execute($reqprepare);

                if ($result) {
                    logMessage("Inscription réussie pour l'utilisateur avec l'adresse e-mail : $email");
                    $_SESSION['inscription_message'] = "Inscription réussie";
                    $_SESSION['inscription_reussie'] = true;
                    header('Location: ../HTML/Connexion_Inscription.php');
                    exit();
                } else {
                    logMessage("Echec de l'inscription pour l'utilisateur avec l'adresse e-mail : $email", 'error');
                    $_SESSION['inscription_message'] = "Échec inscription";
                    $_SESSION['inscription_reussie'] = false;
                    header('Location: ../HTML/Connexion_Inscription.php');
                    exit();
                }

                // Fermeture de la requête préparée
                mysqli_stmt_close($reqprepare);
            }
        }

        // Fermeture de la connexion à la base de données
        mysqli_close($connection);
    }
    // Affiche les valeurs stockées dans les variables
    echo "Nom : " . $nom . "<br>";
    echo "Login : " . $login . "<br>";
    echo "Adresse mail : " . $email . "<br>";
    echo "Mot de passe : " . $mot_de_passe . "<br>";
}
?>

