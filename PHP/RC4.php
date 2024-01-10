<?php

// Algorithme 1: Génération de la suite chiffrante de RC4 (PRGA)
function rc4_prga($S, $n) {
    $K = [];
    $i = $j = 0;

    for ($k = 0; $k < $n; $k++) {
        $i = ($i + 1) % 256;
        $j = ($j + $S[$i]) % 256;
        list($S[$i], $S[$j]) = [$S[$j], $S[$i]];
        $z = $S[($S[$i] + $S[$j]) % 256];
        $K[] = $z;
    }

    return $K;
}

// Algorithme 2: Génération de la permutation S (KSA)
function rc4_ksa($K) {
    $S = range(0, 255);
    $j = 0;

    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $S[$i] + $K[$i % count($K)]) % 256;
        list($S[$i], $S[$j]) = [$S[$j], $S[$i]];
    }

    return $S;
}

// Fonction pour chiffrer un message en utilisant RC4
function rc4_encrypt($message, $key) {
    // Convertir la clé en une liste d'entiers
    $key = array_map('ord', str_split($key));

    // Génération de la permutation S
    $S = rc4_ksa($key);

    // Génération de la suite chiffrante PRGA
    $keystream = rc4_prga($S, strlen($message));

    // Chiffrage du message
    $cipherText = '';
    for ($i = 0; $i < strlen($message); $i++) {
        $cipherText .= sprintf('%02x', ord($message[$i]) ^ $keystream[$i]);
    }

    // Retourner le message chiffré sous forme de chaîne hexadécimale
    return $cipherText;
}

// Fonction pour déchiffrer un message en utilisant RC4
function rc4_decrypt($cipherText, $key) {
    // Convertir la clé en une liste d'entiers
    $key = array_map('ord', str_split($key));

    // Génération de la permutation S
    $S = rc4_ksa($key);

    // Génération de la suite chiffrante PRGA
    $keystream = rc4_prga($S, strlen($cipherText) / 2);

    // Conversion du message chiffré hexadécimal en liste d'entiers
    $cipherText = str_split($cipherText, 2);
    $cipherText = array_map('hexdec', $cipherText);

    // Déchiffrage du message
    $plainText = '';
    for ($i = 0; $i < count($cipherText); $i++) {
        $plainText .= chr($cipherText[$i] ^ $keystream[$i]);
    }

    // Retourner le message déchiffré sous forme de chaîne
    return $plainText;
}

?>