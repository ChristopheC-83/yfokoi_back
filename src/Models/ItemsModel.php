<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class ItemsModel extends DataBase
{
    public function createNewItem($id_list, $content, $created_by)
    {
        $req = "INSERT INTO items_lists (id_list, content, created_by) VALUES (:id_list, :content, :created_by)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':created_by', $created_by, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }


    public function ItemExists($id_list, $content, $created_by)
    {
        $req = "SELECT id FROM items_lists WHERE id_list = :id_list AND content = :content AND created_by = :created_by";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':created_by', $created_by, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();

        return $result !== false;
    }
    public function updateItemDoneStatus(int $item_id, int $is_done): bool
    {
        $req = "UPDATE items_lists SET is_done = :is_done WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':is_done', $is_done, PDO::PARAM_INT);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success;
    }

    public function updateItemContent($item_id, $new_content): bool
    {
        $req = "UPDATE items_lists SET content = :content WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':content', $new_content, PDO::PARAM_STR);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success;
    }

    public function getAllItemsByListId(int $id_list): array
    {
        $req = "SELECT 
            il.*, 
            u.name AS creator_name
        FROM 
            items_lists il
        LEFT JOIN 
            user u ON il.created_by = u.id
        WHERE 
            il.id_list = :id_list";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function deleteItem(int $item_id): bool
    {
        $req = "DELETE FROM items_lists WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success;
    }


    public function deleteAllDoneItems(int $id_list): bool
    {
        $req = "DELETE FROM items_lists WHERE id_list = :id_list AND is_done = 1";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success;
    }

    public function getItemsById(int $id_item): ?array
    {
        $req = "SELECT * FROM items_lists WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id', $id_item, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $item ?: null;
    }

    
}
