<?php

declare(strict_types=1);

namespace Src\Models\api;

use Src\Core\DataBase;
use PDO;

class ApiItemsModel extends DataBase
{
    public function getItemsByListId(int $listId): array
    {
        $req = "SELECT * FROM items WHERE list_id = :list_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $items;

    }

}