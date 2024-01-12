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
    <link href="../CSS/style_page_adm_traitement_ticket.css" rel="stylesheet">
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
                <a href="form_creation_technicien.php"><i class="fa fa-plus-circle"></i> &nbsp; Création compte technicien</a>
            </li>
            <li>
                <a href="page_adm_web_traitement_ticket.php"><i class="fa fa-cogs"></i> &nbsp; Traitement ticket</a>
            </li>
            <li>
                <a href="page_adm_web.php#hisorique-ticket"><i class="fa fa-ticket"></i> &nbsp; Historique de ticket</a>
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
            <h3 class="phrase_acceuil">Page traitement ticket</h3>
            <br>
            <br>
            <h3 id="hisorique-ticket"> Tickets:</h3>
            <br>
            <?php
            $host = "localhost";
            $user = "root";
            $password = "";
            $connection = mysqli_connect($host, $user, $password) or die("Erreur de connexion à la base de données");
            $namedb = "BD_Ticketing";
            $db = mysqli_select_db($connection, $namedb) or die("Erreur de sélection de la base de données");
            $tab = "tickets";


            $query = "SELECT t.id_ticket as Id, t.Login as login, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut, t.technicien as Technicien 
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    ORDER BY statut ASC";
            //$query = "SELECT id_ticket, sujet, login, DATE_FORMAT(date_creation, '%d/%m/%Y') as date_creation, priorite, statut, technicien FROM $tab ORDER BY statut ASC";
            $result = mysqli_query($connection, $query);

            if ($result) {
                echo "<form method='post' action='action_update_ticket_adm_web.php'>";
                echo "<table style='width: 100%; height: 400px; text-align: center'>";
                echo "<tr>";

                // Affiche les en-têtes de colonnes
                echo "<th>Libellé</th>";
                echo "<th>Créé par</th>";
                echo "<th>Date de création</th>";
                echo "<th>Niveau d'urgence</th>";
                echo "<th>Statut</th>";
                echo "<th>Assigné à</th>";
                echo "</tr>";

                // Affiche les données de chaque ligne
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";

                    //Partie pour afficher le libelle
                    $queryLibelles = "SELECT id_libelle, libelle FROM libelle_ticket";
                    $resultLibelles = mysqli_query($connection, $queryLibelles);

                    if ($resultLibelles) {
                        $libelles = array();
                        while ($rowLibelle = mysqli_fetch_assoc($resultLibelles)) {
                            $libelles[] = $rowLibelle;
                        }
                    } else {
                        echo "Erreur lors de la récupération des libellés de la base de données.";
                    }
                    echo "<td>";
                    echo "<select name='libelle[]'>";
                    foreach ($libelles as $libelle) {
                        $selectedLibelle = ($row['Libelle'] == $libelle['libelle']) ? "selected" : "";
                        echo "<option value='{$libelle['id_libelle']}' $selectedLibelle>{$libelle['libelle']}</option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>" . $row['login'] . "</td>";
                    echo "<td>". $row['date_creation'] ."</td>"; // Ajoutez la colonne 'date_creation' à votre table 'tickets'
                    echo "<td>";
                    echo "<select name='priorite[]'>";
                    $enumValuesPriorite = array("Faible", "Moyen", "Important", "Urgent");
                    // Affiche chaque valeur dans le menu déroulant
                    foreach ($enumValuesPriorite as $value) {
                        $selectedPriorite = ($row['Priorité'] == $value) ? "selected" : "";
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
                        $selected = ($row['Statut'] == $value) ? "selected" : "";
                        echo "<option value='$value' $selected>$value</option>";
                    }
                    echo "</select>";
                    echo "</td>";

                    echo "<td>";
                    echo "<select name='assigne[]'>";
                    // Ajoutez une option par défaut
                    echo "<option value='Personne'>Non assigné</option>";

                    // Utilisez une fonction pour récupérer les utilisateurs techniciens
                    $techniciens = getTechniciens($connection);

                    foreach ($techniciens as $technicien) {
                        $selectedAssignation = ($row['Technicien'] == $technicien['login']) ? "selected" : "";
                        echo "<option value='{$technicien['login']}' $selectedAssignation>{$technicien['login']}</option>";
                    }

                    echo "</select>";
                    echo "</td>";

                    // Ajoutez un champ caché pour l'ID du ticket
                    echo "<input type='hidden' name='id_ticket[]' value='" . $row['Id'] . "'>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "<input type='submit' name='valider' value='valider'>";
                echo "</form>";
            } else {
                echo "Erreur lors de la récupération des données de la base de données.";
            }


            function getTechniciens($connection) {
                $queryTechniciens = "SELECT login FROM user WHERE user_role = 'technicien'";
                $resultTechniciens = mysqli_query($connection, $queryTechniciens);

                $techniciens = array();
                while ($rowTechnicien = mysqli_fetch_assoc($resultTechniciens)) {
                    $techniciens[] = $rowTechnicien;
                }

                return $techniciens;
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

