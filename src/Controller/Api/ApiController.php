<?php

declare(strict_types=1);

namespace Src\Controller\Api;


use Exception;
use Src\Controller\Dev\MainController;
use Src\Models\Api\UsersReactModel;
use Src\Models\Api\ApiListsModel;
use Src\Controller\Api\SecurityApiController;
use Src\Models\Api\ApiItemsModel;
use Src\Models\Api\ApiLinksModel;

class ApiController extends MainController
{
    public $usersReactModel;
    public $securityApiController;
    public $apiListsModel;
    public $apiItemsModel;
    public $apiLinksModel;

    public function __construct()
    {
        parent::__construct();
        $this->usersReactModel = new UsersReactModel();
        $this->securityApiController = new SecurityApiController();
        $this->apiListsModel = new ApiListsModel();
        $this->apiItemsModel = new ApiItemsModel();
        $this->apiLinksModel = new ApiLinksModel();
    }

    // Centraliser l'envoi des headers CORS
    public function setCorsHeaders()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');
    }

    // Méthode générique pour envoyer une réponse JSON
    public function sendJson($data, $statusCode = 200)
    {
        $this->setCorsHeaders();
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // Gérer les requêtes OPTIONS (CORS)
    public function handleOptionsRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->setCorsHeaders();
            http_response_code(200);
            exit;
        }
    }

    protected function checkRequestMethod(string $expected): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($expected)) {
            $this->sendJson(["message" => "Méthode non autorisée"], 405);
            return false;
        }
        return true;
    }

    protected function getAuthenticatedUserOrExit(): ?int
    {
        $userId = $this->securityApiController->getAuthenticatedUserIdFromToken();
        if (!$userId) {
            $this->sendJson(["message" => "Utilisateur non authentifié"], 401);
            return null;
        }
        return $userId;
    }
}
