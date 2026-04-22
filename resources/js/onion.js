// onion.js
/*console.log("External JS läuft!");
document.addEventListener('DOMContentLoaded', () => {
    alert("Hallo von external JS!");
});*/

//npm run dev!!!!!!!!!!

async function getWeather() {
    const latitude = 48.2;
    const longitude = 16.37;

    const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&hourly=apparent_temperature,rain,cloud_cover,uv_index,is_day&timezone=Europe%2FBerlin`;

    try {
        const response = await fetch(url);
        const data = await response.json();
        console.log(data);

        const weather = {
            apparentTemperature: data.hourly.apparent_temperature,
            rain: data.hourly.rain,
            cloudCover: data.hourly.cloud_cover,
            uvIndex: data.hourly.uv_index,
            isDay: data.hourly.is_day
        };

        console.log(weather);
        console.log(isRecommended(testKleidungsstueck, 10, weather.apparentTemperature, weather.rain, weather.cloudCover, weather.uvIndex, weather.isDay));
        return weather;

    } catch (error) {
        console.error('Error fetching weather data:', error);
    }
}

const currentWeather = getWeather();
const testKleidungsstueck = {
	wasserfest: true,
	kategorie: 'sonnenbrille',
	minTemp: 0,
	maxTemp: 20,
	minUVIndex: undefined, //für sonnencremes
	maxUVIndex: undefined, //für sonnencremes
	cloudCoverThreshold: 30, //für sonnenbrillen 0-100%. Unterhalb dieser Bewölkung werden sie empfohlen.
	tags: ["Sport"] //Impacted by rain -> Wasserfestigkeit wird berücksichtigt
}


const config = {
   	rainThreshold: 0.5,
    minComfortHours: 7,
    sunglassesMinHours: 3
}

function isRecommended(Kleidungsstück, currentTime, apparent_temperature, rain, cloud_cover, uv_index, is_day) {
    if(Kleidungsstück.tags.includes("Impacted by rain")) {
		if (rain.slice(currentTime, currentTime+10).some(value => value > config.rainThreshold)) //Bei vorkommen von mehr als 0.5mm regen in den nächsten 10h wasserfeste Kleidung empfehlen
		{
			if (Kleidungsstück.wasserfest === true)
			{
				return checkTemperatureRange(Kleidungsstück, currentTime, apparent_temperature);
			}
		} else {
			return checkTemperatureRange(Kleidungsstück, currentTime, apparent_temperature);
		}
	} else if(Kleidungsstück.kategorie == 'sonnenbrille') {
		let exceedsCloudCoverTreshold = 0;
		for (let i = currentTime; i <= currentTime+10; ++i)
		{
			if(is_day[i] == 1 && Kleidungsstück.cloudCoverThreshold > cloud_cover[i]) exceedsCloudCoverTreshold++; //berechnen wie oft die sonne scheint und wenig wolken am himmel sind
		}
		if (exceedsCloudCoverTreshold > config.sunglassesMinHours) return true;
	} else if(Kleidungsstück.kategorie == 'sonnencreme') {
		const peakUV = Math.max(...uv_index.slice(currentTime, 24));

		if (Kleidungsstück.minUVIndex <= peakUV && Kleidungsstück.maxUVIndex >= peakUV) return true;
	} else {
		return checkTemperatureRange(Kleidungsstück, currentTime, apparent_temperature);
	}

	return false;
}

//Wenn Kleidungsstück mind. 70% der Zeit von der Temperatur passt -> empfehlen.
function checkTemperatureRange(Kleidungsstück, currentTime, apparent_temperature) {  //Berechnet wie oft in den nächsten 10 Stunden das Kleidungsstück in der Temperaturrange optimal ist 0-10
	let fallsInRange = 0;
	for (let i = currentTime; i <= currentTime+10; ++i)
	{
		if (Kleidungsstück.minTemp <= apparent_temperature[i] && Kleidungsstück.maxTemp >= apparent_temperature[i])
		{
			fallsInRange++;
		}
	}

	if (fallsInRange >= config.minComfortHours) return true;
	return false;
}