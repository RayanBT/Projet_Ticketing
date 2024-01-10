<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page détails ticket</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        .description-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
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
                <a href="page_technicien.php#tickets-disponible"><i class="fa fa-list-ul"></i> &nbsp; Tickets disponible</a>
            </li>
            <li>
                <a href="page_technicien.php#tickets-attribues"><i class="fa fa-ticket"></i> &nbsp; Tickets attribués</a>
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
            <?php
            $host = "localhost";
            $user = "root";
            $password = "";
            $database = "BD_Ticketing";
            $table = "tickets";

            $connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

            if (isset($_GET['id'])) {
                $ticket_id = $_GET['id'];
                $query = "SELECT * FROM $table WHERE id_ticket = ?";
                $stmt = mysqli_prepare($connection, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $ticket_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && $row = mysqli_fetch_assoc($result)) {
                        echo "<h2>Détails du Ticket</h2>";
                        echo "<p><strong>ID du Ticket:</strong> {$row['id_ticket']}</p>";
                        echo "<p><strong>Sujet:</strong> {$row['sujet']}</p>";
                        echo "<p><strong>Description:</strong></p>";
                        echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;'>";
                        echo nl2br($row['description']);
                        echo "</div>";
                    } else {
                        echo "Aucun ticket trouvé avec l'ID spécifié.";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "Erreur lors de la préparation de la requête.";
                }
            } else {
                echo "ID du ticket non spécifié.";
            }

            mysqli_close($connection);
            ?>
        </main>
    </div>
</div>
</body>
</html>
