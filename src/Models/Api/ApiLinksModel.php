<?php

declare(strict_types=1);

namespace Src\Models\Api;

use Src\Core\DataBase;
use PDO;

class ApiLinksModel extends DataBase
{
    public function getMyFriends(int $userId): array
    {
        $req = "SELECT u.id, u.name, u.email
        FROM user u 
        JOIN user_links ul 
            ON ( (u.id = ul.user1_id AND ul.user2_id = :userId)
              OR (u.id = ul.user2_id AND ul.user1_id = :userId) )
        WHERE ul.status = 'accepted'
        ";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $friends;
    }

    public function getSentRequests(int $userId): array
    {
        $req = "
        SELECT u.id, u.name, u.email
        FROM user u 
        JOIN user_links ul 
            ON (u.id = ul.user2_id AND ul.user1_id = :userId)
        WHERE ul.status = 'pending'";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $requests;
    }

    public function getReceivedRequests(int $userId): array
    {
        $req = "
        SELECT u.id, u.name, u.email
        FROM user u 
        JOIN user_links ul ON u.id = ul.user1_id 
        WHERE ul.user2_id = :userId AND ul.status = 'pending'";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
}
