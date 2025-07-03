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
    public function createNewList(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            $data = json_decode(file_get_contents("php://input"), true);

            $name = trim($data['name']);

            if (!$name || !is_string($name)) {
                $this->sendJson(["message" => "Nom de la liste invalide"], 400);
                return;
            }

            if ($this->apiListsModel->isListNameExists($name, $userId)) {
                $this->sendJson(["message" => "Vous avez déjà une liste avec ce nom."], 400);
                return;
            }

            $listId = $this->apiListsModel->createNewList($name, $userId);

            if (!$listId) {
                $this->sendJson(["message" => "Erreur lors de la création de la liste."], 500);
                return;
            }

            $newList = $this->apiListsModel->getListById($listId);

            $this->sendJson([
                "message" => "Liste créée avec succès.",
                "newList" => $newList
            ], 201);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
    public function deleteList(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            $data = json_decode(file_get_contents("php://input"), true);

            $id = $data['id'];

            if (!$id || !is_numeric($id)) {
                $this->sendJson(["message" => "Nom de la liste invalide"], 400);
                return;
            }

            $list = $this->apiListsModel->getListById($id);

            if ($list['owner_id'] !== $userId) {
                $this->sendJson(["message" => "Vous n'avez pas les droits pour supprimer cette liste."], 403);
                return;
            }

            $userContext = $this->usersContextModel->getUserContextById($userId);

            if ($userContext['selected_list_id'] === $id) {
                $this->usersContextModel->unsetSelectedList($userId);
            }
            if ($userContext['favorite_list_id'] === $id) {
                $this->usersContextModel->unsetFavoriteList($userId);
            }


            $success = $this->apiListsModel->deleteList($id, $userId);

            if (!$success) {
                $this->sendJson(["message" => "Erreur lors de la suppression de la liste."], 500);
                return;
            }

            $this->sendJson(["message" => "Liste supprimée avec succès."], 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

    public function modifyNameList(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();

            $data = json_decode(file_get_contents("php://input"), true);
            $currentList = $data['currentList'];
            $idList = $currentList['id'];
            $newName = trim($data['newNameList']);

            if (!$idList || !is_numeric($idList) || !$newName || !is_string($newName)) {
                $this->sendJson(["message" => "ID ou nom de la liste invalide"], 400);
                return;
            }


            if ($this->apiListsModel->isListNameExists($newName, $userId)) {
                $this->sendJson(["message" => "Vous avez déjà une liste avec ce nom."], 400);
                return;
            }

            if ($userId != $currentList['owner_id']) {
                $this->sendJson(["message" => "Vous n'avez pas les droits pour modifier cette liste."], 403);
                return;
            }

            $success = $this->apiListsModel->modifyNameList($idList, $newName);

            if (!$success) {
                $this->sendJson(["message" => "Erreur lors de la modification du nom de la liste."], 500);
                return;
            }

            $updatedList = $this->apiListsModel->getListById($idList);

            $this->sendJson([
                "message" => "Nom de la liste modifié avec succès.",
                "updatedList" => $updatedList
            ], 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

    
}
