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
}