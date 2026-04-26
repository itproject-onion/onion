<?php
namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\RecommendationService;

class RecommendationController extends Controller
{
    public function index(WeatherService $weatherService, RecommendationService $recService)
    {
        $weather = $weatherService->getWeather();
        if (!$weather) {
            return response()->json(['error' => 'Keine Wetterdaten'], 500);
        }

        $currentTime = $this->getCurrentHourIndex($weather['time']);
        if ($currentTime === -1) {
            return response()->json(['error' => 'Zeit nicht gefunden'], 500);
        }

        $kleidungsstuecke = []; // später aus DB
        $recommendations = [];
        foreach ($kleidungsstuecke as $item) {
            if ($recService->isRecommended($item, $currentTime, $weather)) {
                $recommendations[] = $item;
            }
        }

        $tags = []; // später aus DB alle tags des users holen

        return view('home', [
            'recommendations' => $recommendations,
            'tags' => $tags
        ]);
    }

    private function getCurrentHourIndex($times)
    {
        $currentHour = now()->format('Y-m-d\TH');

        foreach ($times as $index => $time) {
            if (str_starts_with($time, $currentHour)) {
                return $index;
            }
        }

        return -1;
    }
}