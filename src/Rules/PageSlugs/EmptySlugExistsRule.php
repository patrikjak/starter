<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Rules\PageSlugs;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Patrikjak\Starter\Repositories\Contracts\PageSlugs\PageSlugRepository;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class EmptySlugExistsRule implements ValidationRule
{
    public bool $implicit = true;

    protected ?string $prefix = null;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pageSlugRepository = app(PageSlugRepository::class);

        $emptyPageSlug = $pageSlugRepository->existsSameSlug('', $this->prefix);

        if ($emptyPageSlug === null) {
            return;
        }

        $fail(trans_choice('pjutils::validation.unique', GrammaticalGender::MASCULINE));
    }

    public function setPrefix(?string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }
}
