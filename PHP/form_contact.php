<?php
require_once("fonction_check_connexion.php");

session_start();

// Générer deux chiffres aléatoires entre 1 et 10
$nombre1 = rand(1, 10);
$nombre2 = rand(1, 10);

// Calculer la somme
$somme = $nombre1 + $nombre2;

// Stocker la somme dans la session
$_SESSION['captcha'] = $somme;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Contact</title>
</head>
<body>

<h2>Contactez-nous</h2>

<form action="" method="post">
    <label for="email">Email :</label>
    <select id="email" name="email">
        <option value="btrayan21@gmail.com">Directeur du Service Technique</option>
        <option value="ismail.akboulatov@gmail.com">Administrateur de la Plateforme</option>
        <option value="armand.clouzeau@gmail.com">Analyste des Tickets</option>
        <option value="sarah.bader.f@gmail.com">Support Technique</option>
        <option value="aymeric.pesenti@gmail.com">Service Clients</option>
    </select>

    <br>

    <label for="objet">Objet :</label>
    <input type="text" id="objet" name="objet" placeholder="Raison du contact" required>

    <br>

    <label for="description">Description :</label>
    <textarea id="description" name="description" rows="4" placeholder="Description du problème rencontré" required></textarea>

    <br>

    <label for="captcha"> Vérification (calculez <?php echo $nombre1; ?> + <?php echo $nombre2; ?>) :</label>
    <input type="text" id="captcha" placeholder="Résultat" name="captcha">

    <input type="submit" value="Envoyer">
</form>

</body>
</html>

