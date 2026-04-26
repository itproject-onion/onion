<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function reverse($lat, $lon)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'OnionApp/1.0'
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'json',
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}