<?php

namespace App\Framework;

use App\Models\User;
use App\Services\AuthService;
use App\Services\IAuthService;

class Controller
{
    public function __construct()
    {
    }

    protected function sendSuccessResponse($data = [], $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    protected function sendErrorResponse($message, $code = 500)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode(['error' => $message], JSON_PRETTY_PRINT);
    }

    protected function sendApiError(\Throwable $error, array $allowedCodes = [400, 401, 403, 404, 409])
    {
        $code = $error->getCode();

        if (!in_array($code, $allowedCodes, true)) {
            $code = 500;
        }

        $message = $code === 500 ? 'Internal server error' : $error->getMessage();

        return $this->sendErrorResponse($message, $code);
    }

    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');

        if (trim($input) === '') {
            return [];
        }

        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON body', 400);
        }

        return $data;
    }

    protected function getBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if (preg_match('/Bearer\s+(\S+)/', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function getOptionalUser(?IAuthService $authService = null): ?User
    {
        $token = $this->getBearerToken();

        if (!$token) {
            return null;
        }

        $authService = $authService ?? new AuthService();
        $user = $authService->getUserFromToken($token);

        if (!$user) {
            throw new \RuntimeException('Invalid token', 401);
        }

        return $user;
    }

    protected function requireUser(?IAuthService $authService = null): User
    {
        $token = $this->getBearerToken();

        if (!$token) {
            throw new \RuntimeException('Invalid token', 401);
        }

        $authService = $authService ?? new AuthService();
        $user = $authService->getUserFromToken($token);

        if (!$user) {
            throw new \RuntimeException('Invalid token', 401);
        }

        return $user;
    }

    protected function requireAdmin(?IAuthService $authService = null): User
    {
        $user = $this->requireUser($authService);

        if ($user->role !== 'admin') {
            throw new \RuntimeException('Admin access required', 403);
        }

        return $user;
    }

    /**
     * Maps POST data (JSON) to an instance of the specified class
     * 
     * @param string $className The fully qualified class name
     * @return object|null Returns an instance of the class or null if data is invalid
     */
    protected function mapPostDataToClass(string $className): ?object
    {
        $data = $this->getJsonInput();

        $instance = new $className();
        
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->$key = $value;
            }
        }

        return $instance;
    }
}
