<?php

declare(strict_types=1);

namespace App\Transformers;

use App\DTOs\AuthDto;
use App\Http\Requests\AuthRequest;

class AuthDtoTransformer
{
    public static function fromRequest(AuthRequest $request): AuthDto
    {
        return new AuthDto(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );
    }
}
