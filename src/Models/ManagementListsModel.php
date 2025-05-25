<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class ManagementListsModel extends DataBase
{
    public function checkIfShareExists($list_id, $user_id)
    {

        $req = "SELECT COUNT(*) FROM lists_access WHERE list_id = :list_id AND user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0; // Retourne true si un partage existe déjà, sinon false
    }

    public function addUserToList($author_id, $author_name,  $list_id, $user_id, $accessLevel)
    {
        $req = "INSERT INTO lists_access (list_id, user_id, access_level, author_id, author_name) 
                VALUES (:list_id, :user_id, :access_level, :author_id, :author_name)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':access_level', $accessLevel, PDO::PARAM_INT);
        $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->bindValue(':author_name', $author_name, PDO::PARAM_STR);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function getUsersAccessByList($list_id, $user_id): ?array
    {
        $req = "
        SELECT u.id AS user_id, u.name AS user_name, la.access_level
        FROM user u
        INNER JOIN user_links ul ON (
            (ul.user1_id = :user_id AND ul.user2_id = u.id)
            OR (ul.user2_id = :user_id AND ul.user1_id = u.id)
        )
        AND ul.status = 'accepted'
        INNER JOIN lists_access la ON la.user_id = u.id AND la.list_id = :list_id
    ";

    $stmt = $this->setDB()->prepare($req);
    $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $result ?: null;
    }

    public function updateUserToList($author_id, $author_name,  $list_id, $user_id, $accessLevel)
    {
        $req = "UPDATE lists_access 
              SET access_level = :access_level, author_id = :author_id, author_name = :author_name 
              WHERE list_id = :list_id AND user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':access_level', $accessLevel, PDO::PARAM_INT);
        $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->bindValue(':author_name', $author_name, PDO::PARAM_STR);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function getAccessLevel($list_id, $user_id)
    {
        $req = "SELECT access_level FROM lists_access WHERE list_id = :list_id AND user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function deleteUserFromList($list_id, $user_id)
    {
        $req = "DELETE FROM lists_access WHERE list_id = :list_id AND user_id = :user_id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUsersNoAccessByList(int $list_id, int $user_id): ?array
    {
        $req = "
        SELECT u.id, u.name
        FROM user u
        INNER JOIN user_links ul 
        ON (
            (ul.user1_id = :user_id AND ul.user2_id = u.id)
            OR (ul.user2_id = :user_id AND ul.user1_id = u.id)
        )
        AND ul.status = 'accepted'
        LEFT JOIN lists_access la ON u.id = la.user_id AND la.list_id = :list_id
        WHERE la.user_id IS NULL AND u.id != :user_id
    ";

        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result ?: null;
    }
}
