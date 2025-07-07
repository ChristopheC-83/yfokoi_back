<?php

declare(strict_types=1);

namespace Src\Models\api;

use Src\Core\DataBase;
use PDO;

class ApiItemsModel extends DataBase
{
    public function getItemsByListId(int $listId): array
    {
        // $req = "SELECT * FROM items_lists WHERE id_list = :id_list";
         $req = "
        SELECT 
            i.id,
            i.id_list,
            i.content,
            i.is_done,
            i.created_at,
            i.created_by,
            u.name AS author_name,
            u.email AS author_email
        FROM items_lists i
        JOIN user u ON i.created_by = u.id
        WHERE i.id_list = :id_list
    ";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id_list', $listId, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $items;

    }

}