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
    <title>Changer le mot de passe</title>
    <link href="../CSS/style_form_ticket.css" rel="stylesheet">
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Changer le mot de passe</h2>
    <form method="post" action="actionChangePassword.php">
        <label for="mot_de_passe_actuel">Mot de passe actuel:</label>
        <input type="password" id="mot_de_passe_actuel" name="mot_de_passe_actuel" required><br>

        <label for="nouveau_mot_de_passe">Nouveau mot de passe:</label>
        <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required><br>

        <input type="submit" name="changer_mot_de_passe" value="Changer le mot de passe">
    </form>
</div>
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
</body>
<a href="authentification.php" class="bouton-redirection">Retour</a>
</html>


