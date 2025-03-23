<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Rules\Slugs;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class EmptySlugExistsRule implements ValidationRule
{
    public bool $implicit = true;

    protected ?string $prefix = null;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slugRepository = app(SlugRepository::class);

        $emptySlug = $slugRepository->existsSameSlug('', $this->prefix);

        if ($emptySlug === null) {
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
