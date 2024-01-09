<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/form_connexion_inscription.php");
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
    <link href="../CSS/style_page_adm_traitement_ticket.css" rel="stylesheet">
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <a href="accueil.php"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <a href="form_creation_ticket.php"><i class="fa fa-plus-circle"></i> &nbsp; Créer un ticket</a>
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
            <h3 class="phrase_acceuil">Page traitement ticket</h3>
            <br>
            <br>
            <h3 id="hisorique-ticket"> Ticket ouvert :</h3>
            <br>
            <?php
            $host = "localhost";
            $user = "root";
            $password = "";
            $connection = mysqli_connect($host, $user, $password) or die("Erreur de connexion à la base de données");
            $namedb = "BD_Ticketing";
            $db = mysqli_select_db($connection, $namedb) or die("Erreur de sélection de la base de données");
            $tab = "tickets";


            // Utilisation d'une requête SQL simple pour sélectionner les 10 derniers tickets
            $query = "SELECT id_ticket, sujet, login, DATE_FORMAT(date_creation, '%d/%m/%Y') as date_creation, priorite, statut FROM $tab ORDER BY statut ASC";
            $result = mysqli_query($connection, $query);

            if ($result) {
                echo "<form method='post' action='update_statut.php'>";
                echo "<table style='width: 400px; height: 400px'>";
                echo "<tr>";

                // Affiche les en-têtes de colonnes
                echo "<th>Problème</th>";
                echo "<th>Créé par</th>";
                echo "<th>Date de création</th>";
                echo "<th>Niveau d'urgence</th>";
                echo "<th>Statut</th>";
                echo "</tr>";

                // Affiche les données de chaque ligne
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['sujet'] . "</td>";
                    echo "<td>" . $row['login'] . "</td>";
                    echo "<td>". $row['date_creation'] ."</td>"; // Ajoutez la colonne 'date_creation' à votre table 'tickets'
                    echo "<td>";
                    echo "<select name='priorite[]'>";
                    $enumValuesPriorite = array("Faible", "Moyen", "Important", "Urgent");
                    // Affiche chaque valeur dans le menu déroulant
                    foreach ($enumValuesPriorite as $value) {
                        $selectedPriorite = ($row['priorite'] == $value) ? "selected" : "";
                        echo "<option value='$value' $selectedPriorite>$value</option>";
                    }
                    echo "</select>";
                    echo "</td>";

                    // Génère le menu déroulant pour la colonne 'statut' avec les valeurs ENUM
                    echo "<td>";
                    echo "<select name='statut[]'>";
                    $enumValues = array("Ouvert", "En cours", "Fermé");

                    // Affiche chaque valeur dans le menu déroulant
                    foreach ($enumValues as $value) {
                        $selected = ($row['statut'] == $value) ? "selected" : "";
                        echo "<option value='$value' $selected>$value</option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    // Ajoutez un champ caché pour l'ID du ticket
                    echo "<input type='hidden' name='id_ticket[]' value='" . $row['id_ticket'] . "'>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "<input type='submit' name='valider' value='valider'>";
                echo "</form>";
            } else {
                echo "Erreur lors de la récupération des données de la base de données.";
            }
            ?>

            <br>
            <br>
        </main>

    </div>
</div>
</body>
</html>

