<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->smallInteger('id')
                ->unsigned()
                ->autoIncrement();
            $table->string('title');
            $table->tinyInteger('status')
                ->default(PostStatus::ACTIVE->value);
            $table->string('slug')
                ->unique();
            $table->tinyInteger('category_id')
                ->unsigned();
            $table->string('preview_text', 1000);
            $table->text('content');
            $table->boolean('is_hot');
            $table->dateTime('published_at');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE posts ADD INDEX active (status, published_at DESC)');
        DB::statement('ALTER TABLE posts ADD INDEX is_hot (status, is_hot, published_at DESC)');
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
