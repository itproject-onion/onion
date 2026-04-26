<?php
namespace App\Services;

class RecommendationService
{
    private $config = [
        'rainThreshold' => 0.5,
        'minComfortHours' => 7,
        'sunglassesMinHours' => 3
    ];

    public function isRecommended($item, $currentTime, $weather)
    {
        if (in_array("Impacted by rain", $item['tags'])) {

            $rainSoon = false;

            for ($i = $currentTime; $i < $currentTime + 10; $i++) {
                if (isset($weather['rain'][$i]) && $weather['rain'][$i] > $this->config['rainThreshold']) {
                    $rainSoon = true;
                    break;
                }
            }

            if ($rainSoon && empty($item['wasserfest'])) {
                return false;
            }
        }

        return $this->checkTemperatureRange($item, $currentTime, $weather['apparentTemperature']);
    }

    private function checkTemperatureRange($item, $currentTime, $temps)
    {
        $count = 0;

        for ($i = $currentTime; $i < $currentTime + 10; $i++) {
            if (
                isset($temps[$i]) &&
                $item['minTemp'] <= $temps[$i] &&
                $item['maxTemp'] >= $temps[$i]
            ) {
                $count++;
            }
        }

        return $count >= $this->config['minComfortHours'];
    }
}