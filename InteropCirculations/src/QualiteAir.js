'use strict';
//fonction pour récupérer la qualité de l'air (passée ou prévue en fonction du temps le plus proche)
async function getQualiteAir(date) {
    let air = await fetch(`https://services3.arcgis.com/Is0UwT37raQYl9Jj/arcgis/rest/services/ind_grandest/FeatureServer/0/query?where=lib_zone%3D%27Nancy%27&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&featureEncoding=esriDefault&multipatchOption=xyFootprint&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pjson&token=`).then(response => response.json()).then(data => {
        //récupérer le temps en millisecondes
        const temps = new Date(date).getTime();
        let qualite = null;
        let minDif = Number.MAX_SAFE_INTEGER;
        //récupérer les features
        let features = data.features;
        //parcourir les features
        features.forEach(feature => {
            //récupérer le temps de la feature
            const featureTemps = new Date(feature.attributes.date_ech / 1000).getTime();
            //calculer la différence absolue entre les temps
            const dif = Math.abs(temps - featureTemps);
            //si la différence est inférieure à la différence minimale, changer la qualité
            if (dif < minDif) {
                minDif = dif;
                qualite = feature;
            }
        });
        return qualite;
        }).catch(error => console.log(error));
    return air;
}

//fonction pour afficher la qualité de l'air
async function afficherQualiteAir(date) {
    //récupérer la qualité de l'air
    let air = await getQualiteAir(date);
    //récupérer le div
    const airDiv = document.getElementById('QualiteAir');
    //afficher la qualité de l'air
    airDiv.innerHTML = `<div style="background-color: ${air.attributes.coul_qual}; padding: 15px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);">
        <h2>Qualité de l'air à ${air.attributes.lib_zone}</h2>
        <p><strong>Qualité : </strong> ${air.attributes.lib_qual}</p></div>`;
}

export {
    afficherQualiteAir
};
