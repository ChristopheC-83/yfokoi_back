<?php

declare(strict_types=1);

namespace Src\Controller\Api;

use Src\Controller\Dev\MainController;
use Src\Core\Utilities;

class ApiListsController extends ApiController
{

    public function getAllLists(): void
    {
        $allLists = $this->apiListsModel->getAllLists();

        $this->sendJson($allLists);
    }
}
