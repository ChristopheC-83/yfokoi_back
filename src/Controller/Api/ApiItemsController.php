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

    public function addNewItem(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            $data = json_decode(file_get_contents("php://input"), true);
            // $json = file_get_contents('php://input');
            // error_log("Corps brut reçu : " . $json);

            // $data = json_decode($json, true);
            // error_log("Données décodées : " . json_encode($data));

            // $newItem = $data['item'];
            $id_list = $data['id_list'];
            $content = htmlspecialchars($data['content']);
            $created_by = $data['created_by'];

            error_log("Demande d'acces à l id_list: " . $id_list .
                " content: " . $content .
                " created_by: " . $created_by);
            // si access list, ai je le bon niveau d'accréditation ?
            $accessListCheck = $this->apiListsModel->checkListAccessCreate($id_list, $userId);
            // suis je propriétaire de la liste ?
            $ownershipCheck = $this->apiListsModel->checkListOwnership($id_list, $userId);

            // Log the ownership and access checks
            error_log("Access: " . ($accessListCheck ? 'true' : 'false'));
            error_log("Ownership: " . ($ownershipCheck ? 'true' : 'false'));

            if (!$ownershipCheck && !$accessListCheck) {
                $this->sendJson(["message" => "Accès refusé à la liste."], 403);
                return;
            }

            //  content de l'item non vide
            if (empty($data['content']) || !is_string($data['content'])) {
                $this->sendJson(["message" => "Nom de l'item invalide."], 400);
                return;
            }

            //  existe dejà dans la liste ?
            $isExisting = $this->apiItemsModel->ItemExists($id_list, $content, $created_by);
            if ($isExisting) {
                $this->sendJson(["message" => "L'item existe déjà dans cette liste."], 400);
                return;
            }

            $itemId = $this->apiItemsModel->createNewItem($id_list, $content, $created_by);

            if (!$itemId) {
                $this->sendJson(["message" => "Erreur lors de la création de l'item."], 500);
                return;
            }

            $this->sendJson([
                "message" => "Item créé avec succès.",
            ], 201);
        } catch (\Throwable $e) {
            // error_log("Création de l'item : " . json_encode($data));

            $this->sendJson(["message" => "Erreur serveur"], 500);
            return;
        }
    }

    public function deleteItem(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];

            // error_log("Suppression de l'item : " . json_encode($data));
            if (!$userId) {
                $this->sendJson(["message" => "Utilisateur non authentifié."], 401);
                return;
            }

            if (!isset($id) || empty($id)) {
                $this->sendJson(["message" => "ID de l'item manquant."], 400);
                return;
            }

            $id_list = $this->apiItemsModel->getItemsById($id)['id_list'];
            $created_by = $this->apiItemsModel->getItemsById($id)['created_by'];

            // si access list, ai je le bon niveau d'accréditation ?
            $checkListAccessOwn = $this->apiListsModel->checkListAccessOwn($id_list, $userId);
            $checkListAccessAll = $this->apiListsModel->checkListAccessAll($id_list, $userId);
            // suis je propriétaire de la liste ?
            $ownershipCheck = $this->apiListsModel->checkListOwnership($id_list, $userId);

            $hasRights =
                $ownershipCheck
                || $checkListAccessAll
                || ($checkListAccessOwn && $created_by === $userId);

            if (!$hasRights) {
                // error_log("Pas les droits de sup ". $id_list."/".$created_by."/".$userId);
                $this->sendJson(["message" => "Accès refusé à la liste."], 403);
                return;
            }
            
                error_log("Les droits de sup ". $id_list."/".$created_by."/".$userId);
            if ($this->apiItemsModel->deleteItemFromDB($id)) {
                $this->sendJson(["message" => "Item supprimé avec succès."], 200);
                return;
            }
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur lors de la suppression de l'item."], 500);
            return;
        }
    }
}
