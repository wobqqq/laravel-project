<?php

declare(strict_types=1);

namespace App\Http\Requests\Traits;

trait RetrieveInputItemFromRequest
{
    public function page(int $default = 1): int
    {
        return $this->integer('page', $default);
    }

    public function sortBy(string $default = 'id'): string
    {
        $sort_by = $this->input('sort_by') ?? $default;

        return strval($sort_by);
    }

    public function sortDirection(string $default = 'desc'): string
    {
        $sort_direction = $this->input('sort_direction') ?? $default;

        return strval($sort_direction);
    }

    public function stringOrNull(string $key): ?string
    {
        return $this->str($key)->isNotEmpty()
            ? $this->str($key)->toString()
            : null;
    }

    /**
     * @return int[]|string[]
     */
    public function array(string $key): array
    {
        return is_array($this->input($key))
            ? $this->input($key)
            : [];
    }

    /**
     * @return int[]|string[]
     */
    public function arrayOrNull(string $key): array
    {
        return is_array($this->input($key))
            ? $this->input($key)
            : [];
    }
}
