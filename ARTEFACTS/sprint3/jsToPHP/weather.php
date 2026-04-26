<?php

function getWeather() {
    $latitude = 48.2;
    $longitude = 16.37;

    $url = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&hourly=time,apparent_temperature,rain,cloud_cover,uv_index,is_day&timezone=Europe%2FBerlin";

    try {
        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception("Wetterdaten konnten nicht geladen werden.");
        }

        $data = json_decode($response, true);

        return [
            'time' => $data['hourly']['time'],
            'apparentTemperature' => $data['hourly']['apparent_temperature'],
            'rain' => $data['hourly']['rain'],
            'cloudCover' => $data['hourly']['cloud_cover'],
            'uvIndex' => $data['hourly']['uv_index'],
            'isDay' => $data['hourly']['is_day']
        ];

    } catch (Exception $error) {
        echo 'Error fetching weather data: ' . $error->getMessage();
        return null;
    }
}