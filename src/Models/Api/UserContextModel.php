<?php

declare(strict_types=1);

namespace Src\Models\Api;

use PDO;
use Src\Core\DataBase;

class UsersReactModel extends DataBase
{

    public function getUserContextById($id): ?array
    {
        $req = "SELECT * FROM user_context WHERE user_id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $userContext = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $userContext ?: null;
    }

    public function createUserContext($userId): bool
    {
        $req = "INSERT INTO user_context (user_id) VALUES (:userId)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    
}
