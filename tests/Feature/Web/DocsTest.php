<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DocsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function return_successful_result(): void
    {
        $this->get('/docs')
            ->assertOk();
    }
}
