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

    public function deleteLink(int $userId, int $contactId): bool
    {
        $req = "DELETE FROM user_links WHERE 
        (user1_id = :userId AND user2_id = :contactId)
        OR 
        (user1_id = :contactId AND user2_id = :userId)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':contactId', $contactId, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function acceptFriendRequest(int $userId, int $contactId): bool
    {
        $req = "UPDATE user_links SET status = 'accepted' WHERE user1_id = :contactId AND user2_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':contactId', $contactId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
    public function rejectFriendRequest(int $userId, int $contactId): bool
    {
        $req = "UPDATE user_links SET status = 'declined' WHERE user1_id = :contactId AND user2_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':contactId', $contactId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
    public function getBlockedUsers(int $userId): array
{
    $req = "SELECT u.id, u.name, u.email
        FROM user u 
        JOIN user_links ul 
            ON ul.user1_id = u.id
        WHERE ul.user2_id = :userId AND ul.status = 'declined'";
    $stmt = $this->setDB()->prepare($req);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $blockedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $blockedUsers;
}

}
