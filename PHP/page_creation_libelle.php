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
    <link href="../CSS/style_volet_information.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Créé d'un nouveau libellé</h1>
    <form action="action_creation_libelle.php" method="POST">
        <label for="libelle">Libellé :</label>
        <input type="text" id="libelle" placeholder="Libellé" name="libelle"><br><br>
        <input type="submit" value="Créer le nouveau libellé" name="creation_libelle">
    </form>
</div>
</body>
<a href="authentification.php" class="bouton-redirection">Retour</a>
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
</html>
