<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->constrained('tags')
                ->onDelete('cascade');
            $table->string('taggable_type');
            $table->integer('taggable_id')
                ->unsigned();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
