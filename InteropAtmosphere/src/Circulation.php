<?php
require_once 'Curl.php';

// Fonction pour récupérer la circulation
function getCirculation(){
    // Récupération des données de l'API
    $curl = getCurl("https://carto.g-ny.org/data/cifs/cifs_waze_v2.json");
    return $curl;
}
