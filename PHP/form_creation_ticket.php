<?php
session_start();
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
        <label for="sujet">Sujet :</label>
        <input type="text" id="sujet" name="sujet" required><br><br>

        <label for="description">Description :</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

        <label for="priorite">Priorité :</label>
        <select id="priorite" name="priorite">
            <option value="faible">Faible</option>
            <option value="moyenne">Moyenne</option>
            <option value="haute">Haute</option>
        </select><br><br>

        <input type="submit" value="Créer le ticket">
    </form>
</div>

</body>
</html>
