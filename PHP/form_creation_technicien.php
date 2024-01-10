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
    <h1>Créé d'un compte technicien</h1>
    <form action="Inscription.php" method="POST">
        <label for="name">Nom :</label>
        <input type="text" id="name" placeholder="Nom" name="nom"><br><br>
        <label for="login">Login :</label>
        <input type="text" id="login" placeholder="Login" name="login"><br><br>
        <label for="email_inscription">Adresse mail :</label>
        <input type="email" id="email_inscription" placeholder="Email" name="email"><br><br>
        <label for="password_inscription">Mot de passe :</label>
        <input type="password" id="password_inscription" placeholder="Mot de passe" name="mot_de_passe"><br><br>
        <input type="submit" value="Créer le compte technicien" name="inscription_technicien">
    </form>
</div>
</body>
<a href="authentification.php" class="bouton-redirection">Retour</a>
</html>
