<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SelectedOutfit;

class ProfileController extends Controller
{
    public function index()
    {
        /* Sollte theoretisch sowas liefern
        [
            {
                "id": 1,
                "name": "Winterjacke",
                "filepath": "/img/jacket.jpg",
                "is_waterproof": true,
                "cloudcoverthreshold": null,
                "maxuv": null,
                "maxtemp": 10,
                "mintemp": -5,

                "category": {
                "id": 2,
                "categoryname": "Jacken",
                "impactedbyrain": true
                },

                "tags": [
                { "id": 1, "name": "warm" },
                { "id": 2, "name": "winter" }
                ]
            }
        ]
        */
        $kleidungsstuecke = Item::with(['category', 'tags'])
            ->where('user_id', Auth::id())
            ->get();
        $tags = Tag::where('user_id', Auth::id())
            ->get();
        $categories = Category::all();
        $unreviewedOutfitsByDay = SelectedOutfit::where('user_id', auth()->id())
            ->where('has_been_reviewed', false)
            ->get()
            ->groupBy(fn($outfit) => $outfit->created_at->toDateString())
            ->map(function ($group) {
                return $group->pluck('item_id');
            });
        /*
        liefert theoretisch sowas
        [
            '2026-05-15' => [12, 44, 91], //Datum, item-ids
            '2026-05-16' => [7, 22],
        ]
        */

        //$recommendationsForFrontend = $this->formatForFrontend($kleidungsstuecke); //BEI GLEICHER DATENSTRUKTUR WIE BEI HOME-VIEW: Das hier auskommentieren und statt $kleidungsstücke, $recommendationsForFrontend an view übergeben.

        return view('profile', [
            'items' => $kleidungsstuecke,
            'tags' => $tags,
            'categories' => $categories,
            'unreviewedOutfitsByDay' => $unreviewedOutfitsByDay
        ]);
    }


    private function formatForFrontend($recommendations) {
        $categoryMap = [
            'Kopfbedeckung'   => 'head',

            'T-Shirt'         => 'upper_shirt',
            'Pullover'        => 'upper_pulli',
            'Jacke'           => 'upper_jacke',

            'Hose'            => 'lower_pants',
            'Strumpfhose'     => 'lower_tights',

            'Socken'          => 'feet_socks',
            'Schuhe'          => 'feet_shoes',

            'Accessoires' => 'hand',
            'Sonnenbrille'    => 'sunglasses',
            'Sonnencreme'     => 'sunscreen',
        ];

        $result = [
            'head' => [],

            'upper_shirt' => [],
            'upper_pulli' => [],
            'upper_jacke' => [],

            'lower_pants' => [],
            'lower_tights' => [],

            'feet_socks' => [],
            'feet_shoes' => [],

            'hand' => [],
            'sunglasses' => [],
            'sunscreen' => [],
        ];

        foreach ($recommendations as $item) {
            if (!$item->category) continue;

            $key = $categoryMap[$item->category->name] ?? null;
            if (!$key) continue;

            $result[$key][] = [
                'id' => $item->id,
                'img' => $item->filepath,
                'name' => $item->name,
                'waterproof' => $item->is_waterproof,
                'cloudcoverthreshold' => $item->cloud_cover_threshold,
                'maxuv' => $item->max_uv_index,
                'minuv' => $item->min_uv_index,
                'maxtemp' => $item->max_temperature,
                'mintemp' => $item->min_temperature,
                'creationdate' => $item->created_at,
                // TAGS (id + name)
                'tags' => $item->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                })->toArray(),
            ];
        }
        return $result;
    }

}