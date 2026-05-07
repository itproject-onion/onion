<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class RecommendationService
{
    private $config = [
        'rainThreshold' => 0.5,
        'minComfortHours' => 7,
        'sunglassesMinHours' => 3
    ];

    public function isRecommended($kleidungsstueck, $currentTime, $weather) {
        $config = $this->config;

        if($kleidungsstueck->category === null) return false;

        if ($kleidungsstueck->category->impactedbyrain && $kleidungsstueck->is_waterproof !== null) {
            $rainSoon = false;

            for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
                if (isset($weather['rain'][$i]) && $weather['rain'][$i] > $config['rainThreshold']) {
                    $rainSoon = true;
                    break;
                }
            }

            if ($rainSoon) {
                if ($kleidungsstueck->is_waterproof === true) {
                    return $this->checkTemperatureRange($kleidungsstueck, $currentTime, $weather['apparentTemperature']);
                }
                return false;
            }

            return $this->checkTemperatureRange($kleidungsstueck, $currentTime, $weather['apparentTemperature']);
        }

        if ($kleidungsstueck->category->categoryname === 'Sonnenbrille') {
            $goodHours = 0;

            for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
                if (
                    isset($weather['isDay'][$i], $weather['cloudCover'][$i]) &&
                    $weather['isDay'][$i] == 1 &&
                    $kleidungsstueck->cloudcoverthreshold > $weather['cloudCover'][$i]
                ) {
                    $goodHours++;
                }
            }

            return $goodHours >= $config['sunglassesMinHours'];
        }

        if ($kleidungsstueck->category->categoryname === 'Sonnencreme') {
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

            return
                $kleidungsstueck->minuv <= $peakUV &&
                $kleidungsstueck->maxuv >= $peakUV;
        }

        return $this->checkTemperatureRange($kleidungsstueck, $currentTime, $weather['apparentTemperature']);
    }

    private function checkTemperatureRange($kleidungsstueck, $currentTime, $apparentTemperature) {
        $config = $this->config;
        if (Auth::guest()) {
            $userTempOffset = 0;
        } else {
            $userTempOffset = auth()->user()->temperature_offset;
        }

        $fallsInRange = 0;

        for ($i = $currentTime; $i < $currentTime + 10; ++$i) {
            if (
                isset($apparentTemperature[$i]) &&
                ($kleidungsstueck->mintemp + $userTempOffset) <= $apparentTemperature[$i] &&
                ($kleidungsstueck->maxtemp + $userTempOffset) >= $apparentTemperature[$i]
            ) {
                $fallsInRange++;
            }
        }

        return $fallsInRange >= $config['minComfortHours'];
    }
}