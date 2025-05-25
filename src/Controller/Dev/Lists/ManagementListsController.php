<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Lists;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Controller\Dev\Users\UsersContextController;
use Src\Core\Utilities;


class ManagementListsController extends MainController
{

    public function managementListsPage(array $datas): void
    {
        // dd($datas);
        if (empty($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour accéder à cette page", "alert-danger");
            redirect(ROOT . "account/connection");
            exit();
        }

        $allListOfUser = $this->listsModel->getAllListsByUserId($_SESSION['user_id']);
        $id_list = htmlentities((string)$datas['id_list']);
        if (!is_numeric($id_list) || $id_list < 0) {
            flashMessage("L'identifiant de la liste est invalide", "alert-danger");
            redirect(ROOT . "managementLists/myLists");
            exit();
        } else {
            $selected_list = $this->listsModel->getListById($id_list);
        }

        // dd($selected_list);
        if (is_null($selected_list) || $selected_list['owner_id'] != $_SESSION['user_id']) {
            flashMessage("Vous n'avez pas accès à cette liste", "alert-danger");
            redirect(ROOT . "managementLists/myLists");
            exit();
        }

        $usersSharingThisList = $this->managementListsModel->getUsersAccessByList((int)$id_list, $_SESSION['user_id']);
        $usersNotSharingThisList = $this->managementListsModel->getUsersNoAccessByList((int)$id_list, $_SESSION['user_id']);
        $accessLevels = $this->accessLevelsModel->getAllAccessLevels();

        // dd($usersNotSharingThisList);

        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil de la gestion des listes",
            "view" => "dev/pages/managementListsPage.php",
            "layout" => "layout.php",
            "allJS" => [],
            "allListOfUser" => $allListOfUser,
            "selected_list" => $selected_list,
            "usersSharingThisList" => $usersSharingThisList,
            "usersNotSharingThisList" => $usersNotSharingThisList,
            "accessLevels" => $accessLevels,

        ];

        Utilities::renderPage($datas_page);
    }

    public function modifyListAccess(array $data): void
    {
        // dd($data);
        if (empty($data['list_id']) || empty($data['user_id']) || empty($data['access_level'])) {
            flashMessage("Tous les champs sont obligatoires", "alert-danger");
            redirect(ROOT . "managementLists/myLists");
            exit();
        }

        $author_id = (int)$data['author_id'];
        $author_name = htmlentities($data['author_name']);
        $list_id = (int)$data['list_id'];
        $user_id = (int)$data['user_id'];
        $accessLevel = $data['access_level'];

        if ($data['access_level'] == -1) {
            // If access level is -1, we delete the user from the list
            $this->managementListsModel->deleteUserFromList($list_id, $user_id);
            flashMessage("L'utilisateur a été supprimé de la liste avec succès", "alert-success");
            $datas['id_list'] = $list_id;
            $this->managementListsPage($datas);
            exit();
        }

        $exists = $this->managementListsModel->checkIfShareExists($list_id, $user_id);
        if ($exists) {
            $this->managementListsModel->updateUserToList($author_id, $author_name,  $list_id, $user_id, $accessLevel);
            flashMessage("Les droits de l'utilisateur ont été modifiés avec succès", "alert-success");
        } else {
            $this->managementListsModel->addUserToList($author_id, $author_name,  $list_id, $user_id, $accessLevel);
            flashMessage("L'utilisateur a été ajouté à la liste avec succès", "alert-success");
        }

        $datas['id_list'] = $list_id;
        $this->managementListsPage($datas);
        exit();
    }
}
