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
            $mixes = $this->mixService->getAll();
            return $this->sendSuccessResponse($mixes);
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
