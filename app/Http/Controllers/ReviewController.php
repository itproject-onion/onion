<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SelectedOutfit;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $items = SelectedOutfit::with([
                'item.category',
                'item.tags'
            ])
            ->where('user_id', auth()->id())
            ->where('has_been_reviewed', false)
            ->whereDate('created_at', $date)
            ->get()
            ->map(function ($entry) {
                return [
                    'outfit_entry_id' => $entry->id,
                    'outfit_date' => $entry->created_at->toDateString(),

                    ...$entry->item->toArray()
                ];
            });
         /* Sollte theoretisch sowas liefern
        [
            {
                "outfit_entry_id": 15,
                "outfit_date": "2026-05-15",

                "id": 12,
                "name": "Winterjacke",
                "filepath": "/img/jacket.jpg",

                "category": {
                    ...
                },

                "tags": [
                    ...
                ]
            }
        ]
        */

        if ($items->isEmpty()) {
            return view('error', ['error_message' => "Anscheinend gibt es hier nichts zu reviewen :)"]);
        } else {
            return view('review', ['items' => $items]);
        }
    }

}