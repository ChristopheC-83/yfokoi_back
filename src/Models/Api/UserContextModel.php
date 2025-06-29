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

    
}
