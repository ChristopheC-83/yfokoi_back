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
    public function getListById(int $id_list): array
    {
        $req = "SELECT * FROM lists WHERE id = :id_list";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id_list', $id_list, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
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
