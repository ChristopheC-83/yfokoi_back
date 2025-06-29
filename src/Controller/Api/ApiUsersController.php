<?php

declare(strict_types=1);


namespace Src\Controller\Api;

use Src\Controller\Api\Apicontroller;


class ApiUsersController extends Apicontroller
{
    //    Inscription de l'utilisateur
    public function registerReact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJson(["message" => "Méthode non autorisée"], 405);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || !isset($data['name'], $data['email'], $data['password'])) {
            $this->sendJson(["message" => "Données manquantes"], 400);
            return;
        }

        // Vérifie si email ou pseudo existent déjà
        $existingUser = $this->usersReactModel->findByEmailOrName($data['email'], $data['name']);

        if ($existingUser) {
            if ($existingUser['email'] === $data['email']) {
                $this->sendJson(["message" => "Cet email est déjà utilisé."], 409);
            } elseif ($existingUser['name'] === $data['name']) {
                $this->sendJson(["message" => "Ce pseudo est déjà pris."], 409);
            }
            return;
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $newUser = $this->usersReactModel->createAccountDB(
            $data['name'],
            $data['email'],
            $hashedPassword,
            "user",
            // created_at
            date("Y-m-d H:i:s"),
        );

        if ($newUser) {
            $this->sendJson(["message" => "Compte créé avec succès !!!"], 201);
        } else {
            $this->sendJson(["message" => "Erreur lors de la création du compte"], 500);
        }
    }

    // Connexion de l'utilisateur
    public function loginReact()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || !isset($data['name'], $data['password'])) {
                $this->sendJson(["message" => "Données manquantes"], 400);
                return;
            }

            $user = $this->usersReactModel->getUserByName($data['name']);
            if (!$user) {
                $this->sendJson(["message" => "Nom d'utilisateur ou mot de passe incorrect."], 401);
                return;
            }

            $testPassword = $this->usersReactModel->isAccountValid($data['name'], $data['password']);
            if (!$testPassword) {
                $this->sendJson(["message" => "Nom d'utilisateur ou mot de passe incorrect."], 401);
                return;
            }

            //  copier favoriteListId sur selectedListId
            if($user['id']){
                $this->usersContextModel->copyFavoriteToSelectedList($user['id']);
            }



            $jwt = $this->securityApiController->generateJwt($user);

            $this->sendJson([
                "message" => "Connexion réussie, Coucou !!!",
                "token" => $jwt
            ], 200);
        } catch (\Throwable $e) {
            // log erreur dans un fichier si possible
            // error_log($e);
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }

    public function deleteAccount()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

           
           $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
            $success = $this->usersReactModel->deleteAccountDB($userId);

            if (!$success) {
                $this->sendJson(["message" => "Erreur lors de la suppression"], 500);
                return;
            }

            $this->sendJson(["message" => "Compte supprimé avec succès"], 200);
        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
