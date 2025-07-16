<?php

declare(strict_types=1);

namespace Src\Controller\Api;


class ApiLinksController extends ApiController
{
    public function getMyFriends(): void
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

            $friends = $this->apiLinksModel->getMyFriends($userId);

            $this->sendJson($friends);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

    public function sentRequest(): void
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

            $requests = $this->apiLinksModel->getSentRequests($userId);

            $this->sendJson($requests);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function receivedRequest(): void
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

            $requests = $this->apiLinksModel->getReceivedRequests($userId);

            $this->sendJson($requests);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function nameSearchedInDb(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            if (!$userId) {
                $this->sendJson(["message" => "Utilisateur non authentifié"], 401);
                return;
            }

            $data = json_decode(file_get_contents("php://input"), true);
            $nameSearched = htmlentities($data['searchName']);

            if (empty($nameSearched)) {
                $this->sendJson(["message" => "Nom de recherche vide"], 400);
                return;
            }

            $results = $this->apiLinksModel->nameSearchedInDb($nameSearched, $userId);
            error_log(print_r($results, true)); // Debugging line to check results
            $this->sendJson($results);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
