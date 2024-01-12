<?php
session_start();
// Inclure le fichier de configuration des logs
require_once('Config.php');

if (!isset($_SESSION['login'])) {
    header("Location: ../PHP/Deconnexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Id'])) {
        $ticket_id = $_POST['Id'];

        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "BD_Ticketing";
        $table_tickets = "tickets";
        $table_tickets_close = "tickets_close";

        $connection = mysqli_connect($host, $user, $password, $database) or die("Erreur de connexion à la base de données");

        // Récupérer les détails du ticket
        $query = "SELECT * FROM $table_tickets WHERE id_ticket = ?";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $ticket_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && $row = mysqli_fetch_assoc($result)) {
                // Insérer le ticket dans la table 'tickets_close'
                $insertQuery = "INSERT INTO $table_tickets_close (id_ticket, id_libelle, description, date_creation, priorite, statut, technicien, login) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($connection, $insertQuery);

                if ($stmtInsert) {
                    $statutFerme = "Fermé";
                    mysqli_stmt_bind_param($stmtInsert, "isssssss", $row['id_ticket'], $row['id_libelle'], $row['description'], $row['date_creation'], $row['priorite'], $statutFerme, $row['technicien'], $row['login']);
                    mysqli_stmt_execute($stmtInsert);

                    // Supprimer le ticket de la table 'tickets'
                    $deleteQuery = "DELETE FROM $table_tickets WHERE id_ticket = ?";
                    $stmtDelete = mysqli_prepare($connection, $deleteQuery);

                    if ($stmtDelete) {
                        mysqli_stmt_bind_param($stmtDelete, "i", $ticket_id);
                        mysqli_stmt_execute($stmtDelete);

                        logMessage("Le ticket a été clôturé avec succès.");
                        $_SESSION['message'] = "Le ticket a été clôturé avec succès.";
                        $_SESSION['couleur'] = true;
                        header('Location: ../PHP/authentification.php');
                        exit();
                    } else {
                        logMessage("Erreur lors de la préparation de la requête de suppression.", 'error');
                        $_SESSION['message'] = "Erreur lors de la préparation de la requête de suppression.";
                        $_SESSION['couleur'] = false;
                    }

                    mysqli_stmt_close($stmtDelete);
                } else {
                    logMessage("Erreur lors de la préparation de la requête d'insertion.", 'error');
                    $_SESSION['message'] = "Erreur lors de la préparation de la requête d'insertion.";
                    $_SESSION['couleur'] = false;

                }

                mysqli_stmt_close($stmtInsert);
            } else {
                logMessage("Aucun ticket trouvé avec l'ID spécifié.", 'error');
                $_SESSION['message'] = "Aucun ticket trouvé avec l'ID spécifié.";
                $_SESSION['couleur'] = false;
            }

            mysqli_stmt_close($stmt);
        } else {
            logMessage("Erreur lors de la préparation de la requête de sélection.", 'error');
            $_SESSION['message'] = "Erreur lors de la préparation de la requête de sélection.";
            $_SESSION['couleur'] = false;
        }
        mysqli_close($connection);
    } else {
        logMessage("ID du ticket non spécifié.", 'error');
        $_SESSION['message'] = "ID du ticket non spécifié.";
        $_SESSION['couleur'] = false;
    }
} else {
    logMessage("Requête non autorisée.", 'error');
    $_SESSION['message'] = "Requête non autorisée.";
    $_SESSION['couleur'] = false;

}
header('Location: ../PHP/page_details_ticket.php');
exit();
?>
