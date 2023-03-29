<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\DTOs\Admin\Post\PostFilterDto;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostQuery
{
    public function __construct(
        private readonly Post $post,
    ) {
    }

    /**
     * @return LengthAwarePaginator<Post>
     */
    public function getFiltered(PostFilterDto $dto): LengthAwarePaginator
    {
        return $this->post->with('tags')
            ->orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }
}
