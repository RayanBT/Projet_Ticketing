<?php
function connectToDatabase($host, $user, $password, $database) {
    $connection = mysqli_connect($host, $user, $password, $database);
    if (!$connection) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }
    return $connection;
}
?>