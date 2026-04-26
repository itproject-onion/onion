<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeather($latitude, $longitude)
    {   
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'hourly' => implode(',', [
                'temperature_2m',
                'apparent_temperature',
                'rain',
                'cloud_cover',
                'uv_index',
                'is_day',
                'weather_code'
            ]),
            'timezone' => 'Europe/Berlin',
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        return [
            'time' => $data['hourly']['time'],
            'apparentTemperature' => $data['hourly']['apparent_temperature'],
            'rain' => $data['hourly']['rain'],
            'cloudCover' => $data['hourly']['cloud_cover'],
            'uvIndex' => $data['hourly']['uv_index'],
            'isDay' => $data['hourly']['is_day'],
            'weatherCode' => $data['hourly']['weather_code']
        ];
    }
}


/* WEATHER CODES. Symbole dafür?
Code	Description
0	Clear sky
1, 2, 3	Mainly clear, partly cloudy, and overcast
45, 48	Fog and depositing rime fog
51, 53, 55	Drizzle: Light, moderate, and dense intensity
56, 57	Freezing Drizzle: Light and dense intensity
61, 63, 65	Rain: Slight, moderate and heavy intensity
66, 67	Freezing Rain: Light and heavy intensity
71, 73, 75	Snow fall: Slight, moderate, and heavy intensity
77	Snow grains
80, 81, 82	Rain showers: Slight, moderate, and violent
85, 86	Snow showers slight and heavy
95 *	Thunderstorm: Slight or moderate
96, 99 *	Thunderstorm with slight and heavy hail

*/