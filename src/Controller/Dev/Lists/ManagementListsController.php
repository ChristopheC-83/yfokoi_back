<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Lists;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Controller\Dev\Users\UsersContextController;
use Src\Core\Utilities;


class ManagementListsController extends MainController
{
    public function managementListsPage($id_list): void
    {
        $allListOfUser = $this->listsModel->getAllListsByUserId($_SESSION['user_id']);
        $selected_list_id = (int)htmlspecialchars($id_list);
        $selected_list =null;
        $myFriends = $this->usersLinksModel->getAcceptedFriends($_SESSION['user_id']);

        if (empty($selected_list_id)) {
            throw new Exception("La liste demandée n'existe pas.");
        }
        if(!empty($this->listsModel->getListById($selected_list_id))){
            $selected_list = $this->listsModel->getListById($selected_list_id);
        }



        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil de la gestion des listes",
            "view" => "dev/pages/managementListsPage.php",
            "layout" => "layout.php",
            "allJS" => [],
            "allListOfUser" => $allListOfUser,
            "selected_list" => $selected_list,
            "myFriends" => $myFriends,
        ];

        Utilities::renderPage($datas_page);
    }



}