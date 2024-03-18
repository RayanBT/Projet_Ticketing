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

$connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

// Récupérer les informations de l'utilisateur depuis la base de données
$login = $_SESSION['login'];
$query = "SELECT * FROM user WHERE login = '$login'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Erreur lors de la récupération des informations de l'utilisateur.");
}

$userData = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style_user.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap">
    <style>

        body{
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #e3e9f7;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }
        .page {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
        }

        .cadre {
            background-color: transparent; /* Couleur de fond du cadre */
            padding: 20px;
            border-radius: 10px; /* Coins arrondis */
            box-shadow: 0 5px 15px #307191;
            text-align: center;
            justify-content: center;
        }



        .change_mdp {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4caf50; /* Couleur de fond du bouton */
            color: #fff; /* Couleur du texte du bouton */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .change_mdp:hover {
            background-color: #45a049; /* Couleur de fond du bouton au survol */
        }

        .cadre {
            display: flex;
            align-items: center;
        }



        .p_user {
            margin-top: 20px;
        }

    </style>
</head>
<body>
<div class="page">
    <nav>
        <ul>
            <li class="logo"><img alt="logo de Rayan Ticket" src="../IMG/Proposition_logo_1.png"></li>
            <li>
                <i><h3 class="role">Role: <?php echo $_SESSION['user_role']; ?></h3></i>
            </li>
            <li>
                <a href="authentification.php"><i class="fa fa-home"></i> &nbsp; Accueil</a>
            </li>
            <li>
                <a href="ChangePassword.php" class="bouton"><i class="fa fa-user"></i> &nbsp; Changer le mot de passe</a>
            </li>
            <a class="deconnexion" href="../PHP/Deconnexion.php" class="bouton"><i class="fa fa-sign-out"></i> Déconnexion</a>
        </ul>
    </nav>
    <div class="corps">
        <main>
            <h3 class="phrase_acceuil">Bienvenue, <?php echo $userData['nom']; ?>!</h3>
            <div class="cadre">
                <div class="photo-utilisateur" style="width: 25%;">
                    <img src="../IMG/User_IMG.png" alt="Photo de profil" style="width: 100%; height: 100%">
                </div>
                <div class="info-utilisateur">
                    <p style="text-align: center">Voici vos informations:</p><hr style="width: 20%">
                    <strong>Login:</strong> <?php echo $userData['login']; ?><br>
                    <strong>Email:</strong> <?php echo $userData['email']; ?><br>
                    <?php
                    $role = $userData['user_role']; if ($role == "admin_systeme"){$role = "Administrateur Système";}elseif ($role == "admin_web"){$role = "Administrateur Web";}
                    ?>
                    <strong>Droit:</strong> <?php echo $role; ?><br>
                </div>
            </div>

            <?php
            // Afficher le paragraphe explicatif en fonction du rôle
            if ($role == "utilisateur") {
                echo "<p class='p_user'>En tant qu'$role, vous avez les droits suivants :</p>";
                echo "<ul class='p_user'>";
                echo "<li>Faire une demande de dépannage en ouvrant un ticket.</li>";
                echo "<li>Accéder à votre tableau de bord pour voir la liste des tickets publiés et leur état.</li>";
                echo "<li>Accéder à votre profil pour changer votre mot de passe.</li>";
                echo "</ul>";
            } elseif ($role == "technicien") {
                echo "<p class='p_user'>En tant qu'$role, vous avez les droits suivants :</p>";
                echo "<ul class='p_user'>";
                echo "<li>Peuvent s'attribuer un ticket.</li>";
                echo "<li>Changent l'état du ticket une fois pris en charge.</li>";
                echo "<li>Peuvent clôturer un ticket une fois le problème résolu.</li>";
                echo "</ul>";

                // Ajoutez ici les droits spécifiques pour les techniciens
            } elseif ($role == "Administrateur Web") {
                echo "<p class='p_user'>En tant qu'$role, vous avez les droits suivants :</p>";
                echo "<ul class='p_user'>";
                echo "<li>Gère la liste des libellés pour les problèmes rencontrés dans les salles informatiques.</li>";
                echo "<li>Définit les statuts des tickets : ouvert, en cours de traitement, fermé.</li>";
                echo "<li>Définit les niveaux d'urgence pour les tickets (faible, moyen, important, urgent).</li>";
                echo "<li>Crée les comptes des techniciens.</li>";
                echo "<li>Visualise les tickets ouverts et peut les attribuer à un technicien.</li>";
                echo "</ul>";
            } elseif ($role == "Administrateur Système") {
                echo "<p class='p_user'>En tant qu'$role, vous avez les droits suivants :</p>";
                echo "<ul class='p_user'>";
                echo "<li>Accède aux journaux d'activités de l'application web.</li>";
                echo "<li>Visualise les tickets ouverts et fermés</li>";
                echo "<li>Visualisation des statistiques.</li>";
                echo "</ul>";
            }
            ?>
        </main>
    </div>
</div>
</body>
</html>



