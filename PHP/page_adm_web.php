<?php
session_start();

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
?>

!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <a href="authentification.php"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <a href="form_creation_ticket.php"><i class="fa fa-plus-circle"></i> &nbsp; Créer un ticket</a>
            </li>
            <li>
                <a href="form_creation_technicien.php"><i class="fa fa-plus-circle"></i> &nbsp; Création compte technicien</a>
            </li>
            <li>
                <a href="page_adm_web_traitement_ticket.php"><i class="fa fa-cogs"></i> &nbsp; Traitement ticket</a>
            </li>
            <li>
                <a href="#hisorique-ticket"><i class="fa fa-ticket"></i> &nbsp; Historique de ticket</a>
            </li>
            <li>
                <a href="ChangePassword.php"><i class="fa fa-user"></i> &nbsp; Profil</a>
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
            $query = "SELECT id_ticket as Id, login as Login, sujet as Sujet, description as Description, priorite as Priorité, DATE_FORMAT(date_creation, '%d/%m/%Y') as 'Date création', statut as Statut FROM $table WHERE login=?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $login);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                echo "<table style='width: 400px; height: 400px'>";

                // Affiche les en-têtes de colonnes
                echo "<tr>";
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    foreach ($row as $key => $value) {
                        echo "<th>$key</th>";
                    }
                    echo "</tr>";

                    // Affiche les données de chaque ligne
                    do {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    } while ($row = mysqli_fetch_assoc($result));
                } else {
                    // Si aucune donnée, générer des lignes vides
                    for ($i = 0; $i < 10; $i++) {
                        echo "<tr>";
                        echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                        echo "</tr>";
                    }
                }

                echo "</table>";
            } else {
                echo "Erreur lors de la préparation de la requête.";
            }

            ?>

            <br>
            <br>
        </main>
        <script src="../JS/Script.js"></script>

        <?php
        if (isset($_SESSION['message'])) {
            $message = ($_SESSION['message']);
            $couleur = ($_SESSION['couleur']) ? "green" : "red";
            // Appel de la fonction sans inclure à nouveau le script
            echo "<script>afficherVolet('$message', '$couleur');</script>";
            // Vider la session après utilisation
            unset($_SESSION['couleur']);
            unset($_SESSION['message']);
        } else {
            echo "<script>console.log('KO');</script>";
        }
        ?>
    </div>
</div>
</body>
</html>

