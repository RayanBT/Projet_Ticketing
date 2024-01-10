<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Acceuil</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="../CSS/style_seconde_proposition.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <a href="#"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <a href="#presentation"><i class="fa fa-question"></i> &nbsp; Qu'est ce que le ticketing ?</a>
            </li>
            <li>
                <a href="#video_explicative"><i class="fa fa-film"></i> &nbsp; Vidéo explicative</a>
            </li>
            <li>
                <a href="#tickets_recents"><i class="fa fa-ticket"></i> &nbsp; Tickets récents</a>
            </li>
            <li>
                <a href="#about"><i class="fa fa-book"></i> &nbsp; à propos</a>
            </li>
            <li>
                <a href="#about"><i class="fa fa-phone"></i> &nbsp; Nous contacter</a>
            </li>
            <li>
                <a href="../PHP/form_connexion_inscription.php" class="bouton"><i class="fa fa-user"></i> Connexion / Inscription</a>
            </li>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <h3 id="presentation">Qu'est ce que le ticketing ?</h3>
            <br>
            <p class="ticketing">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ullamcorper nisl. Suspendisse accumsan mattis est vitae elementum. Fusce non erat quis odio aliquam pellentesque. Sed et quam id nisl laoreet luctus ac non augue. Nunc ut feugiat lectus. Morbi hendrerit eleifend velit eget tempor. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In et magna risus.</p>
            <br>
            <br>
            <h3 id="video_explicative">Vidéo explicative</h3>
            <br>
            <p class="video">Dans cette vidéo vous retrouverez la manière d'utiliser notre plateforme de ticketing Intern</p>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/tRgqdsyNn2Q?si=6PVipEmUvJwfMnDQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <h3 id="tickets_recents">Tickets récents :</h3>
            <br>

            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            $host = "localhost";
            $user = "root";
            $password = "";
            $connection = mysqli_connect($host, $user, $password) or die("Erreur de connexion à la base de données");
            $namedb = "BD_Ticketing";
            $db = mysqli_select_db($connection, $namedb) or die("Erreur de sélection de la base de données");
            $tab = "tickets";


            // Utilisation d'une requête SQL simple pour sélectionner les 10 derniers tickets
            $query = "SELECT sujet, login, DATE_FORMAT(date_creation, '%d/%m/%Y') as date_creation, priorite, statut, technicien FROM $tab ORDER BY id_ticket DESC LIMIT 10";
            $result = mysqli_query($connection, $query);

            if ($result) {
                echo "<table style='width: 100%; height: 400px; text-align: center'>";
            echo "<tr>";

            // Affiche les en-têtes de colonnes
            echo "<th>Problème</th>";
            echo "<th>Crée par</th>";
            echo "<th>Date de création</th>";
            echo "<th>Niveau d'urgence</th>";
            echo "<th>Statut</th>";
            echo "<th>Technicien en charge</th>";
            echo "</tr>";

            // Affiche les données de chaque ligne
            while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['sujet'] . "</td>";
            echo "<td>" . $row['login'] . "</td>";
            echo "<td>". $row['date_creation'] ."</td>";
            echo "<td>" . $row['priorite'] . "</td>";
            echo "<td>" . $row['statut'] . "</td>";
            echo "<td>" . $row['technicien'] . "</td>";
            echo "</tr>";
            }

            echo "</table>";
            } else {
            echo "Erreur lors de la récupération des données de la base de données.";
            }

            ?>

            <br>
            <br>
        </main>

        <script src="../JS/Script.js"></script>

        <?php
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            $couleur = false;
            // Appel de la fonction sans inclure à nouveau le script
            echo "<script>afficherVolet('$message', '$couleur');</script>";
            // Vider la session après utilisation
            unset($_SESSION['couleur']);
            unset($_SESSION['message']);
        } else {
            echo "<script>console.log('KO');</script>";
        }
        ?>



        <footer id="about">
            <div class="card">
                <h4>A propos</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ullamcorper nisl.</p>
            </div>
            <div class="card">
                <h4>Pour nous contacter</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at ullamcorper nisl.</p>
            </div>

        </footer>
    </div>
</div>
</body>
</html>
