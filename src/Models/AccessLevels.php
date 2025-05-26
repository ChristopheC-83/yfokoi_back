<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;


class AccessLevels extends DataBase
{
    public function getAllAccessLevels(): array
    {
        $req = "SELECT * FROM access_levels ";
        $stmt = $this->setDB()->prepare($req);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function getAccessLevelByListId(int $list_id, $user_id): ?array
    {
        $req = "SELECT * FROM lists_access WHERE list_id = :list_id AND user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result ?: null;
    }
}
