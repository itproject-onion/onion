import { getWeather } from "./weather.js";
import { isRecommended } from "./recommendation.js";


function getCurrentHourIndex(weatherTimes) {
    const now = new Date();
    const currentHour = now.toISOString().slice(0, 13); 

    return weatherTimes.findIndex(time => time.startsWith(currentHour));
}


function getRecommendations(kleidungsstuecke, weather, currentTime) {
    return kleidungsstuecke.filter(kleidungsstueck =>
        isRecommended(kleidungsstueck, currentTime, weather)
    );
}


async function main() {
   
    const weather = await getWeather();
    if (!weather) {
        console.error("Keine Wetterdaten verfügbar");
        return;
    }

    
    const currentTime = getCurrentHourIndex(weather.time);
    if (currentTime === -1) {
        console.error("Aktuelle Stunde nicht in Wetterdaten gefunden");
        return;
    }

    
    const kleidungsstuecke = []; //kommen von db 

    const recommendations = getRecommendations(
        kleidungsstuecke,
        weather,
        currentTime
    );

    
    console.log("Empfohlene Kleidungsstücke:");
    console.log(recommendations);
}

main();