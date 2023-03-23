<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\AuthDto;
use App\Transformers\AuthDtoTransformer;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function getDto(): AuthDto
    {
        return AuthDtoTransformer::fromRequest($this);
    }
}
