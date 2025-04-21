<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Articles;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\Articles\ArticleCategoryPolicy;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Support\StringCropper;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class ArticleCategoriesTableProvider extends BasePaginatedTableProvider
{
    use StringCropper;

    private User $user;

    public function __construct(
        private readonly ArticleCategoryRepository $articleCategoryRepository,
        private readonly AuthManager $authManager,
    ) {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $this->user = $user;
    }

    public function getTableId(): string
    {
        return 'article_categories-table';
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
    {
        return [
            'name' => __('pjstarter::pages.articles.categories.name'),
            'url' => 'URL',
            'description' => __('pjstarter::pages.articles.categories.description'),
            'created_at' => __('pjstarter::general.created_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $canViewArticleCategory = $this->user->hasPermission(
            ArticleCategoryPolicy::FEATURE_NAME,
            BasePolicy::VIEW,
        );

        return $this->getPageData()->map(
            function (ArticleCategory $articleCategory) use ($canViewArticleCategory) {
                return [
                    'id' => $articleCategory->id,
                    'name' => $canViewArticleCategory
                        ? CellFactory::link(
                            $articleCategory->name,
                            route('articles.categories.show', ['articleCategory' => $articleCategory->id]),
                        )
                        : CellFactory::simple($articleCategory->name),
                    'url' => CellFactory::link($articleCategory->getUrl(), $articleCategory->getUrl()),
                    'description' => CellFactory::simple($this->getCroppedString($articleCategory->description)),
                    'created_at' => CellFactory::simple($articleCategory->created_at->format('d.m.Y H:i')),
                ];
            },
        )->toArray();
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

        if ($this->user->hasPermission(ArticleCategoryPolicy::FEATURE_NAME, BasePolicy::EDIT)) {
            $actions[] = new Item(
                __('pjstarter::general.edit'),
                'edit',
                Icon::EDIT,
                href: static function (array $row) {
                    return route('articles.categories.edit', ['articleCategory' => $row['id']]);
                },
            );
        }

        if ($this->user->hasPermission(ArticleCategoryPolicy::FEATURE_NAME, BasePolicy::DELETE)) {
            $actions[] = new Item(
                __('pjstarter::general.delete'),
                'delete',
                Icon::TRASH,
                Type::DANGER,
                href: static function (array $row) {
                    return route('api.articles.categories.destroy', ['articleCategory' => $row['id']]);
                },
                method: 'DELETE',
            );
        }

        return $actions;
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->articleCategoryRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('api.articles.categories.table-parts'),
        ));
    }
}