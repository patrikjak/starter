@use('Illuminate\Support\Facades\Vite')

@if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
    {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
    @push('scripts')
        <script src="{{ Vite::asset('resources/js/articles/article-editor.ts') }}" type="module" defer></script>
    @endpush
@else
    <script src="{{ asset('vendor/pjstarter/assets/article-editor.js') }}" defer type="module"></script>
@endif

<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.articles.edit'), $article->title)">

    <div class="edit-article">
        <div class="grid grid-cols-5 gap-10 max-h-[90%]">
            <div class="article-content col-span-3 overflow-y-auto">
                <p class="font-bold font-primary text-2xl border-b pb-2">@lang('pjstarter::pages.articles.content')</p>
                <div
                    class="editorjs mt-4"
                    id="editorjs"
                    data-upload-image-url="{{ route('api.articles.upload-image') }}"
                    data-fetch-image-url="{{ route('api.articles.fetch-image') }}"
                    data-article-content-url="{{ route('api.articles.content', ['article' => $article->id]) }}"
                ></div>
            </div>

            <div class="article-data col-span-2 overflow-y-auto">
                <div class="article-data-wrapper card">
                    <p class="title">@lang('pjstarter::pages.articles.other_info')</p>

                    <x-pjutils::form
                        :action-label="__('pjstarter::general.edit')"
                        :action="route('api.articles.update', ['article' => $article->id])"
                        method="PUT"
                        :redirect="route('articles.index')"
                        id="article-form"
                    >
                        <x-pjutils::form.input
                            name="title"
                            :label="__('pjstarter::pages.articles.article_title')"
                            :value="$article->title"
                        />

                        <x-pjutils::form.select
                            name="category"
                            :label="__('pjstarter::pages.articles.category')"
                            :options="$articleCategories"
                            :value="$article->articleCategory->id"
                        />

                        <x-pjutils::form.select
                            name="author"
                            :label="__('pjstarter::pages.articles.author')"
                            :options="$authors"
                            :value="$article->author->id"
                        />

                        <x-pjutils::form.textarea
                            name="excerpt"
                            :label="__('pjstarter::pages.articles.excerpt')"
                            :value="$article->excerpt"
                        />

                        <x-pjutils::photo-uploader
                            name="featured_image"
                            :label="__('pjstarter::pages.articles.featured_image')"
                            :value="$featuredImage"
                        />

                        <x-pjutils::form.select
                            name="status"
                            :label="__('pjstarter::pages.articles.status')"
                            :options="$statuses"
                            :value="$article->status->value"
                        />

                        <x-pjutils::form.input
                            name="read_time"
                            :label="__('pjstarter::pages.articles.read_time')"
                            :value="$article->read_time"
                        />

                    </x-pjutils::form>
                </div>

                <div class="card mt-4">
                    <p class="title">@lang('pjstarter::pages.slug.url')</p>

                    <x-pjutils::form
                        :action="route('api.slugs.update', ['slug' => $article->slug->id])"
                        method="PUT"
                        :action-label="__('pjstarter::general.edit')"
                        :redirect="route('articles.index')"
                    >
                        <x-pjutils::form.input
                            name="prefix"
                            :label="__('pjstarter::pages.slug.prefix')"
                            :value="$article->slug->prefix"
                        />

                        <x-pjutils::form.input
                            name="slug"
                            :label="__('pjstarter::pages.slug.slug')"
                            :value="$article->slug->slug"
                        />
                    </x-pjutils::form>
                </div>
            </div>
        </div>
    </div>

</x-pjstarter::layout.app>