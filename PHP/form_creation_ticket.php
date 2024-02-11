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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un ticket</title>
    <link href="../CSS/style_form_ticket.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Créé votre ticket</h1>
    <form action="action_creation_ticket.php" method="POST">
        <label for="libelle">Libellé :</label>
        <select id="libelle" name="libelle">
            <?php
            $host = "localhost";
            $user = "root";
            $password = "";
            $database = "BD_Ticketing";
            $connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

            // Assumez que $connection est votre connexion à la base de données
            $queryLibelles = "SELECT id_libelle, libelle FROM libelle_ticket";
            $resultLibelles = mysqli_query($connection, $queryLibelles);

            while ($rowLibelle = mysqli_fetch_assoc($resultLibelles)) {
                echo "<option value='{$rowLibelle['id_libelle']}'>{$rowLibelle['libelle']}</option>";
            }
            ?>
        </select><br><br>

        <label for="description">Description :</label><br>

        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

        <label for="salle">Salle :</label>
        <select id="salle" name="salle">
            <option value="G22">G22</option>
            <option value="G23">G23</option>
            <option value="G24">G24</option>
            <option value="G25">G25</option>
            <option value="G26">G26</option>
        </select><br><br>

        <label for="ip">@ip du poste :</label><br>
        <input type="text" id="ip" name="ip" required maxlength="15"><br><br>

        <label for="priorite">Priorité :</label>
        <select id="priorite" name="priorite">
            <option value="Faible">Faible</option>
            <option value="Moyen">Moyen</option>
            <option value="Important">Important</option>
            <option value="Urgent">Urgent</option>
        </select><br><br>

        <input type="submit" value="Créer le ticket">
    </form>
</div>
</body>
<a href="authentification.php" class="bouton-redirection">Retour</a>
</html>
