import { getIp } from "./Ip.js";

//fonction pour initialiser la carte
async function initCarte(){
    //récupérer les informations de l'ip
    const infos = await getIp();
    //récupérer la latitude et la longitude
    const lat = infos.loc.split(',')[0];
    const lon = infos.loc.split(',')[1];
    //créer la carte
    //si l'ip n'est pas à Nancy (48.6921, 6.1844), afficher la carte de Nancy
    if (lat !== '48.6921' && lon !== '6.1844') {
        var map = L.map('Carte').setView([48.6921, 6.1844], 15);
    } else {
        var map = L.map('Carte').setView([lat, lon], 15);
    }
    //ajouter le tileLayer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    return map; this;
}

//fonction pour récupérer les stations
async function getStations() {
    let station = [];
    await fetch("https://api.cyclocity.fr/contracts/nancy/gbfs/station_information.json").then(response => response.json()).then(data => {
        station = data.data.stations;
    }).catch(error => console.log(error));
    return station;
}

//fonction pour récupérer le status des stations avec l'autre url
async function getStatusStation(stationId) {
    let status = {};
    await fetch(`https://api.cyclocity.fr/contracts/nancy/gbfs/station_status.json`).then(response => response.json()).then(data => {
        data.data.stations.forEach(station => {
            if (station.station_id === stationId) {
                status = station;
            }
        });
    }).catch(error => console.log(error));
    return status;
}

//fonction pour afficher les stations de vélos
async function afficherStations(){
    //initialiser la carte
    const map = await initCarte();
    //récupérer les stations
    const stations = await getStations();
    //pour chaque station, afficher un marqueur
    stations.forEach(async station => {
        //récupérer le status de la station
        const status = await getStatusStation(station.station_id);
        //créer un marqueur
        const marker = L.marker([station.lat, station.lon]).addTo(map);
        //ajouter un popup au marqueur
        marker.bindPopup(`<b>${station.name}</b><br>${station.address}<br>Capicité : ${station.capacity}<br>Vélos disponibles : ${status.num_bikes_available}<br>Emplacements disponibles : ${status.num_docks_available}`);
    });
}

export {
    afficherStations
}
