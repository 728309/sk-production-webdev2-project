<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Models\Mix;
use App\Services\AuthService;
use App\Services\IAuthService;
use App\Services\IMixService;
use App\Services\MixService;

class MixController extends Controller
{
    private IMixService $mixService;
    private IAuthService $authService;

    public function __construct()
    {
        $this->mixService = new MixService();
        $this->authService = new AuthService();
    }

    public function getAll()
    {
        try {
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, (int)($_GET['limit'] ?? 6));
            $genre = trim($_GET['genre'] ?? '');
            $search = trim($_GET['search'] ?? '');

            $genreFilter = $genre !== '' ? $genre : null;
            $searchFilter = $search !== '' ? $search : null;

            $mixes = $this->mixService->getAll($page, $limit, $genreFilter, $searchFilter, 'published');
            $total = $this->mixService->count($genreFilter, $searchFilter, 'published');
            $totalPages = (int)ceil($total / $limit);

            return $this->sendSuccessResponse([
                'data' => $mixes,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'totalPages' => $totalPages,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function get($vars = [])
    {
        try {
            $id = (int)($vars['id'] ?? 0);
            $mix = $this->mixService->getById($id);

            if (!$mix || $mix->status !== 'published') {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            return $this->sendSuccessResponse($mix);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function getFeatured()
    {
        try {
            return $this->sendSuccessResponse($this->mixService->getFeatured(3));
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function create()
    {
        try {
            $user = $this->requireUser($this->authService);
            $data = $this->getJsonInput();
            $this->validateSubmission($data);

            $mix = new Mix([
                'title' => trim($data['title']),
                'artist' => trim($data['artist']),
                'genre' => trim($data['genre']),
                'platform' => trim($data['platform']),
                'mixUrl' => trim($data['mixUrl']),
                'coverImageUrl' => trim($data['coverImageUrl'] ?? ''),
                'duration' => trim($data['duration'] ?? ''),
                'submittedBy' => $user->name,
                'submittedByUserId' => $user->id,
                'submittedDate' => date('Y-m-d'),
                'description' => trim($data['description'] ?? ''),
                'status' => 'pending',
                'featured' => false,
                'reviewNote' => null,
            ]);

            $mix = $this->mixService->create($mix);

            return $this->sendSuccessResponse($mix, 201);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function getMyMixes()
    {
        try {
            $user = $this->requireUser($this->authService);

            return $this->sendSuccessResponse($this->mixService->getByUserId((int)$user->id));
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function getPending()
    {
        try {
            $this->requireAdmin($this->authService);

            return $this->sendSuccessResponse($this->mixService->getPending());
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function getAdminAll()
    {
        try {
            $this->requireAdmin($this->authService);

            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, (int)($_GET['limit'] ?? 6));
            $genre = trim($_GET['genre'] ?? '');
            $search = trim($_GET['search'] ?? '');
            $status = trim($_GET['status'] ?? '');

            $genreFilter = $genre !== '' ? $genre : null;
            $searchFilter = $search !== '' ? $search : null;
            $statusFilter = $status !== '' ? $status : null;

            $mixes = $this->mixService->getAll($page, $limit, $genreFilter, $searchFilter, $statusFilter);
            $total = $this->mixService->count($genreFilter, $searchFilter, $statusFilter);
            $totalPages = (int)ceil($total / $limit);

            return $this->sendSuccessResponse([
                'data' => $mixes,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'totalPages' => $totalPages,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function adminUpdate($vars = [])
    {
        try {
            $this->requireAdmin($this->authService);
            $id = (int)($vars['id'] ?? 0);
            $existingMix = $this->mixService->getById($id);

            if (!$existingMix) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            $data = $this->getJsonInput();
            $this->validateAdminUpdate($data);

            $mix = new Mix([
                'id' => $id,
                'title' => trim($data['title']),
                'artist' => trim($data['artist']),
                'genre' => trim($data['genre']),
                'platform' => trim($data['platform']),
                'mixUrl' => trim($data['mixUrl']),
                'coverImageUrl' => trim($data['coverImageUrl'] ?? ''),
                'duration' => trim($data['duration'] ?? ''),
                'submittedBy' => $existingMix->submittedBy,
                'submittedByUserId' => $existingMix->submittedByUserId,
                'submittedDate' => $existingMix->submittedDate,
                'description' => trim($data['description'] ?? ''),
                'status' => trim($data['status']),
                'featured' => (bool)($data['featured'] ?? false),
                'reviewNote' => $existingMix->reviewNote,
            ]);

            return $this->sendSuccessResponse($this->mixService->updateAndReturn($id, $mix));
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function adminDelete($vars = [])
    {
        try {
            $this->requireAdmin($this->authService);
            $id = (int)($vars['id'] ?? 0);

            if (!$this->mixService->delete($id)) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            return $this->sendSuccessResponse(['message' => 'Mix deleted successfully']);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function approve($vars = [])
    {
        try {
            $this->requireAdmin($this->authService);
            $id = (int)($vars['id'] ?? 0);
            $mix = $this->mixService->approve($id);

            if (!$mix) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            return $this->sendSuccessResponse($mix);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function reject($vars = [])
    {
        try {
            $this->requireAdmin($this->authService);
            $id = (int)($vars['id'] ?? 0);
            $data = $this->getJsonInput();
            $reviewNote = trim($data['reviewNote'] ?? '');

            if ($reviewNote === '') {
                throw new \InvalidArgumentException('Review note is required', 400);
            }

            $mix = $this->mixService->reject($id, $reviewNote);

            if (!$mix) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            return $this->sendSuccessResponse($mix);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function feature($vars = [])
    {
        try {
            $this->requireAdmin($this->authService);
            $id = (int)($vars['id'] ?? 0);
            $mix = $this->mixService->setFeatured($id, true);

            if (!$mix) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            return $this->sendSuccessResponse($mix);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    public function unfeature($vars = [])
    {
        try {
            $this->requireAdmin($this->authService);
            $id = (int)($vars['id'] ?? 0);
            $mix = $this->mixService->setFeatured($id, false);

            if (!$mix) {
                return $this->sendErrorResponse('Mix not found', 404);
            }

            return $this->sendSuccessResponse($mix);
        } catch (\Throwable $e) {
            return $this->sendMixError($e);
        }
    }

    private function validateSubmission(array $data): void
    {
        $requiredFields = ['title', 'artist', 'genre', 'platform', 'mixUrl'];

        foreach ($requiredFields as $field) {
            if (trim($data[$field] ?? '') === '') {
                throw new \InvalidArgumentException($field . ' is required', 400);
            }
        }

        $this->validateHttpUrl(trim($data['mixUrl']), 'Mix URL');

        $coverImageUrl = trim($data['coverImageUrl'] ?? '');
        if ($coverImageUrl !== '') {
            $this->validateHttpUrl($coverImageUrl, 'Cover image URL');
        }
    }

    private function validateAdminUpdate(array $data): void
    {
        $this->validateSubmission($data);

        $status = trim($data['status'] ?? '');

        if (!in_array($status, ['pending', 'published', 'rejected'], true)) {
            throw new \InvalidArgumentException('Status must be pending, published, or rejected', 400);
        }
    }

    private function validateHttpUrl(string $url, string $label): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException($label . ' must be a valid URL', 400);
        }

        $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));

        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new \InvalidArgumentException($label . ' must use http or https', 400);
        }
    }

    private function sendMixError(\Throwable $e)
    {
        return $this->sendApiError($e, [400, 401, 403, 404]);
    }
}
