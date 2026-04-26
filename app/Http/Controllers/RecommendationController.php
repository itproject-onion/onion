<?php
namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\RecommendationService;
use App\Services\GeocodingService;

class RecommendationController extends Controller
{
    public function index(WeatherService $weatherService, RecommendationService $recService, GeocodingService $GeocodingService)
    {
        $latitude = 48.2;
        $longitude = 16.37;

        $location = $GeocodingService->reverse($latitude, $longitude);
        if (!$location) {
            return response()->json(['error' => 'Standort konnte nicht bestimmt werden'], 500);
        }

        $weather = $weatherService->getWeather($latitude, $longitude);
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
        $categories = []; // später aus DB alle kategorien holen

        //ZUM TESTEN
        $categories = ['head', 'upper', 'lower', 'feet']; 
        $tags = [
            'sun',
            'rain',
            'cold',
            'winter',
            'summer',
            'light',
            'sport',
            'casual',
            'wind'
        ];
        $recommendations = [
            'head' => [
                [
                    'id' => 1,
                    'img' => '/images/konfig/head1_cosi.png',
                    'tags' => ['sun', 'summer']
                ],
                [
                    'id' => 2,
                    'img' => '/images/konfig/head2_cosi.png',
                    'tags' => ['rain', 'cold']
                ],
                [
                    'id' => 3,
                    'img' => '/images/konfig/head3_cosi.png',
                    'tags' => []
                ],
            ],

            'upper_shirt' => [
                [
                    'id' => 4,
                    'img' => '/images/konfig/shirt1_cosi.png',
                    'tags' => ['sun', 'light']
                ],
                [
                    'id' => 5,
                    'img' => '/images/konfig/shirt2_cosi.png',
                    'tags' => ['cold', 'winter']
                ],
                [
                    'id' => 6,
                    'img' => '/images/konfig/shirt3_cosi.png',
                    'tags' => ['casual']
                ],
                [
                    'id' => 19,
                    'img' => '/images/konfig/shirt4_cosi.png',
                    'tags' => ['sport']
                ],
            ],

            'upper_pulli' => [
                [
                    'id' => 7,
                    'img' => '/images/konfig/pulli1_cosi.png',
                    'tags' => ['cold', 'winter']
                ],
                [
                    'id' => 8,
                    'img' => '/images/konfig/pulli2_cosi.png',
                    'tags' => ['casual']
                ],
                [
                    'id' => 9,
                    'img' => '/images/konfig/pulli3_cosi.png',
                    'tags' => ['sport']
                ],
            ],

            'upper_jacke' => [
                [
                    'id' => 10,
                    'img' => '/images/konfig/jacke1_cosi.png',
                    'tags' => ['rain', 'wind']
                ],
                [
                    'id' => 11,
                    'img' => '/images/konfig/jacke2_cosi.png',
                    'tags' => ['cold', 'winter']
                ],
                [
                    'id' => 12,
                    'img' => '/images/konfig/jacke3_cosi.png',
                    'tags' => ['light']
                ],
            ],

            'lower' => [
                [
                    'id' => 13,
                    'img' => '/images/konfig/lower1_cosi.png',
                    'tags' => ['summer', 'light']
                ],
                [
                    'id' => 14,
                    'img' => '/images/konfig/lower2_cosi.png',
                    'tags' => ['casual']
                ],
                [
                    'id' => 15,
                    'img' => '/images/konfig/lower3_cosi.png',
                    'tags' => ['winter', 'cold']
                ],
            ],

            'feet' => [
                [
                    'id' => 16,
                    'img' => '/images/konfig/feet1_cosi.png',
                    'tags' => ['sport']
                ],
                [
                    'id' => 17,
                    'img' => '/images/konfig/feet2_cosi.png',
                    'tags' => ['rain']
                ],
                [
                    'id' => 18,
                    'img' => '/images/konfig/feet3_cosi.png',
                    'tags' => ['casual', 'summer']
                ],
            ],
        ];

        return view('welcome', [
            'recommendations' => $recommendations,
            'tags' => $tags,
            'categories' => $categories,
            'weather' => $weather,
            'current_time' => $currentTime,
            'location' => $location['display_name']
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