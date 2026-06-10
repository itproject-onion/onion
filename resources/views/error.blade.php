@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-2xl p-8 text-center max-w-md">

        <p class="text-gray-600 mb-6">
            {{ $error_message }}
        </p>

        <a href="/"
           class="bg-black text-white px-4 py-2 rounded-lg">
            Zurück
        </a>

    </div>

</div>

@endsection