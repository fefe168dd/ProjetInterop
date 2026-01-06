'use strict';
//fonction pour créer le graphique
async function creerChart() {
    //récupérer toutes les données
    const data = await getAllCovidData();
    //récupérer le contexte du canvas
    const ctx = document.getElementById('Chart').getContext('2d');
    //récupérer les valeurs et les labels
    const values = data.map(item => item.MAXEVILLE);
    const labels = data.map(item => item.semaine);
    //créer le graphique, type, données et options
    new Chart(ctx, {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'MAXEVILLE', data: values, borderColor: 'rgb(6, 2, 255)', borderWidth: 1, fill: false }]},
        options: {scales: { y: { beginAtZero: true }}}
    });
}

//fonction pour récupérer les données de la page
function getCovidData(page = 1) {
    //récupérer les données de la page
    return fetch(`https://tabular-api.data.gouv.fr/api/resources/2963ccb5-344d-4978-bdd3-08aaf9efe514/data/?page=${page}`).then(response => response.json()).then(data => {
            return data.data.map(item => ({
                semaine: item.semaine,
                MAXEVILLE: item.MAXEVILLE
            }));
    }).catch(error => {
        //si erreur, retourner un tableau vide
        return [];
    });
}

//fonction pour récupérer toutes les données
async function getAllCovidData() {
    let result = [];
    let i = 1;
    while (i <= 7) {
        //récupérer les données de la page i
        const data = await getCovidData(i);
        //ajouter les données à result
        result = result.concat(data);
        i++;
    }
    return result;
}

export {
    creerChart
}
