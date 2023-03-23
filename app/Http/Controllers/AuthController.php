<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes as ApiDocs;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;

#[ApiDocs\Group('Auth')]
class AuthController extends Controller
{
    #[ApiDocs\BodyParam('email', 'string', example: 'test@example.net')]
    #[ApiDocs\BodyParam('password', 'string', example: 'secret')]
    public function login(AuthRequest $request): JsonResponse
    {
        $dto = $request->getDto();
        /** @var bool|string $token */
        $token = auth()->attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        return $this->response($token);
    }

    #[ApiDocs\Header('Authorization', 'Bearer {token}')]
    public function refresh(): JsonResponse
    {
        /** @var bool|string $token */
        $token = auth()->refresh();

        return $this->response($token);
    }

    private function response(bool|string $token): JsonResponse
    {
        $isUnauthorized = ! $token;

        if ($isUnauthorized) {
            return response()->json(['message' => 'Unauthorized'], HttpStatusCode::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.refresh_ttl'),
        ]);
    }
}
