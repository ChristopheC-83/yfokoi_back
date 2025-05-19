<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Lists;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Controller\Dev\Users\UsersContextController;
use Src\Core\Utilities;


class ManagementListsController extends MainController
{

    public static array $levels = [
        '' => ['label' => 'Sélectionner le niveau d\'accès', 'permissions' => []],
        'read' => ['label' => 'Lire la liste', 'permissions' => ['read']],
        'read_create' => ['label' => 'Lire + Ajouter', 'permissions' => ['read', 'create']],
        'read_create_own_modify' => ['label' => 'Lire + Ajouter + Modifier ses notes', 'permissions' => ['read', 'create', 'update_own']],
        'read_create_own_modify_delete' => ['label' => 'Lire + Ajouter + Modifier + Supprimer ses notes', 'permissions' => ['read', 'create', 'update_own', 'delete_own']],
        'read_create_all_modify' => ['label' => 'Lire + Ajouter + Modifier toutes les notes', 'permissions' => ['read', 'create', 'update_all']],
        'read_create_all_modify_delete' => ['label' => 'Lire + Ajouter + Modifier + Supprimer toutes les notes', 'permissions' => ['read', 'create', 'update_all', 'delete_all']],
    ];

    public static function getLevels(): array
    {
        return self::$levels;
    }

    public function managementListsPage($id_list): void
    {
        if ($_SESSION['user_id'] == null) {
            flashMessage("Vous devez être connecté pour accéder à cette page", "alert-danger");
            redirect(ROOT . "account/connection");
            exit();
        }
        $allListOfUser = $this->listsModel->getAllListsByUserId($_SESSION['user_id']);
        $selected_list_id = (int)htmlspecialchars($id_list);
        $selected_list = null;
        $myFriends = $this->usersLinksModel->getAcceptedFriends($_SESSION['user_id']);

        if (empty($selected_list_id)) {
            throw new Exception("La liste demandée n'existe pas.");
        }
        if (!empty($this->listsModel->getListById($selected_list_id))) {
            $selected_list = $this->listsModel->getListById($selected_list_id);
        }

        $levels = self::getLevels();



        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil de la gestion des listes",
            "view" => "dev/pages/managementListsPage.php",
            "layout" => "layout.php",
            "allJS" => [],
            "allListOfUser" => $allListOfUser,
            "selected_list" => $selected_list,
            "myFriends" => $myFriends,
            "levels" => $levels,
        ];

        Utilities::renderPage($datas_page);
    }
}
