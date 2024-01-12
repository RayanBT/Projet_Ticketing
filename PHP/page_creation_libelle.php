<?php
session_start();

if (!isset($_SESSION['login']) or $_SESSION['user_role'] != "admin_web") {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un compte technicien</title>
    <link href="../CSS/style_form_ticket.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Créé d'un nouveau libellé</h1>
    <form action="" method="POST">
        <label for="libelle">Libellé :</label>
        <input type="text" id="libelle" placeholder="Libellé" name="libelle"><br><br>
        <input type="submit" value="Créer le nouveau libellé" name="inscription_technicien">
    </form>
</div>
</body>
<a href="authentification.php" class="bouton-redirection">Retour</a>
</html>
