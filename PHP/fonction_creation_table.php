<?php

function creationTable($result)
{
    echo "<table style='width: 100%; text-align: center'>";

    // Affiche les en-têtes de colonnes
    echo "<tr>";

    if ($result && mysqli_num_rows($result) > 0) {
        $headerPrinted = false;
        while ($row = mysqli_fetch_assoc($result)) {
            // Affiche les en-têtes de colonnes une seule fois
            if (!$headerPrinted) {
                foreach ($row as $key => $value) {
                    echo "<th>$key</th>";
                }
                echo "</tr>";
                $headerPrinted = true;
            }

            // Affiche les données de chaque ligne
            echo "<tr class='table-row' onclick=\"window.location='page_details_ticket.php?id=" . urlencode($row['Id']) . "'\">";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        // Affiche les en-têtes de colonnes même si aucun résultat n'est retourné
        echo "<th>Id</th>";
        echo "<th>Crée par</th>";
        echo "<th>Sujet</th>";
        echo "<th>Niveau d'urgence</th>";
        echo "<th>Date de création</th>";
        echo "<th>Statut</th>";
        echo "<th>Technicien en charge</th>";
        echo "</tr>";

        // Génère des lignes vides
        for ($i = 0; $i < 5; $i++) {
            echo "<tr class='table-row'>";
            echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
