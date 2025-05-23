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
}
