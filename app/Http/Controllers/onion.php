<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class onion extends Controller
{
    // GET-Request
    public function show()
    {
        $message = 'Hallo von PHP!';

        return view('onion', ['message' => $message]);
    }
}
