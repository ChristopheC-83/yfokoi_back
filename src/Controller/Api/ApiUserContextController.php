<?php

declare(strict_types=1);


namespace Src\Controller\Api;

use Src\Controller\Api\Apicontroller;


class ApiUserContextController extends Apicontroller
{
    public function userContext()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(["message" => "Méthode non autorisée"], 405);
                return;
            }

            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || !isset($data['id'])) {
                $this->sendJson(["message" => "Données manquantes"], 400);
                return;
            }

            $userContext = $this->usersContextModel->getUserContextById($data['id']);


            $this->sendJson([
                "userContext" => $userContext
            ], 200);


        } catch (\Throwable $e) {
            $this->sendJson(["message" => "Erreur serveur"], 500);
        }
    }
}
