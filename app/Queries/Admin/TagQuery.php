<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\DTOs\Admin\Tag\TagFilterDto;
use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

class TagQuery
{
    public function __construct(
        private readonly Tag $tag,
    ) {
    }

    /**
     * @return LengthAwarePaginator<Tag>
     */
    public function getFiltered(TagFilterDto $dto): LengthAwarePaginator
    {
        return $this->tag->orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }
}
