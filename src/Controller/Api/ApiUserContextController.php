<?php

declare(strict_types=1);


namespace Src\Controller\Api;

use Src\Controller\Api\Apicontroller;


class ApiUserContextController extends Apicontroller
{
    public function userContext(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            $userContext = $this->usersContextModel->getUserContextById($userId);

            if(!$userContext) {
                $this->sendJson(["message" => "Contexte utilisateur non trouvé"], 404);
                return;
            }
            $this->sendJson([
                "userContext" => $userContext
            ], 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

}
