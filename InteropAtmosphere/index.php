<?php
require_once 'src/QualiteAir.php';
require_once 'src/Meteo.php';
require_once 'src/Ip.php';
require_once 'src/Circulation.php';

// récupération des données à afficher

$air = getQualiteAir();
$meteo = getMeteo();
$coord = getIpClient();
$lat = null;
$long = null;
if ($coord && isset($coord->latitude) && isset($coord->longitude)) {
    $lat = $coord->latitude;
    $long = $coord->longitude;
}
// si les coordonnées ne sont pas à Nancy, on les remplace
if ($lat !== 48.6921) {
    $lat = 48.6921;
}
if ($long !== 6.1844) {
    $long = 6.1844;
}
?>
<html lang="fr">
<head>
    <title>Atmosphère</title>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
      <link rel="stylesheet" type="text/css" href="./style.css"/>
</head>
<body>
<h1>Atmosphère... Une page php croisant des API</h1>
<div id="QualiteAir" style="background-color: <?php echo ($air && isset($air->attributes->coul_qual)) ? $air->attributes->coul_qual : '#ccc'; ?>;">
    <h3>Qualité de l'air à Nancy : </h3>
    <?php if ($air && isset($air->attributes->lib_qual)) { ?>
        <p><strong>Qualité : </strong><?php echo $air->attributes->lib_qual?></p>
    <?php } else { ?>
        <p><strong>Qualité : </strong>Information non disponible</p>
    <?php } ?>
</div>
<!-- Affichage de la météo -->
<?php echo $meteo;?>
<div id="Carte"></div>
<script>
    // Création de la carte
    var map = L.map('Carte').setView([<?php echo $lat?>,<?php echo $long?>], 15);
    // Ajout du fond de carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    // Ajout des incidents de circulation
    const circul = <?php echo getCirculation() ?>;
    circul.incidents.forEach(incident => {
        var coo = incident.location.polyline.split(' ');
        var marker = L.marker([parseFloat(coo[0]), parseFloat(coo[1])]).addTo(map); //transformer incident.starttime et endtime et remplacer 'T' par ' ' pour avoir une date lisible
        marker.bindPopup(`<b>${incident.short_description}</b><br>${incident.description}<br>Depuis: ` + incident.starttime.replace('T', ' ') + `<br>Jusqu'au: ` + incident.endtime.replace('T', ' '));
    });
</script>
<div id="Api">
    <h3>API utilisées + lien github :</h3>
    <ul>
        <li><a href="https://github.com/fefe168dd/ProjetInterop">github</a></li>
        <li><a href="https://services3.arcgis.com/Is0UwT37raQYl9Jj/arcgis/rest/services/ind_grandest/FeatureServer/0/query?where=lib_zone%3D%27Nancy%27&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&featureEncoding=esriDefault&multipatchOption=xyFootprint&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pjson&token=">Qualité de l'air</a></li>
        <li><a href="https://www.infoclimat.fr/public-api/gfs/xml?_ll=48.67103,6.15083&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2">Météo</a></li>
        <li><a href="https://ipapi.co/xml/">Ip</a></li>
        <li><a href="https://carto.g-ny.org/data/cifs/cifs_waze_v2.json">Circulation</a></li>
    </ul>
</div>
</body>
</html>