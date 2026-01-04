<?php

define('NANCY_LAT', 48.6921);
define('NANCY_LON', 6.1844);
define('IUT_CHARLEMAGNE_ADDRESS', '2 ter boulevard Charlemagne, 54000 Nancy');
define('apikey','41064487fd0d4bf0ac3195501260301');


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

    $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,city,lat,lon";
    

    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    if ($data['status'] === 'success') {
        return [
            'country' => $data['country'],
            'city' => $data['city'],
            'lat' => $data['lat'],
            'lon' => $data['lon']
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

    

$url = "https://api.openweathermap.org/data/2.5/weather"
         . "?lat={$lat}"
         . "&lon={$lon}"
         . "&units=metric"
         . "&lang=fr"
         . "&appid=" . apikey;

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data) && isset($data['main'])) {
        return [
            'ville' => $data['name'],
            'temperature' => $data['main']['temp'],
            'description' => $data['weather'][0]['description'],
            'vent' => $data['wind']['speed'],
            'pluie'=> $data['rain'] ['1h'],
            'neige'=>$data['snow'] ['1h']
        ];
    }

    return null;
}

function afficherMeteo() {
    $ip = getClientIP();
    $geo = geolocateIP($ip);

    if (!$geo) return "<p>Impossible de géolocaliser l'IP.</p>";

    $meteo = getWeather($geo['lat'], $geo['lon']);

    if (!$meteo) return "<p>Impossible de récupérer la météo.</p>";

    $xml = new DOMDocument('1.0','UTF-8');
    $root = $xml->createElement('Meteonancy');
    $xml->appendChild($root);
    foreach ($meteo as $k => $v) {
        $root->appendChild($xml->createElement($k, htmlspecialchars($v)));
    }

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

