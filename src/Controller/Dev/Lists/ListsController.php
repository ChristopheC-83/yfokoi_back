<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Lists;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Controller\Dev\Users\UsersContextController;
use Src\Core\Utilities;


class ListsController extends MainController
{

    public $userContextController;

    public function __construct()
    {
        parent::__construct();
        $this->userContextController = new UsersContextController();
    }

    public function createList($datas): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $datas['owner_id']) {
            flashMessage("Vous ne pouvez pas créer une liste pour un autre utilisateur.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        if (empty($datas['name']) || trim($datas['name']) === '') {
            flashMessage("Le nom de la liste ne peut pas être vide.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $owner_id = $_SESSION['user_id'];
        $name = htmlspecialchars($datas['name']);

        if ($this->listsModel->listNameExists($name, $owner_id)) {
            flashMessage("Vous avez déjà une liste avec ce nom.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        if ($this->listsModel->createNewList($name, $owner_id)) {
            flashMessage("Liste créée avec succès.", "alert-success");
        } else {
            flashMessage("Erreur lors de la création de la liste.", "alert-danger");
        }

        header('Location: ' . ROOT . 'accueil');
        exit;
    }

    public function selectList($datas): void
    {
        if (!isset($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour sélectionner une liste.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $this->userContextController->createOrUpdateSelectedList($_SESSION['user_id'], $datas['list_id']);

        header('Location: ' . ROOT . 'accueil'); 
        exit;
    }



    public function deleteItem($datas): void
    {
        if (!isset($datas['id'])) {
            flashMessage("ID invalide", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $item_id = (int)$datas['id'];

        if ($this->itemsModel->deleteItem($item_id)) {
            flashMessage("Élément supprimé avec succès.", "alert-success");
        } else {
            flashMessage("Erreur lors de la suppression de l'élément.", "alert-danger");
        }
        header('Location: ' . ROOT . 'accueil');
    }


    public function deleteList($datas): void
    {

        if ($this->listsModel->deleteList((int)$datas['id_list'])) {
            flashMessage("Liste supprimée avec succès.", "alert-success");
        } else {
            flashMessage("Erreur lors de la suppression de la liste.", "alert-danger");
        }
        header('Location: ' . ROOT . 'accueil');
    }
}
