<?php
session_start();

if (!isset($_SESSION['login'])) {
    $message = "Vous devez être connecté pour accéder à cette page.";
    header("Location: ../PHP/Deconnexion.php?erreur=$message");
    exit();
}
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
                <a href="#hisorique-ticket"><i class="fa fa-ticket"></i> &nbsp; Historique de ticket</a>
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
            <h3 id="hisorique-ticket">Historique de ticket :</h3>
            <br>
            <?php

            // Utilisation d'une requête préparée pour éviter les injections SQL
            $login = $_SESSION['login'];
            $query = "SELECT t.id_ticket as Id, t.Login as Crée_par, lt.libelle as Libelle, t.priorite as Priorité, DATE_FORMAT(t.date_creation, '%d/%m/%Y') as 'Date création', t.statut as Statut 
          FROM $table t
          LEFT JOIN libelle_ticket lt ON t.id_libelle = lt.id_libelle
          WHERE t.login=?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $login);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                echo "<table style='width: 100%; text-align: center'>";

// Affiche les en-têtes de colonnes
                echo "<tr>";

                if ($result && mysqli_num_rows($result) > 0) {
                    $headerPrinted = false;
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Affiche les en-têtes de colonnes une seule fois
                        if (!$headerPrinted) {
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
                    // Affiche les en-têtes de colonnes même si aucun résultat n'est retourné
                    echo "<th>Id</th>";
                    echo "<th>Crée par</th>";
                    echo "<th>Sujet</th>";
                    echo "<th>Niveau d'urgence</th>";
                    echo "<th>Date de création</th>";
                    echo "<th>Statut</th>";
                    echo "<th>Technicien en charge</th>";
                    echo "</tr>";

                    // Génère des lignes vides
                    for ($i = 0; $i < 5; $i++) {
                        echo "<tr>";
                        echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }

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
