<?php
session_start();
require_once 'Config.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}
$host = "localhost";
$user = "root";
$password = "";
$database = "BD_Ticketing";

$connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");
// Vérifie le type de tickets à afficher
$type = isset($_GET['type']) ? $_GET['type'] : 'ouverts';
if ($type == 'ouverts') {
    $table = "tickets";
} else {
    $table = "tickets_close";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_user.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
    <link href="../CSS/style_page_adm_systeme.css" rel="stylesheet">
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
                <a href=""><i class="fa fa-bar-chart"></i> &nbsp; Application statistique</a>
            </li>
            <li>
                <a href="page_adm_systeme_log.php"><i class="fa fa-history"></i> &nbsp; Aperçu des logs</a>
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
            <h3 class="phrase_acceuil">Bienvenue sur la page de l'administrateur système.</h3>
            <br>
            <br>
            <h3 id="tickets-ouverts">Tickets <?php echo $type; ?>:</h3>
            <br>
            <?php




            // Utilisation d'une requête préparée pour éviter les injections SQL
            $query = "SELECT t.id_ticket as Id, t.Login as login, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'date_creation', t.statut as Statut 
                    FROM $table t
                    LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
                    WHERE t.statut!=?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                // Lie le paramètre pour le type de ticket
                mysqli_stmt_bind_param($stmt, "s", $type);

                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                echo "<table style='width: 100%; height: 400px; text-align: center'>";
                echo "<tr>";

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    // Affiche les en-têtes de colonnes
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
                logMessage("Erreur lors de la préparation de la requête.");
            }
            ?>
            <br>
            <br>
            <div style="text-align: right">
                <!-- Ajout de liens pour changer entre les tickets ouverts et fermés -->
                <a href="?type=<?php echo $type == 'ouverts' ? 'fermes' : 'ouverts'; ?>" class="arrow-link left">&#8592;</a>
                <a href="?type=<?php echo $type == 'ouverts' ? 'fermes' : 'ouverts'; ?>" class="arrow-link right">&#8594;</a>
            </div>

        </main>
    </div>
</div>
</body>
</html>
