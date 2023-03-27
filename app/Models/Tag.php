<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\Admin\Tag\TagFilterDto as AdminTagFilterDto;
use App\DTOs\Client\Tag\TagFilterDto as ClientTagFilterDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 *
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 *
 * @mixin \Eloquent
 */
class Tag extends Model
{
    use HasFactory;

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $fillable = ['name'];

    /**
     * @return LengthAwarePaginator<Tag>
     */
    public function getAdminFiltered(AdminTagFilterDto $dto): LengthAwarePaginator
    {
        return Tag::orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getClientFiltered(ClientTagFilterDto $dto): Collection
    {
        return Tag::orderBy($dto->sort_by, $dto->sort_direction)->get();
    }
}
