<?php

declare(strict_types=1);

namespace Src\Models\api;

use Src\Core\DataBase;
use PDO;

class ApiItemsModel extends DataBase
{
    public function getItemsByListId(int $listId): array
    {
        $req = "SELECT * FROM items_lists WHERE id_list = :id_list";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id_list', $listId, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $items;

    }

}