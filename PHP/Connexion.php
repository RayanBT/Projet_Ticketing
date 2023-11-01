<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Vérifie si le formulaire a été soumis
if (isset($_POST["connexion"])){
    $host = "localhost";
    $user = "root";
    $password = "";
    if (!empty(!empty($_POST['email']) and !empty($_POST['mot_de_passe']))) {
        // Récupérez l'e-mail et le mot de passe saisis dans le formulaire de connexion
        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"];

// Connexion à la base de données
        $connection = mysqli_connect($host, $user, $password) or die("Erreur de connexion à la base de données");
        $namedb = "BD_Ticketing";
        $db = mysqli_select_db($connection, $namedb) or die("Erreur de sélection de la base de données");
        $tab = "User";

// Requête SQL pour vérifier si l'e-mail existe dans la table User
        $query = "SELECT Mdp FROM $tab WHERE Email = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // L'e-mail existe, maintenant vérifiez le mot de passe
            mysqli_stmt_bind_result($stmt, $hashed_password);
            mysqli_stmt_fetch($stmt);

            // Vérifiez le mot de passe (en MD5)
            if (md5($mot_de_passe) === $hashed_password) {
                echo "Connexion réussie. Vous êtes maintenant connecté.";
            } else {
                echo "Mot de passe incorrect. Veuillez réessayer.";
            }
        } else {
            echo "L'adresse e-mail n'existe pas dans la base de données. Veuillez vous inscrire.";
        }

        // Fermeture de la connexion à la base de données
        mysqli_close($connection);
    }
}


?>
