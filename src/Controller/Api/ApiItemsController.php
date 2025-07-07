<?php

declare(strict_types=1);

namespace Src\Controller\Api;


class ApiItemsController extends ApiController
{


    public function getAllMyItems(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $ownedLists = $this->apiListsModel->getOwnedListsByUserId($userId);
            $accessLists = $this->apiListsModel->getAccessListsByUserId($userId);


            // Fusion avec suppression des doublons, au cas où...
            $allLists = array_merge($ownedLists, $accessLists);
            $listsById = [];
            foreach ($allLists as $list) {
                $listsById[$list['id']] = $list;
            }
            $allLists = array_values($listsById);


            if (empty($allLists)) {
                $this->sendJson(["message" => "Aucune liste trouvée."], 404);
                return;
            }
            foreach ($allLists as $list) {
                $items = $this->apiItemsModel->getItemsByListId($list['id']);

                $result[] = [
                    'id' => $list['id'],
                    'items' => $items,
                ];
            }

            $this->sendJson($result, 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function getItemsByListId(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $data = json_decode(file_get_contents("php://input"), true);
            $id_list = $data['idList'];

            if (!isset($id_list) || empty($id_list)) {
                $this->sendJson(["message" => "ID de la liste manquant."], 400);
                return;
            }

            $ownedLists = $this->apiListsModel->getOwnedListsByUserId($userId);
            $accessLists = $this->apiListsModel->getAccessListsByUserId($userId);

            if (empty($ownedLists) && empty($accessLists)) {
                $this->sendJson(["message" => "Aucune liste trouvée."], 404);
                return;
            }

            $itemsByList = $this->apiItemsModel->getItemsByListId($id_list);

            $this->sendJson($itemsByList, 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function updateIsDone(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $data = json_decode(file_get_contents("php://input"), true);
            $id_item = $data['itemId'];;

            if (!isset($id_item) || empty($id_item)) {
                $this->sendJson(["message" => "ID de la liste manquant."], 400);
                return;
            }

            $list_id = $this->apiItemsModel->getListIdByItemId($id_item);

            if ($list_id === null) {
                $this->sendJson(["message" => "Item non trouvé."], 404);
                return;
            }

            $ownershipCheck = $this->apiListsModel->checkListOwnership($list_id, $userId);
            $accessListCheck = $this->apiListsModel->checkListAccess($list_id, $userId);

            // Log the ownership and access checks
            // error_log("Ownership: " . ($ownershipCheck ? 'true' : 'false'));
            // error_log("Access: " . ($accessListCheck ? 'true' : 'false'));

            if (!$ownershipCheck && !$accessListCheck) {
                $this->sendJson(["message" => "Accès refusé à la liste."], 403);
                return;
            }
            $itemFromDb = $this->apiItemsModel->getItemsById($id_item);
            $is_done = isset($itemFromDb['is_done']) ? (int) !$itemFromDb['is_done'] : 1;

            $updateIsDone = $this->apiItemsModel->updateIsDone($id_item, $is_done);


            if (!$updateIsDone) {
                $this->sendJson(["message" => "Échec de la mise à jour de l'état de l'item."], 500);
                return;
            }

            $this->sendJson(["message" => "IsDone de l'item modifié.",], 200);
        } catch (\Throwable $e) {
            error_log("Erreur updateIsDone : " . $e->getMessage());
            $this->sendJson(["message" => "Erreur serveur sur accessListCheck:" . $accessListCheck], 500);
        }
    }
}
