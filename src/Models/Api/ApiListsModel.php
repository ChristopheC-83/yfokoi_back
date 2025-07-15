<?php

declare(strict_types=1);

namespace Src\Models\Api;

use Src\Core\DataBase;
use PDO;

class ApiListsModel extends DataBase
{

    public function getAllLists(): array
    {
        $req = "SELECT * FROM lists";
        $stmt = $this->setDB()->prepare($req);
        $stmt->execute();
        $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $lists;
    }

    public function getOwnedListsByUserId(int $userId): array
    {
        $req = "SELECT * FROM lists WHERE owner_id = :owner_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':owner_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $lists;
    }

    public function getAccessListsByUserId(int $userId): array
    {
        $req = " SELECT
                l.id,
                l.name,
                l.owner_id,
                l.created_at,
                l.updated_at,
                l.is_archived,
                al.id AS access_id,
                al.user_id,
                al.list_id,
                al.access_level,
                u.id AS author_id,
                u.name AS author_name
            FROM lists_access al 
            JOIN lists l ON al.list_id = l.id
            JOIN user u ON l.owner_id = u.id
            WHERE al.user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $lists;
    }

    public function isListNameExists(string $name, int $owner_id): bool
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

    public function createNewList($name, $owner_id): int|false
    {
        $req = "INSERT INTO lists (name, owner_id) VALUES (:name, :owner_id)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':owner_id', $owner_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
        $lastId = $this->setDB()->lastInsertId(); // ou $stmt->lastInsertId() si dispo, selon PDO
        $stmt->closeCursor();
        return (int) $lastId;
    }

    $stmt->closeCursor();
    return false;
    }
    public function getListById(int $id): ?array
    {
        $req = "SELECT * FROM lists WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result ;
    }

    public function deleteList(int $id_list, int $owner_id): bool
    {
        $req = "DELETE FROM lists WHERE id = :id_list AND owner_id = :owner_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->bindValue(':owner_id', $owner_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function modifyNameList(int $id, string $name): bool
    {
        $req = "UPDATE lists SET name = :name WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function checkListOwnership(int $id, int $owner_id): bool
    {
        $req = "SELECT COUNT(*) FROM lists WHERE id = :id AND owner_id = :owner_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();
        
        return (bool)$count;
    }

    public function checkListAccess(int $list_id, int $user_id): bool
    {
        $req = "SELECT COUNT(*) FROM lists_access WHERE list_id = :list_id AND user_id = :user_id AND access_level > 0";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();
        
        return (bool)$count;
    }
    public function checkListAccessCreate(int $list_id, int $user_id): bool
    {
        $req = "SELECT COUNT(*) FROM lists_access WHERE list_id = :list_id AND user_id = :user_id AND access_level > 1";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();
        
        return (bool)$count;
    }
    public function checkListAccessOwn(int $list_id, int $user_id): bool
    {
        $req = "SELECT COUNT(*) FROM lists_access WHERE list_id = :list_id AND user_id = :user_id AND access_level > 2";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();
        
        return (bool)$count;
    }
    public function checkListAccessAll(int $list_id, int $user_id): bool
    {
        $req = "SELECT COUNT(*) FROM lists_access WHERE list_id = :list_id AND user_id = :user_id AND access_level > 3";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();
        
        return (bool)$count;
    }
}
