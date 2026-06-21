<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Services\AuthService;
use App\Services\IAuthService;

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
            return $this->sendAuthError($e);
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
            return $this->sendAuthError($e);
        }
    }

    public function me()
    {
        try {
            $token = $this->getBearerToken();

            if (!$token) {
                return $this->sendErrorResponse('Invalid token', 401);
            }

            $user = $this->authService->getUserFromToken($token);

            if (!$user) {
                return $this->sendErrorResponse('Invalid token', 401);
            }

            return $this->sendSuccessResponse($this->authService->userToArray($user));
        } catch (\Throwable $e) {
            return $this->sendAuthError($e);
        }
    }

    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        return is_array($data) ? $data : [];
    }

    private function getBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if (preg_match('/Bearer\s+(\S+)/', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function sendAuthError(\Throwable $e)
    {
        $code = $e->getCode();

        if (!in_array($code, [400, 401, 409], true)) {
            $code = 500;
        }

        $message = $code === 500 ? 'Internal server error' : $e->getMessage();

        return $this->sendErrorResponse($message, $code);
    }
}
