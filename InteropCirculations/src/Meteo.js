'use strict';
import { getIp } from "./Ip.js";

// fonction pour récupérer les émojis (trouvés sur https://emojipedia.org)
function getIcon(val, periode) {
    // si il pleut et que la nébulosité est supérieure à 50%
    if (val.pluie > 0 && val.nebulosite.totale > 50) {
        return '☁️🌧️☁️';
        // si il pleut
    }else if (val.pluie > 0) {
        return '☁️🌦️☁️';
        // si la nébulosité est supérieure à 50%
    } else if (val.nebulosite.totale > 50) {
        return '☁️🌥️☁️';
        // sinon afficher l'emoji en fonction de la période
    } else if (periode === 'Matin') {
        return '🌇';
    } else if (periode === 'Midi') {
        return '🏙️';
    } else if (periode === 'Soir') {
        return '🌆';
    } else {
        return '🌃';
    }
}

// fonction pour afficher les données météo
async function afficherMeteo() {
    const infos = await getIp();
    let meteo = await fetch(`https://www.infoclimat.fr/public-api/gfs/json?_ll=${infos.loc}&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2`).then(response => response.json()).then(data => {
        return data
    });
    // récupérer le div
    const meteoDiv = document.getElementById('Meteo');
    // créer un tableau avec les moments de la journée
    const periodesJournee = ['Matin', 'Midi', 'Soir', 'Nuit'];
    // récupérer les données à afficher
    const data = [];
    //matin (7h)
    data.push(Object.values(meteo)[6]);
    //midi (13h)
    data.push(Object.values(meteo)[8]);
    //soir (19h)
    data.push(Object.values(meteo)[10]);
    //nuit (22h)
    data.push(Object.values(meteo)[11]);
    // afficher les données
    meteoDiv.innerHTML = data.map((val, index) => `<div> <h3>${periodesJournee[index]}</h3> <h3>${getIcon(val, periodesJournee[index])}</h3> <p>Humidité 💧(2m) ${val.humidite['2m']}%</p> <p>Vent moyen 🍃 (10m) ${val.vent_moyen['10m']} m/s</p> <p>Rafales 💨 (10m) ${val.vent_rafales['10m']} m/s</p> <p>Température 🌡️ (2m) ${(val.temperature['2m'] - 273.15).toFixed(2)}°C</p></div>`).join('');
}

export {
    afficherMeteo
}
