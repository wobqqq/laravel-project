<?php

declare(strict_types=1);

namespace App\Queries\Client;

use App\DTOs\Client\Post\PostFilterDto;
use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PostQuery
{
    public function __construct(
        private readonly Post $post,
    ) {
    }

    /**
     * @return LengthAwarePaginator<Post>|LengthAwarePaginator<Model>
     */
    public function getFiltered(PostFilterDto $dto): LengthAwarePaginator
    {
        return $this->post->where(function (Builder|Post $q) {
            $q->whereStatus(PostStatus::ACTIVE->value)
                ->useIndex('active');
        })
            ->when($dto->is_hot, function (Builder|Post $q) use ($dto) {
                return $q->whereIsHot($dto->is_hot)
                    ->useIndex('is_hot');
            })
            ->when($dto->category !== null, function (Builder $q) use ($dto) {
                $q->whereRelation('category', 'slug', $dto->category);
            })
            ->when($dto->tags !== [], function (Builder $q) use ($dto) {
                $q->whereRelation('tags', function (Builder $q) use ($dto) {
                    $q->whereIn('name', $dto->tags);
                });
            })
            ->with('tags', 'category')
            ->orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }
}
