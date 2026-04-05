@use('Patrikjak\Starter\Enums\Editorjs\EditorTool')
@use('Illuminate\Support\Collection')

<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.new')">

    <div class="new-article article-editor-layout">
        <div class="article-data">
            <x-pjutils::form
                :action-label="__('pjstarter::general.save')"
                :action="route('admin.api.articles.store')"
                method="POST"
                :redirect="route('admin.articles.index')"
                id="article-form"
            >
                <x-pjutils::accordion-group>
                    <x-pjutils::accordion :title="__('pjstarter::pages.articles.accordion.basic_info')" :open="true">
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
                        <x-pjutils::form.select
                            name="status"
                            :label="__('pjstarter::pages.articles.status')"
                            :options="$statuses"
                        />
                        <x-pjutils::form.input
                            name="read_time"
                            :label="__('pjstarter::pages.articles.read_time')"
                        />
                        <x-pjutils::form.textarea name="excerpt" :label="__('pjstarter::pages.articles.excerpt')" />
                    </x-pjutils::accordion>

                    <x-pjutils::accordion :title="__('pjstarter::pages.articles.accordion.media')">
                        <x-pjutils::photo-uploader name="featured_image" :label="__('pjstarter::pages.articles.featured_image')" />
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
            />
        </div>
    </div>

</x-pjstarter::layout.app>
