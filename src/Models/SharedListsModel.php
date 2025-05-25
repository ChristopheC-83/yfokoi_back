<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class SharedListsModel extends DataBase
{
    public function getAllSharedListsByUserId(int $user_id): array
    {
        $req = "
           SELECT 
            la.*, 
            l.name,
            u.name AS author_name
            FROM lists_access la
            INNER JOIN lists l ON la.list_id = l.id
            INNER JOIN user u ON l.owner_id = u.id
            WHERE la.user_id = :user_id
        ";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
}
