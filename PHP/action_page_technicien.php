<?php
session_start();

require_once('fonction_connexion_bd.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

if (isset($_POST['valider_tickets']) && isset($_POST['tickets'])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "BD_Ticketing";
    $table = "tickets";

    $connection = connectToDatabase($host, $user, $password, $database);

    $login_technicien = $_SESSION['login'];
    $tickets_selected = $_POST['tickets'];

    // Mettre à jour les tickets avec le login du technicien
    $update_query = "UPDATE $table SET Technicien = ?, Statut = 'En cours' WHERE id_ticket IN (" . implode(',', $tickets_selected) . ")";
    $stmt_update = mysqli_prepare($connection, $update_query);




    if ($stmt_update) {
        mysqli_stmt_bind_param($stmt_update, 's', $login_technicien);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);

        $_SESSION['message'] = "Mise à jour des tickets réussie.";
        $_SESSION['couleur'] = true;
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour des tickets.";
        $_SESSION['couleur'] = false;
    }

    header('Location: page_technicien.php');
    exit();
} else {
    header('Location: page_technicien.php');
    exit();
}
?>
