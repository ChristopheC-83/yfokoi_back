<?php

declare(strict_types=1);

namespace Src\Controller\Api;


class ApiHandleLinksController extends ApiController{

    public function sendFriendRequest(){

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

            $data = json_decode(file_get_contents('php://input'), true);
            $friendId = $data['to_id'] ?? null;

            if (!$friendId) {
                $this->sendJson(["message" => "ID d'ami manquant"], 400);
                return;
            }

            //  vérifier si e lien existe déjà
            $linkExists = $this->apiHandleLinksModel->checkIfLinkExists($userId, $friendId);
            if ($linkExists) {
                $this->sendJson(["message" => "Vous êtes déjà lié à cet utilisateur"], 409);
                return;
            }

            $result = $this->apiHandleLinksModel->createLink($userId, $friendId);

            if ($result) {
                $this->sendJson(["message" => "Demande d'ami envoyée avec succès"]);
            } else {
                $this->sendJson(["message" => "Échec de l'envoi de la demande d'ami"], 500);
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }

    }
    public function cancelRequest(){

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

            $data = json_decode(file_get_contents('php://input'), true);
            $friendId = $data['id'] ?? null;

            if (!$friendId) {
                $this->sendJson(["message" => "ID d'ami manquant"], 400);
                return;
            }

            //  vérifier si le lien existe déjà
            $linkExists = $this->apiHandleLinksModel->checkIfLinkExists($userId, $friendId);
            if (!$linkExists) {
                $this->sendJson(["message" => "Aucune demande en cours"], 409);
                return;
            }

            $result = $this->apiHandleLinksModel->deleteLink($userId, $friendId);

            if ($result) {
                $this->sendJson(["message" => "Demande annulée avec succès"]);
            } else {
                $this->sendJson(["message" => "Échec de la demande d'annulation"], 500);
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }

    }
}