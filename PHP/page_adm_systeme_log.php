<?php
session_start();
require_once 'Config.php';
if (!isset($_SESSION['login']) and $_SESSION['user_role'] != "admin_systeme") {
    header("Location: ../PHP/Deconnexion.php");
    exit();
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
            <h3 class="phrase_acceuil">Bienvenue sur la page des logs.</h3>
            <br>
            <br>
            <?php
            // Chemin vers votre fichier de log
            $cheminFichierLog = 'app.log';

            // Nombre de lignes par page
            $nombreLignesParPage = 15;

            // Numéro de page actuel
            $numeroPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

            // Lecture du fichier de log
            $contenuFichier = file($cheminFichierLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Calcul de l'offset
            $offset = ($numeroPage - 1) * $nombreLignesParPage;

            // Récupération des lignes à afficher pour la page actuelle
            $lignesPageCourante = array_slice($contenuFichier, $offset, $nombreLignesParPage);

            // Affichage des lignes
            foreach ($lignesPageCourante as $ligne) {
                echo $ligne . '<hr>';
            }

            // Calcul du nombre total de pages
            $nombreTotalPages = ceil(count($contenuFichier) / $nombreLignesParPage);

            // Affichage des liens de pagination
            echo '<br><div class="pagination" style="text-align: right">';
            if ($numeroPage > 1) {
                echo '<a href="?page=' . ($numeroPage - 1) . '">&#9666; Précédent</a> ';
            }

            echo 'Page ' . $numeroPage . ' de ' . $nombreTotalPages;

            if ($numeroPage < $nombreTotalPages) {
                echo ' <a href="?page=' . ($numeroPage + 1) . '">Suivant &#9656;</a>';
            }
            echo '</div>';
            ?>
        </main>
    </div>


