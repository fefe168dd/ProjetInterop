'use strict';
import { afficherStations } from './CarteVelos.js';
import { creerChart } from "./ChartCovid.js";
import { afficherQualiteAir } from "./QualiteAir.js";
import {afficherMeteo} from "./Meteo.js";

window.addEventListener('load', async () => {
    const date = Date.now();
    afficherQualiteAir(date);
    afficherMeteo();
    creerChart();
    await afficherStations();
});
