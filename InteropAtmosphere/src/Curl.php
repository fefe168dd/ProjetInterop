<?php

// Fonction pour récupérer des données via cURL
function getCurl($url){
    // Initialisation de cURL
    $curl = curl_init();
    // Configuration de cURL
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    
    // Tentative avec proxy universitaire (si disponible)
    $proxyHost = 'www-cache';
    $proxyPort = 3128;
    $useProxy = @fsockopen($proxyHost, $proxyPort, $errno, $errstr, 1);
    
    if ($useProxy) {
        fclose($useProxy);
        curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt($curl, CURLOPT_PROXY, $proxyHost . ':' . $proxyPort);
    }
    
    // Exécution de cURL
    $response = curl_exec($curl);
    
    // Gestion des erreurs
    if (curl_errno($curl)) {
        error_log('Erreur cURL: ' . curl_error($curl));
        $response = false;
    }
    
    // Fermeture de cURL
    curl_close($curl);
    return $response;
}