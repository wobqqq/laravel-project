<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Post;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Post\StatusResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes as ApiDocs;

#[ApiDocs\Group('Admin')]
#[ApiDocs\Subgroup('Post')]
#[ApiDocs\Authenticated]
class PostStatusController extends Controller
{
    /**
     * Get status
     */
    public function index(): JsonResource
    {
        $status = [
            PostStatus::ACTIVE->name => PostStatus::ACTIVE->value,
            PostStatus::INACTIVE->name => PostStatus::INACTIVE->value,
        ];

        return StatusResource::make($status);
    }
}
