//items
<div class="grid">
    @foreach($recommendations as $item)
        @if($item['kategorie'] === 'sonnenbrille')
            <div class="item" data-tags='@json($item["tags"])'>
                <h3>{{ $item['name'] }}</h3>
                <span class="badge">{{ $item['kategorie'] }}</span>
            </div>
        @endif
    @endforeach
</div>


//tags
<div class="grid" id="tags">
    @foreach($tags as $index => $tag)
        <div>
            <input type="checkbox" id="tag-{{ $index }}" value="{{ $tag }}"/>
            <label for="tag-{{ $index }}">{{ $tag }}</label>
        </div>
    @endforeach
</div>

@vite('resources/js/filterTags.js')