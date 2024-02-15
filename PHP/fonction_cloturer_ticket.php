<?php
session_start();
require_once('Config.php');
require('fonction_connexion_bd.php');
function closeTicket($ticket_id) {
    global $host, $user, $password, $database; // Inclure les variables de Config.php dans cette fonction

    $table_tickets = "tickets";
    $table_tickets_close = "tickets_close";

    $connection = connectToDatabase($host, $user, $password, $database);

    $query = "SELECT * FROM $table_tickets WHERE id_ticket = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $ticket_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $insertQuery = "INSERT INTO $table_tickets_close (id_ticket, id_libelle, description, date_creation, priorite, statut, technicien, login) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = mysqli_prepare($connection, $insertQuery);

            if ($stmtInsert) {
                $statutFerme = "Fermé";
                mysqli_stmt_bind_param($stmtInsert, "isssssss", $row['id_ticket'], $row['id_libelle'], $row['description'], $row['date_creation'], $row['priorite'], $statutFerme, $row['technicien'], $row['login']);
                mysqli_stmt_execute($stmtInsert);

                $deleteQuery = "DELETE FROM $table_tickets WHERE id_ticket = ?";
                $stmtDelete = mysqli_prepare($connection, $deleteQuery);

                if ($stmtDelete) {
                    mysqli_stmt_bind_param($stmtDelete, "i", $ticket_id);
                    mysqli_stmt_execute($stmtDelete);

                    logMessage("Le ticket a été clôturé avec succès.");
                    $_SESSION['message'] = "Le ticket a été clôturé avec succès.";
                    $_SESSION['couleur'] = true;
                    mysqli_stmt_close($stmtDelete);
                } else {
                    logMessage("Erreur lors de la préparation de la requête de suppression.", 'error');
                    $_SESSION['message'] = "Erreur lors de la préparation de la requête de suppression.";
                    $_SESSION['couleur'] = false;
                }

                mysqli_stmt_close($stmtInsert);
            } else {
                logMessage("Erreur lors de la préparation de la requête d'insertion.", 'error');
                $_SESSION['message'] = "Erreur lors de la préparation de la requête d'insertion.";
                $_SESSION['couleur'] = false;
            }
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
}
?>
