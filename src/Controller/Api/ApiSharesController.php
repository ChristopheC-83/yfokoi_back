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

    public function createShare()
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJson(["message" => "Méthode non autorisée"], 405);
            return;
        }

        $author_id = $this->securityApiController->getAuthenticatedUserIdFromToken();
        if (!$author_id) {
            $this->sendJson(["message" => "Utilisateur non authentifié"], 401);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $list_id = $data['list_id'] ?? null;
        $user_id = $data['user_id'] ?? null;

        if (!$list_id || !$user_id) {
            $this->sendJson(["message" => "Données manquantes"], 400);
            return;
        }

        $isAuthor = $this->apiListsModel->checkListOwnership($list_id, $author_id);
        if (!$isAuthor) {
            $this->sendJson(["message" => "Vous n'êtes pas l'auteur de cette liste"], 403);
            return;
        }

        $author_name = $this->usersReactModel->getNameById($author_id);
        $created = $this->apiSharesModel->createShare($author_id, $author_name, $user_id, $list_id);
        if (!$created) {
            $this->sendJson(["message" => "Erreur lors de la création du partage."], 500);
            return;
        }

        $share = $this->apiSharesModel->getShare($author_id, $user_id, $list_id);
        if (!$share) {
            $this->sendJson(["message" => "Partage créé, mais impossible de le récupérer."], 500);
            return;
        }

        $this->sendJson($share, 201);
    } catch (\Throwable $th) {
        error_log($th->getMessage());
        $this->sendJson(["message" => "Erreur serveur"], 500);
    }
}

    public function updateShare()
    {

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            if (!$userId) {
                $this->sendJson(["message" => "Utilisateur non authentifié"], 401);
                return;
            }
            $data = json_decode(file_get_contents("php://input"), true);

            $author_id = $userId;
            $list_id = $data['list_id'];
            $user_id = $data['user_id'];
            // passer en string
            $access_level = (string)$data['access_level'];

            //  j'ai bien toutes les données
            if (is_null($list_id) || is_null($user_id) || is_null($access_level) || $access_level === '') {
                $this->sendJson(["message" => "Données manquantes"], 400);
                return;
            }

            // author est bien l'auteur de la list id
            $isAuthor = $this->apiListsModel->checkListOwnership($list_id, $author_id);
            if (!$isAuthor) {
                $this->sendJson(["message" => "Vous n'êtes pas l'auteur de cette liste"], 403);
                return;
            }

            // $user_id a bien accés à cette liste
            $hasAccess = $this->apiListsModel->checkListAccess($list_id, $user_id);
            if (!$hasAccess) {
                $this->sendJson(["message" => "Accès refusé"], 403);
                return;
            }

            //  on modifie l'access_level
            $success = $this->apiSharesModel->updateShare($list_id, $user_id, $access_level);
            if (!$success) {
                $this->sendJson(["message" => "Erreur lors de la mise à jour du partage."], 500);
                return;
            }

            $this->sendJson(["message" => "Partage mis à jour avec succès."], 200);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function removeShare()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            if (!$userId) {
                $this->sendJson(["message" => "Utilisateur non authentifié"], 401);
                return;
            }
            $data = json_decode(file_get_contents("php://input"), true);

            $author_id = $userId;
            $list_id = $data['list_id'];
            $user_id = $data['user_id'];
            $delete_items = $data['delete_items'];

            //  j'ai bien toutes les données
            if (is_null($list_id) || is_null($user_id)) {
                $this->sendJson(["message" => "Données manquantes"], 400);
                return;
            }

            // author est bien l'auteur de la list id
            $isAuthor = $this->apiListsModel->checkListOwnership($list_id, $author_id);
            if (!$isAuthor) {
                $this->sendJson(["message" => "Vous n'êtes pas l'auteur de cette liste"], 403);
                return;
            }

            // $user_id a bien accés à cette liste
            $hasAccess = $this->apiListsModel->checkListAccess($list_id, $user_id);
            if (!$hasAccess) {
                $this->sendJson(["message" => "Accès refusé"], 403);
                return;
            }

            //  on supprime les items de la liste
            if ($delete_items) {
                if(!$this->apiItemsModel->removeMyItemsByListId($list_id, $user_id)){
                    $this->sendJson(["message" => "Erreur lors de la suppression des items."], 500);
                    return;
                };
            }



            //  on modifie l'access_level
            $success = $this->apiSharesModel->removeShare($author_id, $list_id, $user_id);

            if (!$success) {
                $this->sendJson(["message" => "Erreur lors de la suppression du partage."], 500);
                return;
            }
            $this->sendJson(["message" => "Partage supprimé avec succès."], 200);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
