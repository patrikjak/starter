@use('Illuminate\Support\Facades\Vite')

@if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
    {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
    @push('scripts')
        <script src="{{ Vite::asset('resources/js/articles/article-editor.ts') }}" type="module" defer></script>
    @endpush
@else
    <script src="{{ asset('vendor/pjstarter/assets/article-editor.js') }}" defer type="module"></script>
@endif

<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.new')">

    <div class="new-article">
        <div class="grid grid-cols-5 gap-10 max-h-[90%]">
            <div class="article-content col-span-3 overflow-y-auto">
                <p class="font-bold font-primary text-2xl border-b pb-2">@lang('pjstarter::pages.articles.content')</p>
                <div
                    class="editorjs mt-4"
                    id="editorjs"
                    data-upload-image-url="{{ route('admin.api.articles.upload-image') }}"
                    data-fetch-image-url="{{ route('admin.api.articles.fetch-image') }}"
                ></div>
            </div>

            <div class="article-data col-span-2 overflow-y-auto">
                <div class="article-data-wrapper card">
                    <p class="title">@lang('pjstarter::pages.articles.other_info')</p>

                    <x-pjutils::form
                        :action-label="__('pjstarter::general.save')"
                        :action="route('admin.api.articles.store')"
                        method="POST"
                        :redirect="route('admin.articles.index')"
                        id="article-form"
                    >
                        <x-pjutils::form.input name="title" :label="__('pjstarter::pages.articles.article_title')" />

                        <x-pjutils::form.select
                            name="category"
                            :label="__('pjstarter::pages.articles.category')"
                            :options="$articleCategories"

                        />

                        <x-pjutils::form.select
                            name="author"
                            :label="__('pjstarter::pages.articles.author')"
                            :options="$authors"
                        />

                        <x-pjutils::form.textarea name="excerpt" :label="__('pjstarter::pages.articles.excerpt')" />
                        <x-pjutils::photo-uploader name="featured_image" :label="__('pjstarter::pages.articles.featured_image')" />
                        <x-pjutils::form.select name="status" :label="__('pjstarter::pages.articles.status')" :options="$statuses" />
                        <x-pjutils::form.input name="read_time" :label="__('pjstarter::pages.articles.read_time')" />

                    </x-pjutils::form>
                </div>
            </div>
        </div>
    </div>

</x-pjstarter::layout.app>