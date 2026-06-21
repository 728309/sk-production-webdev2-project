<?php

namespace App\Controllers;

use App\Framework\Controller;
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

            $mix = $this->mixService->getById($mixId);

            if (!$mix || $mix->status !== 'published') {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            $user = $this->getOptionalUser($this->authService);

            return $this->sendSuccessResponse($this->voteService->getSummary($mixId, $user?->id));
        } catch (\Throwable $e) {
            return $this->sendVoteError($e);
        }
    }

    public function vote($vars = [])
    {
        try {
            $user = $this->requireUser($this->authService);
            $mixId = (int)($vars['id'] ?? 0);

            $mix = $this->mixService->getById($mixId);

            if (!$mix || $mix->status !== 'published') {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            $data = $this->getJsonInput();
            $summary = $this->voteService->vote($mixId, (int)$user->id, $data['voteType'] ?? '');

            return $this->sendSuccessResponse($summary);
        } catch (\Throwable $e) {
            return $this->sendVoteError($e);
        }
    }

    private function sendVoteError(\Throwable $e)
    {
        return $this->sendApiError($e, [400, 401, 403, 404]);
    }
}
