<?php

declare(strict_types=1);

namespace Src\Controller\Api;


class ApiSharesController extends ApiController
{
    public function getAllShares()
    {

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            if (!$userId) {
                $this->sendJson(["message" => "Utilisateur non authentifié"], 401);
                return;
            }

            $shares = $this->apiSharesModel->getMyShares($userId);
            // $shares = [1, 2, 3];

            $this->sendJson($shares);

        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
