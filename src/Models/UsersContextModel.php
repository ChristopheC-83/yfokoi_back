<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class UsersContextModel extends DataBase
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

    public function createUserContext($id): bool
    {
        $req = "INSERT INTO user_context ( user_id) VALUES ( :userId )";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function updateUserContext($id): bool
    {
        $req = "UPDATE user_context SET last_update = NOW() WHERE user_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
    public function createSelectedList($user_id, $list_id): bool
    {
        $req = "INSERT INTO user_context (user_id, selected_list_id) VALUES (:userId, :listId)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':listId', $list_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
    public function updateSelectedList($user_id, $list_id): bool
    {
        $req = "UPDATE user_context SET selected_list_id = :listId WHERE user_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':listId', $list_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function setFavoriteList($user_id, $list_id): bool
    {
        $req = "UPDATE user_context SET favorite_list_id = :listId WHERE user_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':listId', $list_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function unsetFavoriteList($user_id): bool
    {
        $req = "UPDATE user_context SET favorite_list_id = NULL WHERE user_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }

    public function copyFavoriteToSelectedList($user_id): bool
    {
        $req = "UPDATE user_context SET selected_list_id = favorite_list_id WHERE user_id = :userId";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $stmt->closeCursor();
        return $success;
    }
}
