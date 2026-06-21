<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Services\IAuthService;
use App\Services\IMixService;
use App\Services\IVoteService;
use App\Services\MixService;
use App\Services\VoteService;

class VoteController extends Controller
{
    private IVoteService $voteService;
    private IMixService $mixService;
    private IAuthService $authService;

    public function __construct()
    {
        $this->voteService = new VoteService();
        $this->mixService = new MixService();
        $this->authService = new AuthService();
    }

    public function getForMix($vars = [])
    {
        try {
            $mixId = (int)($vars['id'] ?? 0);

            if (!$this->mixService->getById($mixId)) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            $user = $this->getOptionalUser();

            return $this->sendSuccessResponse($this->voteService->getSummary($mixId, $user?->id));
        } catch (\Throwable $e) {
            return $this->sendVoteError($e);
        }
    }

    public function vote($vars = [])
    {
        try {
            $user = $this->requireUser();
            $mixId = (int)($vars['id'] ?? 0);

            if (!$this->mixService->getById($mixId)) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            $data = $this->getJsonInput();
            $summary = $this->voteService->vote($mixId, (int)$user->id, $data['voteType'] ?? '');

            return $this->sendSuccessResponse($summary);
        } catch (\Throwable $e) {
            return $this->sendVoteError($e);
        }
    }

    private function getOptionalUser(): ?User
    {
        $token = $this->getBearerToken();

        if (!$token) {
            return null;
        }

        $user = $this->authService->getUserFromToken($token);

        if (!$user) {
            throw new \RuntimeException('Invalid token', 401);
        }

        return $user;
    }

    private function requireUser(): User
    {
        $token = $this->getBearerToken();

        if (!$token) {
            throw new \RuntimeException('Invalid token', 401);
        }

        $user = $this->authService->getUserFromToken($token);

        if (!$user) {
            throw new \RuntimeException('Invalid token', 401);
        }

        return $user;
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

    private function sendVoteError(\Throwable $e)
    {
        $code = $e->getCode();

        if (!in_array($code, [400, 401, 403, 404], true)) {
            $code = 500;
        }

        $message = $code === 500 ? 'Internal server error' : $e->getMessage();

        return $this->sendErrorResponse($message, $code);
    }
}
