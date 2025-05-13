<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class ListsModel extends DataBase
{

    public function createNewList($name, $owner_id): bool
    {
        $req = "INSERT INTO lists (name, owner_id) VALUES (:name, :owner_id)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':owner_id', $owner_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
    public function listNameExists(string $name, int $owner_id): bool
    {
        $req = "SELECT id FROM lists WHERE name = :name AND owner_id = :owner_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':owner_id', $owner_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();

        return $result !== false;
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

    public function getAllListsByUserId(int $user_id): array
    {
        $req = "SELECT * FROM lists WHERE owner_id = :owner_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':owner_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

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

    public function getAllItemsByListId(int $id_list): array
    {
        $req = "SELECT * FROM items_lists WHERE id_list = :id_list";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getListNameById(int $id_list): string
    {
        $req = "SELECT name FROM lists WHERE id = :id_list";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result['name'] ?? '';
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

    public function deleteList(int $id_list): bool
    {
        $req = "DELETE FROM lists WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id', $id_list, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success;
    }
}
