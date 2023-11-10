<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion & Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="../CSS/style_connexion_inscription_2.css" rel="stylesheet">
</head>
<body>
    <main>
        <div class = "container" id="container">
            <div class="form-container sign-up">
                <form action="../PHP/Inscription.php" method="post">    <!--changer l'action vers le formulaire action.php-->
                    <h1>Créer un compte</h1>
                    <label for="name">Nom :</label>
                    <input type="text" id="name" placeholder="Nom" name="nom">
                    <label for="login">Login :</label>
                    <input type="text" id="login" placeholder="Login" name="login">
                    <label for="email_inscription">Adresse mail :</label>
                    <input type="email" id="email_inscription" placeholder="Email" name="email">
                    <label for="password_inscription">Mot de passe :</label>
                    <input type="password" id="password_inscription" placeholder="Mot de passe" name="mot_de_passe">
                    <button type="submit" name='inscription' value='Inscription'>Inscription</button>
                </form>
            </div>
            <div class="form-container sign-in">
                <form action="../PHP/Connexion.php" method="post">    <!--changer l'action vers le formulaire action.php-->
                    <h1>Connectez-vous</h1>
                    <label for="login">Login :</label>
                    <input type="text" id="login_connexion" placeholder="Login" name="login_connexion">
                    <label for="password_connexion">Mot de passe :</label>
                    <input type="password" id="password_connexion" placeholder="Mot de passe" name="mot_de_passe">
                    <a href="#">mot de passe oublié ?</a> <!--Rediriger vers le formulaire php pour changer de mot de passe-->
                    <button type="submit" name="connexion">Connexion</button>
                </form>
            </div>
            <div class="toggle-container">
                <div class="toggle">
                    <div class="toggle-panel toggle-left">
                        <h1>Bienvenue parmis nous !</h1>
                        <p>Entrez vos informations pour pouvoir accéder pleinement à notre site.</p>
                        <button class="hidden" id="connexion">Connexion</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>Content de vous revoir !</h1>
                        <p>Si vous n’êtes pas encore inscrit parmi nous, cliquez sur le bouton ci-dessous pour vous enregistrer.</p>
                        <button class="hidden" id="register">Inscription</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="../JS/script.js"></script>
        <script src="../JS/volet.js"></script>

        <?php
        if (isset($_SESSION['inscription_reussie'])) {
            $message = ($_SESSION['inscription_message']);
            $couleur = ($_SESSION['inscription_reussie']) ? "green" : "red";
            // Appel de la fonction sans inclure à nouveau le script
            echo "<script>afficherVolet('$message', '$couleur');</script>";
            // Vider la session après utilisation
            unset($_SESSION['inscription_reussie']);
        } else {
            echo "<script>console.log('KO');</script>";
        }
        ?>
    </main>
</body>
</html>
