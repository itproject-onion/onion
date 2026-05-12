{{-- Header --}}
<div class="header">
    <h1 class="title is-1">Tag erstellen</h1>
</div>

<div class="form-wrapper">
    <form action="{{ route('tags.store') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input type="text" class="input @error('name') is-danger @enderror" name="name"
                    value="{{ old('name') }}" placeholder="z. B. 'Sommer'" required>
            </div>

            @error('name')
                <p class="has-text-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- submit button --}}
        <div class="control submit-button">
            <button class="button is-info">Tag erstellen</button>
        </div>

    </form>
</div>
