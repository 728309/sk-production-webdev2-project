<?php

namespace App\Controllers;

use App\Models\Mix;
use App\Services\IMixService;
use App\Services\MixService;
use App\Framework\Controller;

class MixController extends Controller
{
    private IMixService $mixService;

    public function __construct()
    {
        $this->mixService = new MixService();
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

            $mixes = $this->mixService->getAll($page, $limit, $genreFilter, $searchFilter);
            $total = $this->mixService->count($genreFilter, $searchFilter);
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
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function get($vars = [])
    {
        try {
            $id = (int)($vars['id'] ?? 0);
            $mix = $this->mixService->getById($id);
            
            if (!$mix) {
                return $this->sendErrorResponse('Mix not found', 404);
            }
            return $this->sendSuccessResponse($mix);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function create()
    {
        try {
            $mix = $this->mapPostDataToClass(Mix::class);
            $mix = $this->mixService->create($mix);
            return $this->sendSuccessResponse($mix, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function update($vars = [])
    {
        try {
            $mix = $this->mapPostDataToClass(Mix::class);
            $id = (int)($vars['id'] ?? 0);
            $mix->id = $id;
            $this->mixService->update($id, $mix);
            return $this->sendSuccessResponse($mix);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function delete($vars = [])
    {
        try {
            $id = (int)($vars['id'] ?? 0);
            $this->mixService->delete($id);
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }


}
