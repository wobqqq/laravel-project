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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Tag extends Model
{
    use HasFactory;

    /** @var string[] */
    protected $fillable = ['name'];

    public function getAdminFiltered(AdminTagFilterDto $dto): LengthAwarePaginator
    {
        return Tag::orderBy($dto->sort_by, $dto->sort_direction)
            ->paginate(page: $dto->page);
    }

    public function getClientFiltered(ClientTagFilterDto $dto): Collection
    {
        return Tag::orderBy($dto->sort_by, $dto->sort_direction)->get();
    }
}
