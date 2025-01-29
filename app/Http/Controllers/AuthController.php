<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AuthInterface;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthInterface $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authService->register($request->validated());
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request->validated());
    }

    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    public function refresh(): JsonResponse
    {
        return $this->authService->refresh();
    }

    public function me(): JsonResponse
    {
        return $this->authService->getAuthenticatedUser();
    }
}
