<?php
session_start();

require_once('Config.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";

if (isset($_POST['captcha']) && isset($_SESSION['captcha'])) {
    $userAnswer = intval($_POST['captcha']);
    $correctAnswer = $_SESSION['captcha'];

    if ($userAnswer === $correctAnswer) {
        if (!empty($_POST['libelle']) && !empty($_POST['description']) && !empty($_POST['priorite'])) {
            $login = $_SESSION['login'];
            $description = $_POST['description'];
            $salle = $_POST['salle'];
            $ip = $_POST['ip'];
            $priorite = $_POST['priorite'];
            $libelle_id = $_POST['libelle'];

            // Connexion à la base de données
            $connection = mysqli_connect($host, $user, $password, "BD_Ticketing") or die("Erreur de connexion à la base de données");
            $tab = "tickets";

            // Préparation de la requête SQL avec des paramètres
            $query = "INSERT INTO $tab (login, id_libelle, description, salle, ip, priorite, date_creation) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($connection, $query);

            // Liaison des valeurs aux paramètres dans la requête préparée
            mysqli_stmt_bind_param($stmt, "ssssss", $login, $libelle_id, $description, $salle, $ip, $priorite);

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
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    } else {
        logMessage("Tentative de connexion. Captcha incorrect. Veuillez réessayer.", 'error');
        $_SESSION['message'] = "Captcha incorrect. Veuillez réessayer.";
        $_SESSION['couleur'] = false;
        header('Location: ../PHP/form_creation_ticket.php');
        exit();
    }
} else {
    logMessage("Tentative de connexion. Veuillez remplir le captcha.", 'error');
    $_SESSION['message'] = "Veuillez remplir le captcha.";
    $_SESSION['couleur'] = false;
    header('Location: ../PHP/form_creation_ticket.php');
    exit();
}
?>
