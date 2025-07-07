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
}
