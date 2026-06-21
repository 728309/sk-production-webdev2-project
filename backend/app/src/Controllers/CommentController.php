<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Services\CommentService;
use App\Services\IAuthService;
use App\Services\ICommentService;
use App\Services\IMixService;
use App\Services\MixService;

class CommentController extends Controller
{
    private ICommentService $commentService;
    private IMixService $mixService;
    private IAuthService $authService;

    public function __construct()
    {
        $this->commentService = new CommentService();
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

            return $this->sendSuccessResponse($this->commentService->getByMixId($mixId));
        } catch (\Throwable $e) {
            return $this->sendCommentError($e);
        }
    }

    public function create($vars = [])
    {
        try {
            $user = $this->requireUser();
            $mixId = (int)($vars['id'] ?? 0);

            $mix = $this->mixService->getById($mixId);

            if (!$mix || $mix->status !== 'published') {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            $data = $this->getJsonInput();
            $comment = $this->commentService->create($mixId, (int)$user->id, $data['body'] ?? '');

            return $this->sendSuccessResponse($comment, 201);
        } catch (\Throwable $e) {
            return $this->sendCommentError($e);
        }
    }

    public function delete($vars = [])
    {
        try {
            $user = $this->requireUser();
            $commentId = (int)($vars['id'] ?? 0);
            $comment = $this->commentService->getById($commentId);

            if (!$comment) {
                return $this->sendErrorResponse('Comment not found', 404);
            }

            if ($user->role !== 'admin' && (int)$comment->userId !== (int)$user->id) {
                return $this->sendErrorResponse('Not allowed to delete this comment', 403);
            }

            $this->commentService->delete($commentId);

            return $this->sendSuccessResponse(['message' => 'Comment deleted']);
        } catch (\Throwable $e) {
            return $this->sendCommentError($e);
        }
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

    private function sendCommentError(\Throwable $e)
    {
        $code = $e->getCode();

        if (!in_array($code, [400, 401, 403, 404], true)) {
            $code = 500;
        }

        $message = $code === 500 ? 'Internal server error' : $e->getMessage();

        return $this->sendErrorResponse($message, $code);
    }
}
