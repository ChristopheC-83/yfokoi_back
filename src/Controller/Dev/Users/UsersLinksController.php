<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Users;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Core\Utilities;

class UsersLinksController extends MainController
{

    public function searchContact($nameSearched): void
    {
        $nameSearched = trim($nameSearched['nameSearched']);

        if (empty($nameSearched)) {
            flashMessage("Le nom recherché ne peut pas être vide.", "alert-danger");
            header('Location: ' . ROOT . 'account/profile');
            exit;
        }

        $contacts = $this->usersLinksModel->searchContact($nameSearched);

        if (empty($contacts)) {
            flashMessage("Aucun contact trouvé avec le nom : " . $nameSearched, "alert-danger");
            header('Location: ' . ROOT . 'account/profile');
            exit;
        }

        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil",
            "view" => "dev/pages/searchContactPage.php",
            "layout" => "layout.php",
            "allJS" => [],
            "contacts" => $contacts,
            "nameSearched" => $nameSearched,
        ];

        Utilities::renderPage($datas_page);
    }

    public function addContact($linkedUserId): void
    {
        $linkedUserId = (int) $linkedUserId['idContact'];
        // Vérifier si l'utilisateur est connecté
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
            header('Location: ' . ROOT . 'usersLinks/searchContact');
            exit;
        }

        

        // Ajouter le lien dans la base de données
        $this->usersLinksModel->createLink($currentUserId, $linkedUserId);

        flashMessage("Contact ajouté avec succès !", "alert-success");
        header('Location: ' . ROOT . 'account/profile');
        exit;
    }
}
