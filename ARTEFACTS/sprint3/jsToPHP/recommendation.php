<?php

$config = [
    'rainThreshold' => 0.5,
    'minComfortHours' => 7,
    'sunglassesMinHours' => 3
];

function isRecommended($kleidungsstueck, $currentTime, $weather) {
    global $config;

    if (in_array("Impacted by rain", $kleidungsstueck['tags'])) {
        $rainSoon = false;

        for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
            if (isset($weather['rain'][$i]) && $weather['rain'][$i] > $config['rainThreshold']) {
                $rainSoon = true;
                break;
            }
        }

        if ($rainSoon) {
            if (!empty($kleidungsstueck['wasserfest'])) {
                return checkTemperatureRange($kleidungsstueck, $currentTime, $weather['apparentTemperature']);
            }
            return false;
        }

        return checkTemperatureRange($kleidungsstueck, $currentTime, $weather['apparentTemperature']);
    }

    if ($kleidungsstueck['kategorie'] === 'sonnenbrille') {
        $goodHours = 0;

        for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
            if (
                isset($weather['isDay'][$i], $weather['cloudCover'][$i]) &&
                $weather['isDay'][$i] == 1 &&
                $kleidungsstueck['cloudCoverThreshold'] > $weather['cloudCover'][$i]
            ) {
                $goodHours++;
            }
        }

        return $goodHours >= $config['sunglassesMinHours'];
    }

    if ($kleidungsstueck['kategorie'] === 'sonnencreme') {
        $uvValues = [];

        for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
            if (isset($weather['uvIndex'][$i])) {
                $uvValues[] = $weather['uvIndex'][$i];
            }
        }

        if (empty($uvValues)) {
            return false;
        }

        $peakUV = max($uvValues);

        return $kleidungsstueck['minUVIndex'] <= $peakUV &&
               $kleidungsstueck['maxUVIndex'] >= $peakUV;
    }

    return checkTemperatureRange($kleidungsstueck, $currentTime, $weather['apparentTemperature']);
}

function checkTemperatureRange($kleidungsstueck, $currentTime, $apparentTemperature) {
    global $config;

    $fallsInRange = 0;

    for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
        if (
            isset($apparentTemperature[$i]) &&
            $kleidungsstueck['minTemp'] <= $apparentTemperature[$i] &&
            $kleidungsstueck['maxTemp'] >= $apparentTemperature[$i]
        ) {
            $fallsInRange++;
        }
    }

    return $fallsInRange >= $config['minComfortHours'];
}