<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";

if (!empty($_POST['sujet']) && !empty($_POST['description']) && !empty($_POST['priorite'])) {
    $login = $_SESSION['login'];
    $sujet = $_POST['sujet'];
    $description = $_POST['description'];
    $priorite = $_POST['priorite'];

    // Connexion à la base de données
    $connection = mysqli_connect($host, $user, $password, "BD_Ticketing") or die("Erreur de connexion à la base de données");
    $tab = "tickets";

    // Préparation de la requête SQL avec des paramètres
    $query = "INSERT INTO $tab (login, sujet, description, priorite, date_creation) VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($connection, $query);

    // Liaison des valeurs aux paramètres dans la requête préparée
    mysqli_stmt_bind_param($stmt, "ssss", $login, $sujet, $description, $priorite);

    // Exécution de la requête préparée
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        // Redirection vers utilisateur.php si l'insertion est réussie
        header("Location: authentification.php");
        exit(); // Assurez-vous d'utiliser exit() après la redirection pour arrêter l'exécution du script
    } else {
        echo "Erreur lors de l'insertion des données : " . mysqli_error($connection);
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connection);
}
?>
