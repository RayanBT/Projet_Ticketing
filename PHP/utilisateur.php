<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "BD_Ticketing";
$table = "tickets";

$connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <a href="accueil.html"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <a href="form_creation_ticket.php"><i class="fa fa-plus-circle"></i> &nbsp; Créer un ticket</a>
            </li>
            <li>
                <a href="#hisorique-ticket"><i class="fa fa-ticket"></i> &nbsp; Historique de ticket</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-user"></i> &nbsp; Profil</a>
            </li>
            <li>
                <a href="../PHP/Deconnexion.php" class="bouton"><i class="fa fa-sign-out"></i> Déconnexion</a>
            </li>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <h3 class="phrase_acceuil">Bonjour, <?php echo $_SESSION['login']; ?> ravi de vous revoir.</h3>
            <br>
            <br>
            <h3 id="hisorique-ticket">Historique de ticket :</h3>
            <br>
            <?php

            // Utilisation d'une requête préparée pour éviter les injections SQL
            $login = $_SESSION['login'];
            $query = "SELECT * FROM $table WHERE login=?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $login);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result) {
                    echo "<table style='width: 400px; height: 400px'>";
                    echo "<tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Affiche les en-têtes de colonnes une seule fois
                        if (empty($headerPrinted)) {
                            foreach ($row as $key => $value) {
                                echo "<th>$key</th>";
                            }
                            echo "</tr>";
                            $headerPrinted = true;
                        }

                        // Affiche les données de chaque ligne
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Erreur lors de la récupération des données de la base de données.";
                }
            } else {
                echo "Erreur lors de la préparation de la requête.";
            }

            ?>

            <br>
            <br>
        </main>

    </div>
</div>
</body>
</html>
