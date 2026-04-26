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