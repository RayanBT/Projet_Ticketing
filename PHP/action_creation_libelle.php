<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";

if (isset($_POST['creation_libelle'])) {
    $login = $_SESSION['login'];

    if (isset($_POST['libelle'])) {
        $libelle = $_POST['libelle'];

        // Vérification si le libellé n'est pas vide
        if (!empty($libelle)) {

            // Connexion à la base de données
            $connection = mysqli_connect($host, $user, $password, "BD_Ticketing") or die("Erreur de connexion à la base de données");
            $tab = "libelle_ticket";

            // Préparation de la requête SQL avec des paramètres
            $query = "INSERT INTO $tab (libelle) VALUES (?)";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                // Liaison des valeurs aux paramètres dans la requête préparée
                mysqli_stmt_bind_param($stmt, "s", $libelle);

                // Exécution de la requête préparée
                $success = mysqli_stmt_execute($stmt);

                if ($success) {
                    $_SESSION['message'] = "Le libellé '$libelle' a bien été créé.";
                    $_SESSION['couleur'] = true;
                    // Redirection vers utilisateur.php si l'insertion est réussie
                    header("Location: authentification.php");
                    exit(); // Assurez-vous d'utiliser exit() après la redirection pour arrêter l'exécution du script
                } else {
                    $_SESSION['message'] = "Erreur lors de l'insertion des données : " . mysqli_error($connection);
                    $_SESSION['couleur'] = false;
                }

                // Fermeture du statement
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['message'] = "Erreur lors de la préparation de la requête.";
                $_SESSION['couleur'] = false;
            }

            // Fermeture de la connexion à la base de données
            mysqli_close($connection);
        } else {
            $_SESSION['message'] = "Le champ Libellé ne peut pas être vide.";
            $_SESSION['couleur'] = false;
        }
    } else {
        $_SESSION['message'] = "Le champ Libellé est requis.";
        $_SESSION['couleur'] = false;
    }
} else {
    $_SESSION['message'] = "Le formulaire n'a pas été soumis correctement.";
    $_SESSION['couleur'] = false;
}

// Redirection vers la page de création de compte technicien avec un message
header("Location: page_creation_compte_technicien.php");
exit();
?>
