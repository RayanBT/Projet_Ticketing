<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Vérifie si le formulaire a été soumis
if (isset($_POST["inscription"])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    if (!empty($_POST['nom']) and !empty($_POST['email']) and !empty($_POST['mot_de_passe'])) {
        // Récupère la valeur de l'input nom et la stocke dans la variable $nom
        $nom = $_POST["nom"];

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

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "L'adresse e-mail existe déjà. Veuillez utiliser une autre adresse e-mail.";
        } else {
            // Requête SQL correcte avec des marqueurs de paramètres
            $requete = "INSERT INTO `User` (`id_User`,`Nom`, `Email`, `Mdp`) VALUES (NULL,?, ?, MD5(?))";

            // Préparation de la requête
            $reqprepare = mysqli_prepare($connection, $requete);

            if (!$reqprepare) {
                die("Erreur de préparation de la requête : " . mysqli_error($connection));
            }else{
                mysqli_stmt_bind_param($reqprepare, 'sss', $nom, $email, $mot_de_passe);

                // Exécution de la requête préparée
                $result = mysqli_stmt_execute($reqprepare);

                if ($result) {
                    echo "Inscription réussie.";
                } else {
                    echo "Erreur d'inscription : " . mysqli_error($connection);
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
    echo "Adresse mail : " . $email . "<br>";
    echo "Mot de passe : " . $mot_de_passe . "<br>";
}
?>

