<?php

declare(strict_types=1);

namespace Src\Controller\Dev\Users;

use Exception;
use Src\Controller\Dev\MainController;
use Src\Core\Utilities;


class UsersController extends MainController
{


    public function registerPage(): void
    {
        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil",
            "view" => "dev/pages/registerPage.php",
            "layout" => "layout.php",
            "allJS" => ["registerValidation.js"],
        ];

        Utilities::renderPage($datas_page);
    }

    public function loginPage(): void
    {
        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil",
            "view" => "dev/pages/connectionPage.php",
            "layout" => "layout.php",
            "allJS" => ["connectionValidation.js"],
        ];

        Utilities::renderPage($datas_page);
    }



    public function validateAndCreateAccount($users_datas)
    {

        $errors = $this->validationService->validateRegisterData($users_datas);

        if (empty($errors)) {
            // Si aucune erreur, on peut créer le compte

            // On peut ajouter ici une vérification pour voir si l'email ou nom existe déjà

            $this->validationService->avaibleNameAndEmail($users_datas);

            $hashed_password = password_hash(htmlspecialchars($users_datas['password']), PASSWORD_DEFAULT);
            $name = htmlspecialchars($users_datas['name']);
            $email = htmlspecialchars($users_datas['email']);


            $this->createAccount($name, $email, $hashed_password);

            // Message de succès (tu peux personnaliser ce message si besoin)
            flashMessage('Le compte a été créé avec succès.', 'alert-success');
        } else {
            // Sinon, on affiche les erreurs dans la session pour les montrer dans le layout
            $_SESSION['alert'] = [
                'type' => 'alert-danger', // Type de l'alerte (erreur ici)
                'message' => implode('<br>', $errors) // On assemble les erreurs avec un saut de ligne
            ];
        }

        // Redirige l'utilisateur vers la page d'inscription (ou une autre page si besoin)
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function createAccount($name, $email, $hashed_password)
    {
        $this->usersModel->createUser($name, $email, $hashed_password);
    }

    public function login($users_datas)
    {
        $identifier = htmlspecialchars($users_datas['name_email']);
        $password = $users_datas['password'];

        if (empty($identifier) || empty($password)) {
            throw new Exception("Veuillez remplir tous les champs.");
        }

        // On essaie par le nom, puis l'email si échec
        $user = $this->usersModel->getUserByName($identifier);
        if (!$user) {
            $user = $this->usersModel->getUserByEmail($identifier);
        }

        if ($user && password_verify($password, $user['hashed_password'])) {
            // Authentification réussie et connexion avec $_Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];



            flashMessage('Connexion réussie.', 'alert-success');
            header('Location: ' . ROOT . '/');
            exit;
        } else {
            flashMessage('Identifiants incorrects.', 'alert-danger');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    
    public function profilePage(): void
    {
        $datas_page = [
            "description" => "Bienvenue sur votre outil YFOKOI !",
            "title" => "Page d'accueil",
            "view" => "dev/pages/profilePage.php",
            "layout" => "layout.php",
            "allJS" => ["profileValidation.js"],
        ];

        Utilities::renderPage($datas_page);
    }

    public function logout()
    {
        // Supprimer uniquement les variables de session spécifiques à l'utilisateur
        unset($_SESSION['user_id']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['role']);

        // On détruit la session pour déconnecter l'utilisateur
        flashMessage('Déconnexion réussie.', 'alert-info');

        header('Location: ' . ROOT . 'account/connection');
        exit;
    }
}
