<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Items;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Core\Utilities;


class ItemsController extends MainController
{
    

    

    public function addItem($datas): void
    {
        // dd($datas);
        if (!isset($datas['selected_list_id']) || !isset($datas['created_by'])) {
            flashMessage("Vous ne pouvez pas créer d'élément pour cette liste.", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $content = htmlspecialchars($datas['content']);
        $id_list = (int) $datas['selected_list_id'];
        $created_by = $_SESSION['user_id'];
        $isExisting = $this->itemsModel->ItemExists($id_list, $content, $created_by);

        if ($this->itemsModel->createNewItem($id_list, $content, $created_by)) {
            if ($isExisting) {
                flashMessage("L'élément existe déjà dans cette liste mais a été rajouté en plus.", "alert-info");
            } else {
                flashMessage("Élément ajouté avec succès.", "alert-success");
            }
        } else {
            flashMessage("Erreur lors de la création de la liste.", "alert-danger");
        }

        header('Location: ' . ROOT . 'accueil');
        exit;
    }

    public function itemIsDone($datas): void
    {
        if (!isset($datas['id'])) {
            flashMessage("ID invalide", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $item_id = (int)$datas['id'];
        $is_done = isset($datas['is_done']) ? 1 : 0; // Si coché => 1, sinon => 0

        $this->itemsModel->updateItemDoneStatus($item_id, $is_done);

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

    public function deleteAllDone($datas): void
    {
        if (!isset($datas['id_list'])) {
            flashMessage("ID invalide", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $id_list = (int)$datas['id_list'];

        if ($this->itemsModel->deleteAllDoneItems($id_list)) {
            flashMessage("Tous les éléments cochés ont été supprimés avec succès.", "alert-success");
        } else {
            flashMessage("Erreur lors de la suppression des éléments cochés.", "alert-danger");
        }
        header('Location: ' . ROOT . 'accueil');
    }
    public function editMode($datas): void
    {
        if (!isset($datas['id'])) {
            flashMessage("ID invalide", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $_SESSION['edit_item_id']  = (int)$datas['id'];

        header('Location: ' . ROOT . 'accueil');
        exit;
    }


public function updateItem($datas): void
    {
        if (!isset($datas['id']) || !isset($datas['new_content'])) {
            flashMessage("ID ou contenu invalide", "alert-danger");
            header('Location: ' . ROOT . 'accueil');
            exit;
        }

        $item_id = (int)$datas['id'];
        $new_content = htmlspecialchars($datas['new_content']);

        if ($this->itemsModel->updateItemContent($item_id, $new_content)) {
            flashMessage("Élément mis à jour avec succès.", "alert-success");
        } else {
            flashMessage("Erreur lors de la mise à jour de l'élément.", "alert-danger");
        }

        unset($_SESSION['edit_item_id']);
        header('Location: ' . ROOT . 'accueil');
        exit;
    }

    public function exitEditMode(): void
    {
        if (isset($_SESSION['edit_item_id'])) {
            unset($_SESSION['edit_item_id']);
        }

        header('Location: ' . ROOT . 'accueil');
        exit;
    }

   
}
