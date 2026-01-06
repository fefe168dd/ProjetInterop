<?php
require_once 'Ip.php';
require_once 'Curl.php';

// Fonction pour récupérer la météo
function getMeteo() {
    // récupération de la latitude et de la longitude
    $ipXml = getIpClient();
    // récupération de la latitude et de la longitude
    $lat = (is_object($ipXml) && isset($ipXml->latitude)) ? $ipXml->latitude : 48.6921;
    $long = (is_object($ipXml) && isset($ipXml->longitude)) ? $ipXml->longitude : 6.1844;
    // si la latitude et la longitude ne sont pas celles de Nancy
    if ($lat != 48.6921) {
        $lat = 48.6921;
    }
    if ($long != 6.1844) {
        $long = 6.1844;
    }
    // récupération de la météo
    $curl = getCurl("https://www.infoclimat.fr/public-api/gfs/xml?_ll={$lat},{$long}&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2");
    // fichier XSL
    $xsl = new DOMDocument();
    $xsl->load(__DIR__.'/../infoclimat.xsl');
    // fichier XML
    $domDoc = new DOMDocument();
    if (!empty($curl)) {
        $domDoc->loadXML($curl);
    } else {
        return '<p>Erreur : données météo non disponibles.</p>';
    }
    // transformateur XSLT
    $proces = new XSLTProcessor();
    $proces->importStyleSheet($xsl);
    // transformation du fichier XML
    $html = $proces->transformToXML($domDoc);
    return $html;
}
