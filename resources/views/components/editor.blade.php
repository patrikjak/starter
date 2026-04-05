@use('Illuminate\Support\Facades\Vite')

@push('scripts')
    @if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <script src="{{ Vite::asset('resources/js/content-editor.ts') }}" type="module" defer></script>
    @else
        <script src="{{ asset('vendor/pjstarter/assets/content-editor.js') }}" defer type="module"></script>
   @endif
@endpush

<div
    class="editorjs"
    id="editorjs"
    data-tools="{{ $toolsValue() }}"
    @if($resolvedUploadUrl()) data-upload-image-url="{{ $resolvedUploadUrl() }}" @endif
    @if($resolvedFetchUrl()) data-fetch-image-url="{{ $resolvedFetchUrl() }}" @endif
    @if($contentUrl) data-content-url="{{ $contentUrl }}" @endif
></div>
