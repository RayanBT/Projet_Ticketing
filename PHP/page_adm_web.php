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
    <title>Page admin web</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_tableau.css" rel="stylesheet">
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
                <a href="form_creation_technicien.php"><i class="fa fa-plus-circle"></i> &nbsp; Création compte technicien</a>
            </li>
            <li>
                <a href="page_adm_web_traitement_ticket.php"><i class="fa fa-cogs"></i> &nbsp; Traitement ticket</a>
            </li>
            <li>
                <a href="page_creation_libelle.php"><i class="fa fa-tag"></i> &nbsp; Gestion libellé</a>
            </li>
            <li>
                <a href="#tickets-ouverts"><i class="fa fa-ticket"></i> &nbsp; Tickets Ouverts</a>
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
            <h3 id="tickets-ouverts">Tickets ouverts :</h3>
            <br>
            <?php

            // Utilisation d'une requête préparée pour éviter les injections SQL
            $login = $_SESSION['login'];
            $query = "SELECT t.id_ticket as Id, t.Login as login, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut , t.technicien as Technicien
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE t.statut!=? and technicien='Personne'";
            $stmt = mysqli_prepare($connection, $query);
            $statut = "fermé";
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $statut);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

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
            } else {
                echo "Erreur lors de la préparation de la requête.";
            }

            // Requête pour obtenir tous les utilisateurs ayant le rôle "technicien"
            $queryTechniciens = "SELECT login FROM user WHERE user_role = 'technicien'";
            $resultTechniciens = mysqli_query($connection, $queryTechniciens);

            // Vérifiez s'il y a des techniciens
            if ($resultTechniciens && mysqli_num_rows($resultTechniciens) > 0) {
                // Pour chaque technicien, récupérez les tickets qu'il a pris en charge
                while ($rowTechnicien = mysqli_fetch_assoc($resultTechniciens)) {
                    $technicienLogin = $rowTechnicien['login'];

                    // Requête pour obtenir les tickets attribués à un technicien spécifique
                    $queryTicketsTechnicien = "SELECT t.id_ticket as Id, t.Login as login, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE technicien = ?";
                    $stmtTicketsTechnicien = mysqli_prepare($connection, $queryTicketsTechnicien);

                    if ($stmtTicketsTechnicien) {
                        mysqli_stmt_bind_param($stmtTicketsTechnicien, "s", $technicienLogin);
                        mysqli_stmt_execute($stmtTicketsTechnicien);
                        $resultTicketsTechnicien = mysqli_stmt_get_result($stmtTicketsTechnicien);

                        // Affiche un titre pour chaque technicien
                        echo "<h3>Tickets pris en charge par le technicien $technicienLogin :</h3>";
                        echo "<br>";

                        // Affiche un tableau pour les tickets pris en charge par le technicien
                        echo "<table style='width: 100%; height: 400px; text-align: center'>";
                        // Affiche les en-têtes de colonnes
                        echo "<tr>";
                        if ($resultTicketsTechnicien && mysqli_num_rows($resultTicketsTechnicien) > 0) {
                            $rowTicketsTechnicien = mysqli_fetch_assoc($resultTicketsTechnicien);
                            foreach ($rowTicketsTechnicien as $key => $value) {
                                echo "<th>$key</th>";
                            }
                            echo "</tr>";

                            // Affiche les données de chaque ligne
                            do {
                                echo "<tr>";
                                foreach ($rowTicketsTechnicien as $value) {
                                    echo "<td>$value</td>";
                                }
                                echo "</tr>";
                            } while ($rowTicketsTechnicien = mysqli_fetch_assoc($resultTicketsTechnicien));
                        } else {
                            // Si aucune donnée, générer une ligne vide
                            echo "<tr>";
                            echo "<td colspan='6'>Aucun ticket pris en charge.</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "Erreur lors de la préparation de la requête pour les tickets pris en charge par le technicien $technicienLogin.";
                    }
                }
            } else {
                echo "Aucun technicien trouvé.";
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

