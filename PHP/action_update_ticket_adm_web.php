<?php
session_start();
// Inclure le fichier de configuration des logs
require_once('Config.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "BD_Ticketing";
$table = "tickets";

$connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

if (isset($_POST['valider'])) {
    if (isset($_POST['id_ticket'])){
        $id_tickets = $_POST['id_ticket'];
        if (isset($_POST['statut'])){
            $statuts = $_POST['statut'];
            foreach ($statuts as $key => $value) {
                // Assurez-vous de valider et échapper les données avant de les utiliser dans la requête SQL
                $id_ticket = mysqli_real_escape_string($connection, $id_tickets[$key]);
                $statut = mysqli_real_escape_string($connection, $value);
                // Utilisez une requête UPDATE pour mettre à jour la colonne 'statut'
                $updateQuery = "UPDATE $table SET statut='$statut' WHERE id_ticket='$id_ticket'";
                mysqli_query($connection, $updateQuery);
            }
            logMessage("Les statuts ont été mis à jour avec succès.");
            header('Location: ../PHP/page_adm_web_traitement_ticket.php');
        }

        if (isset($_POST['priorite'])){
            $priorites = $_POST['priorite'];
            foreach ($priorites as $key => $value) {
                // Assurez-vous de valider et échapper les données avant de les utiliser dans la requête SQL
                $id_ticket = mysqli_real_escape_string($connection, $id_tickets[$key]);
                $priorite = mysqli_real_escape_string($connection, $value);
                // Utilisez une requête UPDATE pour mettre à jour la colonne 'statut'
                $updateQuery = "UPDATE $table SET priorite='$priorite' WHERE id_ticket='$id_ticket'";
                mysqli_query($connection, $updateQuery);
            }
            logMessage("Les priorités ont été mis à jour avec succès.");
            header('Location: ../PHP/page_adm_web_traitement_ticket.php');
        }
    }





}


?>