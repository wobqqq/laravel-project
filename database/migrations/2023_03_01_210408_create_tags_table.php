<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->tinyInteger('id')
                ->unsigned()
                ->autoIncrement();
            $table->string('name')
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
