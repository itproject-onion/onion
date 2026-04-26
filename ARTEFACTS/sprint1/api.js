async function getWeather() {
    const latitude = 48.2;
    const longitude = 16.37;

    const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&daily=temperature_2m_max,temperature_2m_min&hourly=temperature_2m,apparent_temperature,rain,cloud_cover_mid,uv_index,precipitation_probability&timezone=Europe%2FBerlin`;

    try {
        const response = await fetch(url);
        const data = await response.json();
        console.log(data);

        const weather = {
            apparentTemperature: data.hourly.apparent_temperature,
            rain: data.hourly.rain,
            cloudCoverMid: data.hourly.cloud_cover_mid,
            uvIndex: data.hourly.uv_index,
            precipitationProbability: data.hourly.precipitation_probability,
            temperatureMax: data.daily.temperature_2m_max[0],
            temperatureMin: data.daily.temperature_2m_min[0]
        };

        console.log(weather);
        return weather;

    } catch (error) {
        console.error('Error fetching weather data:', error);
    }
}

getWeather();