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
        '' => [
            'label' => 'Sélectionner le niveau d\'accès',
            'permissions' => [],
        ],
        'read' => [
            'label' => 'Lire la liste',
            'permissions' => ['read'],
        ],
        'read_create' => [
            'label' => 'Lire + Ajouter',
            'permissions' => ['read', 'create'],
        ],
        'read_create_manage_own' => [
            'label' => 'Lire + Ajouter + Gérer ses notes',
            'permissions' => ['read', 'create', 'manage_own'],
        ],
        'read_create_manage_all' => [
            'label' => 'Lire + Ajouter + Gérer toutes les notes',
            'permissions' => ['read', 'create', 'manage_all'],
        ],
    ];



    public static function getLevels(): array
    {
        return self::$levels;
    }

    public function describePermissions(array $permissions): string
    {
        $actions = [];

        if (!empty($permissions['can_read'])) {
            $actions[] = 'lire';
        }
        if (!empty($permissions['can_create'])) {
            $actions[] = 'ajouter';
        }
        if (!empty($permissions['can_manage_own_items'])) {
            $actions[] = 'gérer ses propres notes';
        }
        if (!empty($permissions['can_manage_all_items'])) {
            $actions[] = 'gérer toutes les notes';
        }

        return !empty($actions) ? implode(', ', $actions) : 'aller se recoucher...';
    }

    public function managementListsPage(int $id_list = -1): void
    {
        if (empty($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour accéder à cette page", "alert-danger");
            redirect(ROOT . "account/connection");
            exit();
        }

        $userId = (int)$_SESSION['user_id'];
        $allListOfUser = $this->listsModel->getAllListsByUserId($userId);
        $myFriends = $this->usersLinksModel->getAcceptedFriends($userId);
        $selected_list = null;
        $usersAccessToList = [];

        if ($id_list !== -1) {
            $selected_list = $this->listsModel->getListById($id_list);
            if (!$selected_list) {
                throw new Exception("La liste demandée n'existe pas.");
            }
            $usersAccessToList = $this->managementListsModel->getUserAccessByList($id_list);
        }

        $levels = self::getLevels();

        $sharedUsers = [];
        $usersAccessToList = $usersAccessToList ?? [];
        foreach ($usersAccessToList as $access) {
            $friend = array_filter($myFriends, fn($f) => $f['id'] === $access['user_id']);
            $friend = array_values($friend)[0] ?? null;
            if ($friend) {
                $sharedUsers[] = [
                    'name' => $friend['name'],
                    'user_id' => $friend['id'],
                    'permissions' => $this->describePermissions($access),
                ];
            }
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
            "levels" => $levels,
            "sharedUsers" => $sharedUsers,
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
