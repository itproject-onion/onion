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