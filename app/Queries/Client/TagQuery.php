<?php

declare(strict_types=1);

namespace App\Queries\Client;

use App\DTOs\Client\Tag\TagFilterDto;
use App\Models\Tag;
use Illuminate\Support\Collection;

class TagQuery
{
    public function __construct(
        private readonly Tag $tag,
    ) {
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getFiltered(TagFilterDto $dto): Collection
    {
        return $this->tag->orderBy($dto->sort_by, $dto->sort_direction)->get();
    }
}
