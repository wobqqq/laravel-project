<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\Admin\Post\PostFilterDto as AdminPostFilterDto;
use App\DTOs\Client\Post\PostFilterDto as ClientPostFilterDto;
use App\Enums\PostStatus;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property PostStatus $status
 * @property string $slug
 * @property int $category_id
 * @property string $preview_text
 * @property string $content
 * @property bool $is_hot
 * @property \Illuminate\Support\Carbon $published_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 *
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static Builder|Post findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post query()
 * @method static Builder|Post whereCategoryId($value)
 * @method static Builder|Post whereContent($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereIsHot($value)
 * @method static Builder|Post wherePreviewText($value)
 * @method static Builder|Post wherePublishedAt($value)
 * @method static Builder|Post whereSlug($value)
 * @method static Builder|Post whereStatus($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;
    use Sluggable;

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'preview_text',
        'content',
        'is_hot',
        'published_at',
        'status',
    ];

    protected $casts = [
        'is_hot' => 'boolean',
        'published_at' => 'datetime',
        'status' => PostStatus::class,
    ];

    /**
     * @return array<string, string>[]
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    /**
     * @return BelongsTo<Category, Post>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return LengthAwarePaginator<Post>
     */
    public function getAdminFiltered(AdminPostFilterDto $dto): LengthAwarePaginator
    {
        return Post::with('tags')
            ->orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }

    /**
     * @return LengthAwarePaginator<Post>|LengthAwarePaginator<Model>
     */
    public function getClientFiltered(ClientPostFilterDto $dto): LengthAwarePaginator
    {
        return Post::where(function (Builder|Post $q) {
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
