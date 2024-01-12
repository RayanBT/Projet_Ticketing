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
    <title>Page technicien</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_page_adm_traitement_ticket.css" rel="stylesheet">
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
                <a href="#tickets-disponible"><i class="fa fa-list-ul"></i> &nbsp; Tickets disponible</a>
            </li>
            <li>
                <a href="#tickets-attribues"><i class="fa fa-ticket"></i> &nbsp; Tickets attribués</a>
            </li>
            <li>
                <a href="profil.php"><i class="fa fa-user"></i> &nbsp; Profil</a>
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
            <h3 id="tickets-disponible">Ticket Disponible :</h3>
            <br>
            <?php

            // Utilisation d'une requête préparée pour éviter les injections SQL
            $login = $_SESSION['login'];
            $query = "SELECT t.id_ticket as Id, t.Login as login, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut , t.technicien as Technicien
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE t.statut=? or t.technicien='Personne'";
            $stmt = mysqli_prepare($connection, $query);
            $Statut = "Ouvert";
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $Statut);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                echo "<form action='action_page_technicien.php' method='POST'>";
                echo "<table style='width: 100%; height: 400px; text-align: center'>";

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
                        echo "<td><input type='checkbox' name='tickets[]' value='{$row['Id']}' class='styled-checkbox'></td>";
                        echo "</tr>";
                    } while ($row = mysqli_fetch_assoc($result));
                } else {
                    echo "<tr>";
                    // Affiche les en-têtes de colonnes
                    echo "<th>Id</th>";
                    echo "<th>Crée par</th>";
                    echo "<th>Sujet</th>";
                    echo "<th>Niveau d'urgence</th>";
                    echo "<th>Date de création</th>";
                    echo "<th>Statut</th>";
                    echo "<th>Technicien en charge</th>";
                    echo "</tr>";
                    // Si aucune donnée, générer des lignes vides
                    for ($i = 0; $i < 5; $i++) {
                        echo "<tr>";
                        echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                        echo "</tr>";
                    }
                }

                echo "</table><br>";
                echo "<input type='submit' name='valider_tickets' value='Valider la sélection'>";
                echo "</form>";
            } else {
                echo "Erreur lors de la préparation de la requête.";
            }

            ?>

            <br>
            <br>

            <h3 id="tickets-attribues">Tickets attribués :</h3>
            <br>
            <?php

            // Utilisation d'une requête préparée pour éviter les injections SQL
            $login = $_SESSION['login'];
            $query = "SELECT t.id_ticket as Id, t.Login as 'Crée par', lt.libelle as Libelle, t.priorite as 'Niveau urgence', DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut , t.technicien as Technicien
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE technicien=?";
            $stmt = mysqli_prepare($connection, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $login);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                echo "<form action='action_page_technicien.php' method='POST'>";
                echo "<table style='width: 100%; height: 400px; text-align: center'>";

                // Affiche les en-têtes de colonnes
                echo "<tr>";
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    foreach ($row as $key => $value) {
                        echo "<th>$key</th>";
                    }
                    echo "</tr>";

                    // Affiche les données de chaque ligne avec une cellule cliquable pour chaque ticket
                    do {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            if ($key === 'Id') {
                                echo "<td><a href='page_details_ticket.php?id={$row['Id']}'>$value</a></td>";
                            } else {
                                echo "<td>$value</td>";
                            }
                        }
                        echo "</tr>";
                    } while ($row = mysqli_fetch_assoc($result));

                } else {
                    echo "<tr>";
                    // Affiche les en-têtes de colonnes
                    echo "<th>Id</th>";
                    echo "<th>Crée par</th>";
                    echo "<th>Sujet</th>";
                    echo "<th>Niveau d'urgence</th>";
                    echo "<th>Date de création</th>";
                    echo "<th>Statut</th>";
                    echo "<th>Technicien en charge</th>";
                    echo "</tr>";

                    // Si aucune donnée, générer des lignes vides
                    for ($i = 0; $i < 5; $i++) {
                        echo "<tr>";
                        echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                        echo "</tr>";
                    }
                }

                echo "</table>";
                echo "</form>";
            } else {
                echo "Erreur lors de la préparation de la requête.";
            }

            ?>
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

