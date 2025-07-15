<?php

declare(strict_types=1);

namespace Src\Models\Api;

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

    public function getListIdByItemId(int $itemId): ?int
    {
        $req = "SELECT id_list FROM items_lists WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $result ? (int) $result['id_list'] : null;
    }

    public function updateIsDone(int $itemId, int $isDone): bool
    {
        $req = "UPDATE items_lists SET is_done = :is_done WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':is_done', $isDone, PDO::PARAM_INT);
        $stmt->bindValue(':id', $itemId, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success;
    }

    public function getItemById(int $id): ?array
    {
        $req = "SELECT * FROM items_lists WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $item ?: null;
    }


    public function ItemExists($id_list, $content)
    {
        $req = "SELECT id FROM items_lists WHERE id_list = :id_list AND content = :content ";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();

        return $result !== false;
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

    public function deleteItemFromDB(int $id_list): bool
    {
        $req = "DELETE FROM items_lists WHERE id_list = :id_list";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        return true;
    }

    public function deleteAllCheckedItemsFromDB(int $id_list): int
    {
        try {
            $req = "DELETE FROM items_lists WHERE id_list = :id_list AND is_done = 1";
            $stmt = $this->setDB()->prepare($req);
            $stmt->bindParam(':id_list', $id_list, PDO::PARAM_INT);
            $stmt->execute();
            $deletedCount = $stmt->rowCount();
            $stmt->closeCursor();
            return $deletedCount;
        } catch (\PDOException $e) {
            error_log("Erreur SQL deleteAllCheckedItemsFromDB : " . $e->getMessage());
            return 0; // Aucun item supprimé
        }
    }


    public function deleteMyCheckedItemsFromDB(int $id_list, int $created_by): int
    {
        try {
            $req = "DELETE FROM items_lists 
                WHERE id_list = :id_list 
                  AND created_by = :created_by 
                  AND is_done = 1";

            $stmt = $this->setDB()->prepare($req);
            $stmt->bindParam(':id_list', $id_list, PDO::PARAM_INT);
            $stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);
            $stmt->execute();

            $deletedCount = $stmt->rowCount();
            $stmt->closeCursor();

            return $deletedCount;
        } catch (\PDOException $e) {
            error_log("Erreur SQL deleteMyCheckedItemsFromDB : " . $e->getMessage());
            return 0; // 0 supprimé => soit rien à supprimer, soit erreur
        }
    }

    public function updateItemContent(int $item_id, string $new_content): bool
    {
        $req = "UPDATE items_lists SET content = :content WHERE id = :id LIMIT 1";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':content', $new_content, PDO::PARAM_STR);
        $stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();

        return $success && $stmt->rowCount() > 0;
    }
}
