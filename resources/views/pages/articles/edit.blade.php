@use('Patrikjak\Starter\Enums\Editorjs\EditorTool')
@use('Illuminate\Support\Collection')

<x-pjstarter::layout.app :title="sprintf('%s: %s', __('pjstarter::pages.articles.edit'), $article->title)">

    <div class="edit-article article-editor-layout">
        <div class="article-data">
            <x-pjutils::form
                :action="route('admin.api.articles.update', ['article' => $article->id])"
                method="PUT"
                :redirect="route('admin.articles.index')"
                :action-label="null"
                id="article-form"
            >
                <x-pjutils::accordion-group>
                    <x-pjutils::accordion :title="__('pjstarter::pages.articles.accordion.basic_info')" :open="true">
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
                        <x-pjutils::form.textarea
                            name="excerpt"
                            :label="__('pjstarter::pages.articles.excerpt')"
                            :value="$article->excerpt"
                        />
                    </x-pjutils::accordion>

                    <x-pjutils::accordion :title="__('pjstarter::pages.articles.accordion.media')">
                        <x-pjutils::photo-uploader
                            name="featured_image"
                            :label="__('pjstarter::pages.articles.featured_image')"
                            :value="$featuredImage"
                        />
                    </x-pjutils::accordion>
                </x-pjutils::accordion-group>

                <x-pjutils::button type="submit">
                    @lang('pjstarter::general.save')
                </x-pjutils::button>
            </x-pjutils::form>

            <x-pjutils::form
                :action="route('admin.api.slugs.update', ['slug' => $article->slug->id])"
                method="PUT"
                :redirect="route('admin.articles.index')"
                :action-label="null"
            >
                <x-pjutils::accordion-group class="mt-4">
                    <x-pjutils::accordion :title="__('pjstarter::pages.slug.url')">
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
                        <x-pjutils::button type="submit">
                            @lang('pjstarter::general.save')
                        </x-pjutils::button>
                    </x-pjutils::accordion>
                </x-pjutils::accordion-group>
            </x-pjutils::form>
        </div>

        <div class="article-content">
            <p class="article-section-title">@lang('pjstarter::pages.articles.content')</p>
            <x-pjstarter::editor
                :tools="Collection::make([
                    EditorTool::Header,
                    EditorTool::List,
                    EditorTool::Image,
                    EditorTool::Raw,
                    EditorTool::Underline,
                ])"
                context="articles"
                :content-url="route('admin.api.articles.content', ['article' => $article->id])"
            />
        </div>
    </div>

</x-pjstarter::layout.app>
