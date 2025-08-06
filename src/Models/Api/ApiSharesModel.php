<?php

declare(strict_types=1);

namespace Src\Models\Api;

use Src\Core\DataBase;
use PDO;

class ApiSharesModel extends DataBase
{

    public function getMyShares(int $user_id): array
    {
        $req = "SELECT 
            la.*, 
            l.name AS list_name,
            owner.name AS author_name,
            user.name AS user_name
            FROM lists_access la
            INNER JOIN lists l ON la.list_id = l.id
            INNER JOIN user owner ON l.owner_id = owner.id
            INNER JOIN user user ON la.user_id = user.id
            WHERE la.author_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $shares = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // return [1, 2, 3];
        return $shares;
    }

    public function createShare(int $author_id, string $author_name, int $user_id, int $list_id): bool
    {
         $req = "INSERT INTO lists_access (list_id, user_id, access_level, author_id, author_name) 
                VALUES (:list_id, :user_id, :access_level, :author_id, :author_name)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':access_level', 1, PDO::PARAM_INT);
        $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->bindValue(':author_name', $author_name, PDO::PARAM_STR);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function getShare(int $author_id, int $user_id, int $list_id): array|false
    {
        $req = "SELECT * FROM lists_access 
            WHERE author_id = :author_id AND user_id = :user_id AND list_id = :list_id
            LIMIT 1";

        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);

        $stmt->execute();
        $share = $stmt->fetch(PDO::FETCH_ASSOC);
        return $share ?: false;
    }


    public function updateShare(int $list_id, int $user_id, string $access_level): bool
    {
        $req = "UPDATE lists_access 
                SET access_level = :access_level 
                WHERE list_id = :list_id AND user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':access_level', $access_level, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function removeShare(int $author_id, int $list_id, int $user_id): bool
    {
        $req = "DELETE FROM lists_access 
                WHERE list_id = :list_id AND user_id = :user_id AND author_id = :author_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
