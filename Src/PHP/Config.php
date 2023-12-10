<?php

// Config.php
$logConfig = [
    'logFile' => 'app.log',
    'logLevel' => 'info',
];

// Vérifiez si le fichier de log existe, sinon, créez-le
if (!file_exists($logConfig['logFile'])) {
    $file = fopen($logConfig['logFile'], 'w');
    fclose($file);
}

// Fonction de journalisation
function logMessage($message, $level = 'info') {
    global $logConfig;

    if ($logConfig['logLevel'] == 'info' || $logConfig['logLevel'] == 'debug') {
        $timestamp = date("d-m-Y H:i:s");
        $ip = $_SERVER['REMOTE_ADDR']; // Récupère l'adresse IP du client
        $logEntry = "[$timestamp] [$level] [IP: $ip]: $message\n";

        $logFile = $logConfig['logFile'];

        // Écriture dans le fichier de log
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

