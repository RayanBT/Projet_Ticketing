<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";

if (!empty($_POST['libelle'])) {
    $login = $_SESSION['login'];
    $libelle = $_POST['libelle'];

    // Connexion à la base de données
    $connection = mysqli_connect($host, $user, $password, "BD_Ticketing") or die("Erreur de connexion à la base de données");
    $tab = "libelle_ticket";

    // Préparation de la requête SQL avec des paramètres
    $query = "INSERT INTO $tab (libelle) VALUES (?)";
    $stmt = mysqli_prepare($connection, $query);

    // Liaison des valeurs aux paramètres dans la requête préparée
    mysqli_stmt_bind_param($stmt, "s", $libelle);

    // Exécution de la requête préparée
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        logMessage("Le libellé a bien été crée.");
        $_SESSION['message'] = "Le libellé '$libelle' a bien été crée.";
        $_SESSION['couleur'] = true;
        // Redirection vers utilisateur.php si l'insertion est réussie
        header("Location: authentification.php");
        exit(); // Assurez-vous d'utiliser exit() après la redirection pour arrêter l'exécution du script
    } else {
        echo "Erreur lors de l'insertion des données : " . mysqli_error($connection);
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connection);
}else{
    echo "Veuillez remplir tous les champs.";
}
?>
