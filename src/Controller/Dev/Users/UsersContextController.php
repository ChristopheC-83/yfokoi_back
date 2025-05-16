<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Users;

use Src\Controller\Dev\MainController;
use Src\Core\Utilities;

class UsersContextController extends MainController
{



    public function createOrUpdateUserContext($id): void
    {
        $userContext =  $this->usersContextModel->getUserContextById($id);
        if (!$userContext) {
            $this->usersContextModel->createUserContext($id);
        } else {
            $this->usersContextModel->updateUserContext($id);
        }
        if(!empty($userContext['favorite_list_id']) ) {
            $this->usersContextModel->copyFavoriteToSelectedList($id);
        }
    }

    public function createOrUpdateSelectedList($user_id, $list_id): void
    {
        $userContext = $this->usersContextModel->getUserContextById($user_id);
        if ($userContext) {
            $this->usersContextModel->updateSelectedList($user_id, $list_id);
        } else {
            $this->usersContextModel->createSelectedList($user_id, $list_id);
        }
    }

    public function copyFavoriteToSelectedList($user_id): void
    {
        $this->usersContextModel->copyFavoriteToSelectedList($user_id);
        
    }

    public function favoriteList($datas): void
    {
        if (!isset($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour identifier une liste favorie.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $list_id = htmlspecialchars($datas['id_list']);
        $user_id = $_SESSION['user_id'];


        if ($this->usersContextModel->setFavoriteList($user_id, $list_id)) {
            flashMessage("Cette liste est devenue votre favorie !", "alert-success");
        } else {
            flashMessage("Erreur lors de la nommination de la Favorie.", "alert-danger");
        }

        header('Location: ' . ROOT . 'accueil');
        exit;
    }
    public function unsetFavoriteList(): void
    {
        if (!isset($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour retirer la liste favorie.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $user_id = $_SESSION['user_id'];


        if ($this->usersContextModel->unsetFavoriteList($user_id)) {
            flashMessage("Cette liste n'est plus votre favorie !", "alert-success");
        } else {
            flashMessage("Erreur lors du retrait du statut de Favorie.", "alert-danger");
        }

        header('Location: ' . ROOT . 'accueil');
        exit;
    }


}
