<?php

define('NANCY_LAT', 48.6921);
define('NANCY_LON', 6.1844);
define('IUT_CHARLEMAGNE_ADDRESS', '2 ter boulevard Charlemagne, 54000 Nancy');
define('apikey','3ba9a48527f6403e96c3fdbab9b4d4cb');


function getClientIP() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function geolocateIP($ip) {

    $url = "https://ipwho.is/{$ip}";
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
if ($data && isset($data['success']) && $data['success'] === true) {
        return [
            'country' => $data['country'],
            'city' => $data['city'],
            'lat' => $data['latitude'],
            'lon' => $data['longitude']
        ];
    } else {
        return null;
    }
}





function isNancy($lat, $lon) {
    $earthRadius = 6371; // km
    
    $latDiff = deg2rad(NANCY_LAT - $lat);
    $lonDiff = deg2rad(NANCY_LON - $lon);
    
    $a = sin($latDiff / 2) * sin($latDiff / 2) +
         cos(deg2rad($lat)) * cos(deg2rad(NANCY_LAT)) *
         sin($lonDiff / 2) * sin($lonDiff / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;
    
    return $distance <= 75; // 75km de rayon
}

function getWeather($lat, $lon) {
    $url = "https://api.weatherbit.io/v2.0/current"
         . "?lat={$lat}"
         . "&lon={$lon}"
         . "&lang=fr"
         . "&key=" . apikey;

    $response = file_get_contents($url);
    if ($response === false) return null;

    $data = json_decode($response, true);

    // Vérifie que data[0] existe
    if (!empty($data['data'][0])) {
        $w = $data['data'][0];
        return [
            'ville' => $w['city_name'] ?? 'Nancy',
            'temperature' => $w['temp'] ?? 0,
            'description' => $w['weather']['description'] ?? '',
            'vent' => $w['wind_spd'] ?? 0,
            'pluie' => $w['precip'] ?? 0,
            'neige' => 0 
        ];
    }

    return null;
}
function buildPrevisionsXML($meteo) {
    $xml = new DOMDocument('1.0','UTF-8');
    $previsions = $xml->createElement('previsions');
    $xml->appendChild($previsions);

    // Définir les heures pour matin, midi et soir
    $heures = [
        'matin' => 9,  // 9h
        'midi'  => 12, // 12h
        'soir'  => 18  // 18h
    ];

    foreach ($heures as $periode => $hour) {
        $echeance = $xml->createElement('echeance');
        $echeance->setAttribute('hour', $hour);
        $previsions->appendChild($echeance);

        $temp = $xml->createElement('temperature');
        $level = $xml->createElement('level', $meteo['temperature'] + 273.15); // convertir en Kelvin
        $level->setAttribute('val','2m');
        $temp->appendChild($level);
        $echeance->appendChild($temp);

        $pluie = $xml->createElement('pluie', $meteo['pluie']);
        $echeance->appendChild($pluie);

        $vent = $xml->createElement('vent_moyen');
        $levelVent = $xml->createElement('level', $meteo['vent']);
        $levelVent->setAttribute('val','10m');
        $vent->appendChild($levelVent);
        $echeance->appendChild($vent);

        $risqueNeige = $xml->createElement('risque_neige', $meteo['neige'] > 0 ? 'oui' : 'non');
        $echeance->appendChild($risqueNeige);
    }

    return $xml;
}

function afficherMeteo() {
    $ip = getClientIP();
    $geo = geolocateIP($ip);

    if (!$geo || !isNancy($geo['lat'], $geo['lon'])) {
        $geo = [
            'lat' => NANCY_LAT,
            'lon' => NANCY_LON
        ];
    }

    $meteo = getWeather($geo['lat'], $geo['lon']);
    if (!$meteo) return "<p>Impossible de récupérer la météo.</p>";

$xml = buildPrevisionsXML($meteo);

    $xsl = new DOMDocument();
    $xsl->load('Meteonancy.xsl');
    $proc = new XSLTProcessor();
    $proc->importStylesheet($xsl);

    return $proc->transformToXML($xml);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Météo client</title>
</head>
<body>

<h1>Météo actuelle</h1>

<?php
echo afficherMeteo();
?>

</body>
</html>

