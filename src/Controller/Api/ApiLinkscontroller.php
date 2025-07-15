<?php

declare(strict_types=1);

namespace Src\Controller\Api;


class ApiLinksController extends ApiController
{
    public function getFriends(): void
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

            $friends = $this->apiLinksModel->getFriends($userId);
            $this->sendJson($friends);

        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
