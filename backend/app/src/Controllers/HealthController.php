<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Utils\Database;

class HealthController extends Controller
{
    public function api()
    {
        return $this->sendSuccessResponse([
            'status' => 'ok',
            'service' => 'SK Production Hub API',
        ]);
    }

    public function database()
    {
        try {
            $connection = Database::connect();
            $connection->query('SELECT 1');

            return $this->sendSuccessResponse([
                'status' => 'ok',
                'database' => 'connected',
            ]);
        } catch (\Throwable $e) {
            return $this->sendErrorResponse('Database unavailable', 500);
        }
    }
}
