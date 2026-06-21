<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Inheritance\Services\IAuthService;
use App\Inheritance\Services\ICommentService;
use App\Inheritance\Services\IMixService;
use App\Services\AuthService;
use App\Services\CommentService;
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
            $user = $this->requireUser($this->authService);
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
            $user = $this->requireUser($this->authService);
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

    private function sendCommentError(\Throwable $e)
    {
        return $this->sendApiError($e, [400, 401, 403, 404]);
    }
}
