<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Admin\Post\PostDto;
use App\Models\Post;

class PostService
{
    public function store(PostDto $postDto): Post
    {
        $post = Post::create([
            'title' => $postDto->title,
            'status' => $postDto->status,
            'category_id' => $postDto->category_id,
            'preview_text' => $postDto->preview_text,
            'content' => $postDto->content,
            'is_hot' => $postDto->is_hot,
            'published_at' => $postDto->published_at,
        ]);
        $post->tags()->sync($postDto->tag_ids);

        return $post;
    }

    public function update(PostDto $postDto, Post $post): Post
    {
        $post->update([
            'title' => $postDto->title,
            'status' => $postDto->status,
            'category_id' => $postDto->category_id,
            'slug' => $postDto->slug,
            'preview_text' => $postDto->preview_text,
            'content' => $postDto->content,
            'is_hot' => $postDto->is_hot,
            'published_at' => $postDto->published_at,
        ]);
        $post->tags()->sync($postDto->tag_ids);

        return $post;
    }
}
