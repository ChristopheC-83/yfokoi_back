<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Users;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Core\Utilities;

class UsersLinksController extends MainController
{

    public function searchContact($nameSearched): array
    {
        $searchedContact = [];
        $searchedContact = $this->usersLinksModel->searchContact($nameSearched['nameSearched']);

        return $searchedContact;
    }

    public function addContact($linkedUserId): void
    {
        $linkedUserId = (int) $linkedUserId['idContact'];
        if (!isset($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour ajouter un contact.", "alert-danger");
            header('Location: ' . ROOT . 'account/login');
            exit;
        }

        $currentUserId = $_SESSION['user_id'];
        // dd( $linkedUserId);

        // Vérifier si le lien existe déjà
        $existingLink = $this->usersLinksModel->checkIfLinkExists($currentUserId, $linkedUserId);
        if ($existingLink) {
            flashMessage("Vous êtes déjà lié à cet utilisateur.", "alert-info");
            header('Location: ' . ROOT . 'usersLinks/profile');
            exit;
        }

        // Ajouter le lien dans la base de données
        $this->usersLinksModel->createLink($currentUserId, $linkedUserId);

        flashMessage("Contact ajouté avec succès !", "alert-success");
        header('Location: ' . ROOT . 'account/profile');
        exit;
    }

    public function askFriendRequest($id): array
    {
        $askFriendRequest = [];
        $askFriendRequest = $this->usersLinksModel->getAskFriendRequest($id);

        return $askFriendRequest;
    }


    public function getAcceptedFriends($id): array
    {
        $accepedFriends = [];
        $accepedFriends = $this->usersLinksModel->getAcceptedFriends($id);

        return $accepedFriends;
    }

    public function validateAskFriendRequest($data): void
    {
        $idContact = (int) $data['idContact'];
        $response = (int) $data['response'];


        if (!isset($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour valider une demande d'ami.", "alert-danger");
            header('Location: ' . ROOT . 'account/login');
            exit;
        }

        $currentUserId = $_SESSION['user_id'];

        // Valider ou refuser la demande d'ami
        if ($response === 1) {
            $this->usersLinksModel->acceptFriendRequest($currentUserId, $idContact);
            flashMessage("Demande d'ami acceptée avec succès !", "alert-success");
        } else if ($response === 0) {
            $this->usersLinksModel->rejectFriendRequest($currentUserId, $idContact);
            flashMessage("Demande d'ami refusée avec succès !", "alert-danger");
        } else {
            flashMessage("Réponse invalide.", "alert-danger");
        }

        header('Location: ' . ROOT . 'account/profile');
        exit;
    }

    public function getPendingFriends($id)
    {
        $pendingFriends = [];
        $pendingFriends = $this->usersLinksModel->getPendingFriends($id);

        return $pendingFriends;
    }

    public function deleteLink($data): void
    {
        $idContact = (int) $data['idContact'];

        if (!isset($_SESSION['user_id'])) {
            flashMessage("Vous devez être connecté pour supprimer un contact.", "alert-danger");
            header('Location: ' . ROOT . 'account/login');
            exit;
        }

        $currentUserId = $_SESSION['user_id'];

        // Supprimer le lien dans la base de données
        if (!$this->usersLinksModel->deleteLink($currentUserId, $idContact)) {
            flashMessage("Erreur lors de la suppression du contact.", "alert-danger");
            header('Location: ' . ROOT . 'account/profile');
            exit;
        }
        // supprimer favorie ou selected_list de context si liste paratgée
        $allSharedLists = $this->sharedListsModel->getAllSharedListsUserContact($currentUserId, $idContact);
        $selectedListFromContact = $this->usersContextModel->getSelectedListFromContact($idContact);
        $favoriteListFromContact = $this->usersContextModel->getFavoriteListFromContact($idContact);



        if (!empty($allSharedLists)) {
            foreach ($allSharedLists as $sharedList) {
                if ($sharedList['list_id'] === $selectedListFromContact) {
                    // Si la liste partagée est la liste sélectionnée, on la remet à null
                    if (!$this->usersContextModel->unsetSelectedList($idContact)) {
                        flashMessage("Erreur lors de la mise à jour de la liste sélectionnée.", "alert-danger");
                        header('Location: ' . ROOT . 'account/profile');
                        exit;
                    }
                }
                if ($sharedList['list_id'] === $favoriteListFromContact) {
                    // Si la liste partagée est la liste favorite, on la remet à null
                    if (!$this->usersContextModel->unsetFavoriteList($idContact)) {
                        flashMessage("Erreur lors de la mise à jour de la liste favorite.", "alert-danger");
                        header('Location: ' . ROOT . 'account/profile');
                        exit;
                    }
                }
            }
        }

        // supprimer les listes partagées avec ce contact
        if (!$this->sharedListsModel->deleteSharedLists($currentUserId, $idContact)) {
            flashMessage("Erreur lors de la suppression des listes partagées.", "alert-danger");
            header('Location: ' . ROOT . 'account/profile');
            exit;
        }

        flashMessage("Contact supprimé avec succès !", "alert-success");
        header('Location: ' . ROOT . 'account/profile');
        exit;
    }
}
