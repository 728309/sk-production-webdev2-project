<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Inheritance\Services\IAuthService;
use App\Services\AuthService;

class AuthController extends Controller
{
    private IAuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register()
    {
        try {
            $data = $this->getJsonInput();

            $result = $this->authService->register(
                trim($data['name'] ?? ''),
                trim($data['email'] ?? ''),
                $data['password'] ?? ''
            );

            return $this->sendSuccessResponse($result, 201);
        } catch (\Throwable $e) {
            return $this->sendApiError($e, [400, 401, 409]);
        }
    }

    public function login()
    {
        try {
            $data = $this->getJsonInput();

            $result = $this->authService->login(
                trim($data['email'] ?? ''),
                $data['password'] ?? ''
            );

            return $this->sendSuccessResponse($result);
        } catch (\Throwable $e) {
            return $this->sendApiError($e, [400, 401, 409]);
        }
    }

    public function me()
    {
        try {
            $user = $this->requireUser($this->authService);

            return $this->sendSuccessResponse($this->authService->userToArray($user));
        } catch (\Throwable $e) {
            return $this->sendApiError($e, [400, 401, 409]);
        }
    }
}
