<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Admin\Tag\TagDto;
use App\Models\Tag;

class TagService
{
    public function store(TagDto $tagDto): Tag
    {
        return Tag::create(['name' => $tagDto->name]);
    }

    public function update(TagDto $tagDto, Tag $tag): Tag
    {
        $tag->update(['name' => $tagDto->name]);

        return $tag;
    }
}
