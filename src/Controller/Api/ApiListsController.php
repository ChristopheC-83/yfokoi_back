<?php

declare(strict_types=1);

namespace Src\Controller\Api;

use Src\Controller\Dev\MainController;
use Src\Core\Utilities;

class ApiListsController extends ApiController
{

    public function getAllLists(): void
    {
        $allLists = $this->apiListsModel->getAllLists();

        $this->sendJson($allLists);
    }

    public function getOwnedLists(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();



            $ownedLists = $this->apiListsModel->getOwnedListsByUserId($userId);

            if ($ownedLists === false) {
                $this->sendJson(['error' => 'Erreur lors de la récupération des listes.'], 500);
                return;
            }

            $this->sendJson($ownedLists);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

    public function getAccessLists(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            // Assuming you have a method to get access lists by user ID
            $accessLists = $this->apiListsModel->getAccessListsByUserId($userId);

            if ($accessLists === false) {
                $this->sendJson(['error' => 'Erreur lors de la récupération des listes accessibles.'], 500);
                return;
            }

            $this->sendJson($accessLists);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
