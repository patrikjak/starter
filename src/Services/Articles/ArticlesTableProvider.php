<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Articles;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\Articles\ArticlePolicy;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository;
use Patrikjak\Starter\Support\StringCropper;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class ArticlesTableProvider extends BasePaginatedTableProvider
{
    use StringCropper;

    private User $user;

    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly AuthManager $authManager,
    ) {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $this->user = $user;
    }

    public function getTableId(): string
    {
        return 'articles-table';
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
    {
        return [
            'title' => __('pjstarter::pages.articles.article_title'),
            'url' => 'URL',
            'category' => __('pjstarter::pages.articles.category'),
            'excerpt' => __('pjstarter::pages.articles.excerpt'),
            'status' => __('pjstarter::pages.articles.status'),
            'published_at' => __('pjstarter::pages.articles.published_at'),
            'created_at' => __('pjstarter::general.created_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $canViewArticle = $this->user->hasPermission(
            ArticlePolicy::FEATURE_NAME,
            BasePolicy::VIEW,
        );

        return $this->getPageData()->map(function (Article $article) use ($canViewArticle) {
            return [
                'id' => $article->id,
                'title' => $canViewArticle
                    ? CellFactory::link(
                        $article->title,
                        route('articles.show', ['article' => $article->id]),
                    )
                    : CellFactory::simple($article->title),
                'url' => CellFactory::link($article->getUrl(), $article->getUrl()),
                'category' => CellFactory::simple($article->articleCategory->name),
                'excerpt' => CellFactory::simple($this->getCroppedString($article->excerpt)),
                'status' => CellFactory::chip($article->status->toLabel(), match ($article->status) {
                    ArticleStatus::DRAFT => Type::NEUTRAL,
                    ArticleStatus::PUBLISHED => Type::SUCCESS,
                    ArticleStatus::ARCHIVED => Type::WARNING,
                }),
                'published_at' => CellFactory::simple(
                    $article->published_at === null
                        ? ''
                        : $article->published_at->format('d.m.Y H:i'),
                ),
                'created_at' => CellFactory::simple($article->created_at->format('d.m.Y H:i')),
            ];
        })->toArray();
    }

    public function showOrder(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        $actions = [];

        if ($this->user->hasPermission(ArticlePolicy::FEATURE_NAME, BasePolicy::EDIT)) {
            $actions[] = new Item(
                __('pjstarter::general.edit'),
                'edit',
                Icon::EDIT,
                href: static function (array $row) {
                    return route('articles.edit', ['article' => $row['id']]);
                },
            );
        }

        if ($this->user->hasPermission(ArticlePolicy::FEATURE_NAME, BasePolicy::DELETE)) {
            $actions[] = new Item(
                __('pjstarter::general.delete'),
                'delete',
                Icon::TRASH,
                Type::DANGER,
                href: static function (array $row) {
                    return route('api.articles.destroy', ['article' => $row['id']]);
                },
                method: 'DELETE',
            );
        }

        return $actions;
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->articleRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('api.articles.table-parts'),
        ));
    }
}