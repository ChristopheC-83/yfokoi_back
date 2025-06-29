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

            if (!$userContext) {
                $this->sendJson(["message" => "Contexte utilisateur non trouvé"], 404);
                return;
            }
            // $this->sendJson([
            //     "userContext" => $userContext
            // ], 200);
            $this->sendJson($userContext, 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

    public function updateSelectedListId(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $data = json_decode(file_get_contents('php://input'), true);

            if (!is_array($data) || !array_key_exists('selected_list_id', $data)) {
                $this->sendJson(["message" => "ID de liste sélectionnée manquant"], 400);
                return;
            }

            $selectedListId = $data['selected_list_id'];
            if (!is_null($selectedListId) && !is_numeric($selectedListId)) {
                $this->sendJson(["message" => "L'ID doit être un entier ou null"], 400);
                return;
            }

            $selectedListId = is_null($selectedListId) ? null : (int) $selectedListId;

            $success = $this->usersContextModel->updateSelectedList($userId, $selectedListId);

            if (!$success) {
                $this->sendJson(["message" => "Échec de la mise à jour en base"], 500);
                return;
            }

            $this->sendJson(["message" => "ID de liste sélectionnée mis à jour avec succès"], 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function updateFavoriteListId(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $data = json_decode(file_get_contents('php://input'), true);

            if (!is_array($data) || !array_key_exists('favorite_list_id', $data)) {
                $this->sendJson(["message" => "ID de liste sélectionnée manquant"], 400);
                return;
            }

            $favoriteListId = $data['favorite_list_id'];
            if (!is_null($favoriteListId) && !is_numeric($favoriteListId)) {
                $this->sendJson(["message" => "L'ID doit être un entier ou null"], 400);
                return;
            }

            $favoriteListId = is_null($favoriteListId) ? null : (int) $favoriteListId;

            $success = $this->usersContextModel->setFavoriteList($userId, $favoriteListId);

            if (!$success) {
                $this->sendJson(["message" => "Échec de la mise à jour en base"], 500);
                return;
            }

            $this->sendJson(["message" => "ID de liste sélectionnée mis à jour avec succès"], 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
