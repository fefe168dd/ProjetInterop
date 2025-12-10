<?php

define('NANCY_LAT', 48.6921);
define('NANCY_LON', 6.1844);
define('IUT_CHARLEMAGNE_ADDRESS', '2 ter boulevard Charlemagne, 54000 Nancy');


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
