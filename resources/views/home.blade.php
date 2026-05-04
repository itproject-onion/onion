<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>on¿on - Konfigurator</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    <script>
        window.wardrobe_inventory = @json($recommendations);
    </script>
</head>

<body>

<main class="wrapper">
    <aside class="side-area branding">
        <div class="brand-content">
            <h1 class="onion-title">on¿on</h1>
            <p class="onion-subtitle">Was ziehst du<br>heute an?</p>
            <button type="submit" form="outfit-form" class="anziehen-btn pc-only">DAS HIER!</button>
        </div>
    </aside>

    <section class="main-configurator">
        <form action="{{ route('outfit.save') }}" method="POST" id="outfit-form">
            @csrf

            @foreach($categories as $cat)
                <div class="outfit-capsule" data-category="{{ $cat }}">

                    @if($cat === 'upper')
                        <div class="layer-selection-grid">
                            <div class="layer-square" data-layer="upper_shirt">
                                <div class="square-content">
                                    <i class="bi bi-plus-lg plus-icon"></i>
                                    <img src="" class="square-image">
                                </div>
                            </div>
                            <div class="layer-square" data-layer="upper_pulli">
                                <div class="square-content">
                                    <i class="bi bi-plus-lg plus-icon"></i>
                                    <img src="" class="square-image">
                                </div>
                            </div>
                            <div class="layer-square" data-layer="upper_jacke">
                                <div class="square-content">
                                    <i class="bi bi-plus-lg plus-icon"></i>
                                    <img src="" class="square-image">
                                </div>
                            </div>
                        </div>

                        <div class="layer-carousel-view d-none">
                            <button type="button" class="nav-arrow prev"><i class="bi bi-chevron-left"></i></button>
                            <div class="view-window">
                                <img src="" class="item-side item-prev">
                                <div class="main-item-wrapper">
                                    <img src="" class="item-main shadow-aura">
                                    <div class="select-trigger-overlay"></div>
                                </div>
                                <img src="" class="item-side item-next">
                            </div>
                            <button type="button" class="nav-arrow next"><i class="bi bi-chevron-right"></i></button>
                        </div>

                        <input type="hidden" name="upper_shirt_id" id="input-upper_shirt">
                        <input type="hidden" name="upper_pulli_id" id="input-upper_pulli">
                        <input type="hidden" name="upper_jacke_id" id="input-upper_jacke">

                    @else
                        <button type="button" class="nav-arrow prev"><i class="bi bi-chevron-left"></i></button>
                        <div class="view-window">
                            <img src="" class="item-side item-prev">
                            <div class="main-item-wrapper">
                                <img src="" class="item-main shadow-aura">
                                <div class="select-trigger-overlay"></div>
                            </div>
                            <img src="" class="item-side item-next">
                        </div>
                        <button type="button" class="nav-arrow next"><i class="bi bi-chevron-right"></i></button>
                        <input type="hidden" name="{{ $cat }}_id" id="input-{{ $cat }}">
                    @endif
                </div>
            @endforeach
        </form>
        <button type="submit" form="outfit-form" class="anziehen-btn mobile-only">ANZIEHEN</button>
    </section>

    <!--wetter blcok ist grad einfach nur platzhalter den müsstet ihr bitte noch dynamsich befüllen (je nach dem wie du die sachen in der db dann final benennst...-->
    <aside class="side-area info">
        <div class="weather-desktop">
            <div class="weather-city"><i class="bi bi-geo-alt-fill"></i>{{ $location }}</div>
            <h2 class="display-temp">{{ $weather['apparentTemperature'][$current_time] }}</h2>
            <p class="condition">windig ANPASSEN</p>
            <div class="weather-icons">
                <i class="bi bi-sun-fill main-sun">ANPASSEN</i>
                <i class="bi bi-cloud-fill overlap-cloud">ANPASSEN</i>
            </div>
            <div class="recommendation-box">
                <p>hier ist theoretisch noch platz für einen kleinen infotext zum wetter oder so idk</p>
            </div>
        </div>
    </aside>

    //tags ZUM TESTEN
    <div class="grid" id="tags">
        @foreach($tags as $index => $tag)
            <div>
                <input type="checkbox" id="tag-{{ $index }}" value="{{ $tag }}"/>
                <label for="tag-{{ $index }}">{{ $tag }}</label>
            </div>
        @endforeach
    </div>

</main>

</body>
</html>