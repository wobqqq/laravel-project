<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\Admin\Category\CategoryFilterDto as AdminCategoryFilterDto;
use App\DTOs\Client\Category\CategoryFilterDto as ClientCategoryFilterDto;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;
    use Sluggable;

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * @return array<string, string>[]
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * @return LengthAwarePaginator<Category>
     */
    public function getAdminFiltered(AdminCategoryFilterDto $dto): LengthAwarePaginator
    {
        return Category::orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }

    /**
     * @return Collection<int, Category>
     */
    public function getClientFiltered(ClientCategoryFilterDto $dto): Collection
    {
        return Category::orderBy($dto->sort_by, $dto->sort_direction)->get();
    }
}
