<?php
require_once 'Curl.php';

// Fonction pour récupérer la qualité de l'air
function getQualiteAir(){
    // Récupération des données de l'API
    $curl = getCurl("https://services3.arcgis.com/Is0UwT37raQYl9Jj/arcgis/rest/services/ind_grandest/FeatureServer/0/query?where=lib_zone%3D%27Nancy%27&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&featureEncoding=esriDefault&multipatchOption=xyFootprint&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pjson&token=");
    // Décodage du JSON
    $data = json_decode($curl);
    $features = (isset($data->features) && (is_array($data->features) || is_object($data->features))) ? $data->features : [];
    // Récupération de la date actuelle
    $date = new DateTime();
    $qualite = null;
    $minDif = PHP_INT_MAX;
    // Recherche de la qualité la plus proche de la date actuelle
    foreach ($features as $feature){ // $features is now always array|object
        // Suppression des 3 derniers caractères de la date pour la convertir en timestamp
        $dateString = substr($feature->attributes->date_ech, 0, -3);
        // Conversion de la date en objet DateTime
        $featureTemps = DateTime::createFromFormat('U', $dateString);
        // Calcul de la différence de temps entre la date actuelle et la date de la qualité de l'air
        $diff = abs($featureTemps->getTimestamp() - $date->getTimestamp());
        // Si la différence est inférieure à la différence minimale, on met à jour les variables
        if ($diff < $minDif){
            $minDif = $diff;
            $qualite = $feature;
        }
    }
    return $qualite;
}
