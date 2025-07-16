<?php

declare(strict_types=1);

namespace Src\Models\Api;

use Src\Core\DataBase;
use PDO;

class ApiHandleLinksModel extends DataBase
{

    public function checkIfLinkExists(int $user1Id, int $user2Id): bool
    {
        $req = "SELECT COUNT(*) FROM user_links WHERE (user1_id = :user1Id AND user2_id = :user2Id) OR (user1_id = :user2Id AND user2_id = :user1Id)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':user1Id', $user1Id, PDO::PARAM_INT);
        $stmt->bindValue(':user2Id', $user2Id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        return $count > 0; // Retourne true si un lien existe déjà, sinon false
    }

     public function createLink(int $user1Id, int $user2Id): bool
    {
        // Insérer un lien entre les deux utilisateurs
        $req = "INSERT INTO user_links (user1_id, user2_id) VALUES (:user1Id, :user2Id)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':user1Id', $user1Id, PDO::PARAM_INT);
        $stmt->bindValue(':user2Id', $user2Id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
}
