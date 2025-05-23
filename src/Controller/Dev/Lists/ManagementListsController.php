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
        $id_list = htmlentities($datas['id_list']);
        $selected_list = $this->listsModel->getListById($id_list);
        
        // dd($selected_list);
        // if (is_null($selected_list) || $selected_list['owner_id'] != $_SESSION['user_id']) {
        //     flashMessage("Vous n'avez pas accès à cette liste", "alert-danger");
        //     redirect(ROOT . "managementLists/myLists");
        //     exit();
        // }




        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil de la gestion des listes",
            "view" => "dev/pages/managementListsPage.php",
            "layout" => "layout.php",
            "allJS" => [],
            "allListOfUser" => $allListOfUser,
            "selected_list" => $selected_list,

        ];

        Utilities::renderPage($datas_page);
    }

    public function modifyListAccess(array $data): void
    {
        if (empty($data['list_id']) || empty($data['user_id']) || empty($data['access_level'])) {
            flashMessage("Tous les champs sont obligatoires", "alert-danger");
            redirect(ROOT . "managementLists/myLists");
            exit();
        }

        $list_id = (int)$data['list_id'];
        $user_id = (int)$data['user_id'];
        $accessLevel = $data['access_level'];

        $exists = $this->managementListsModel->checkIfShareExists($list_id, $user_id);
        // dd($exists);
        if ($exists) {
            $this->managementListsModel->updateUserToList($list_id, $user_id, $accessLevel);
            flashMessage("Les droits de l'utilisateur ont été modifiés avec succès", "alert-success");
        } else {
            $this->managementListsModel->addUserToList($list_id, $user_id, $accessLevel);
            flashMessage("L'utilisateur a été ajouté à la liste avec succès", "alert-success");
        }

        redirect(ROOT . "managementLists/myLists/$list_id");
    }

    public function deleteListAccess(array $data): void
    {
        if (empty($data['list_id']) || empty($data['user_id'])) {
            flashMessage("Tous les champs sont obligatoires", "alert-danger");
            redirect(ROOT . "managementLists/myLists");
            exit();
        }

        $list_id = (int)$data['list_id'];
        $user_id = (int)$data['user_id'];

        $this->managementListsModel->deleteUserFromList($list_id, $user_id);
        flashMessage("L'utilisateur a été supprimé de la liste avec succès", "alert-success");

        redirect(ROOT . "managementLists/myLists/$list_id");
    }
}
