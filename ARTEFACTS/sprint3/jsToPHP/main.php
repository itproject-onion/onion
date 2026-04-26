<?php

require_once 'weather.php';
require_once 'recommendation.php';

function getCurrentHourIndex($weatherTimes) {
    $currentHour = date('Y-m-d\TH');

    foreach ($weatherTimes as $index => $time) {
        if (str_starts_with($time, $currentHour)) {
            return $index;
        }
    }

    return -1;
}

function getRecommendations($kleidungsstuecke, $weather, $currentTime) {
    $recommendations = [];

    foreach ($kleidungsstuecke as $kleidungsstueck) {
        if (isRecommended($kleidungsstueck, $currentTime, $weather)) {
            $recommendations[] = $kleidungsstueck;
        }
    }

    return $recommendations;
}

function main() {
    $weather = getWeather();

    if (!$weather) {
        echo "Keine Wetterdaten verfügbar";
        return;
    }

    $currentTime = getCurrentHourIndex($weather['time']);

    if ($currentTime === -1) {
        echo "Aktuelle Stunde nicht in Wetterdaten gefunden";
        return;
    }

    $kleidungsstuecke = []; // kommen von db

    $recommendations = getRecommendations(
        $kleidungsstuecke,
        $weather,
        $currentTime
    );

    echo "<pre>";
    echo "Empfohlene Kleidungsstücke:\n";
    print_r($recommendations);
    echo "</pre>";
}

main();