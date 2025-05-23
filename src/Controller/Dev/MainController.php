<?php

declare(strict_types=1);

namespace Src\Controller\Dev;

use Src\Controller\Dev\Users\UsersLinksController;
use Src\Core\Utilities;
use Src\Models\AccessLevels;
use Src\Models\ItemsModel;
use Src\Models\ListsModel;
use Src\Models\ManagementListsModel;
use Src\Models\UsersContextModel;
use Src\Models\UsersLinksModel;
use Src\Models\UsersModel;
use Src\Services\ValidationSercice;


class MainController
{

    public $usersModel;
    public $validationService;
    public $listsModel;
    public $usersLinksModel;
    public $itemsModel;
    public $usersContextModel;
    public $managementListsModel;
    public $accessLevelsModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->validationService = new ValidationSercice();
        $this->listsModel = new ListsModel();
        $this->usersLinksModel = new UsersLinksModel();
        $this->itemsModel = new ItemsModel();
        $this->usersContextModel = new UsersContextModel();
        $this->managementListsModel = new ManagementListsModel();
        $this->accessLevelsModel = new AccessLevels();
    }

    public function homePage()
    {

        $listsOfUser = null;
        $items_list = [];
        $list_name = "";
        $deleteAllDoneBtn = false;
        $context = null;

        if (isset($_SESSION['user_id'])) {
            $listsOfUser = $this->listsModel->getAllListsByUserId($_SESSION['user_id']);
            $context = $this->usersContextModel->getUserContextById($_SESSION['user_id']);
        }
         if(isset($context['selected_list_id']) && !empty($context['selected_list_id'])  && $context['selected_list_id'] !=null) {
            $items_list = $this->listsModel->getAllItemsByListId($context['selected_list_id']);
        }
     
        // ✅ Vérification s'il y a des éléments cochés
        foreach ($items_list as $item) {
            if ($item['is_done'] == 1) {
                $deleteAllDoneBtn = true;
                break;
            }
        }



        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil",
            "view" => "dev/pages/homePage.php",
            "layout" => "layout.php",
            "listsOfUser" => $listsOfUser,
            "items_list" => $items_list,
            "list_name" => $list_name,
            "deleteAllDoneBtn" => $deleteAllDoneBtn,
            "context" => $context,
        ];

        Utilities::renderPage($datas_page);
    }
}
