<?php

declare(strict_types=1);

namespace Src\Controller\Dev;

use Src\Core\Utilities;
use Src\Models\ListsModel;
use Src\Models\UsersModel;
use Src\Services\ValidationSercice;


class MainController
{

    public $usersModel;
    public $validationService;
    public $listsModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->validationService = new ValidationSercice();
        $this->listsModel = new ListsModel();
    }

    public function homePage()
    {

        $listsOfUser=null;
        $items_list = [];
        $list_name = "";

        if (isset($_SESSION['user_id'])) {
            $listsOfUser = $this->listsModel->getAllListsByUserId($_SESSION['user_id']);
        }
        if(isset($_SESSION['selected_list_id'])){
            $items_list = $this->listsModel->getAllItemsByListId($_SESSION['selected_list_id']);
            $list_name = $this->listsModel->getListNameById($_SESSION['selected_list_id']);
        }
        

        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil",
            "view" => "dev/pages/homePage.php",
            "layout" => "layout.php",
            "listsOfUser" => $listsOfUser,
            "items_list" => $items_list,
            "list_name" => $list_name,
        ];

        Utilities::renderPage($datas_page);
    }
}
