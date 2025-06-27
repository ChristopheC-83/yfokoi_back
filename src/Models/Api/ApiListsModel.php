<?php

declare(strict_types=1);

namespace Src\Models\api;

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
}
