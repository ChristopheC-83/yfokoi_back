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

    public function addUserToList($list_id, $user_id, $access_level)
    {
        $permissions = AccessLevelHelper::mapLevelToPermissions($access_level);

        $columns = array_keys($permissions);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $sql = "INSERT INTO lists_access (list_id, user_id, " . implode(", ", $columns) . ")
            VALUES (:list_id, :user_id, " . implode(", ", $placeholders) . ")";

        $stmt = $this->setDB()->prepare($sql);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        foreach ($permissions as $column => $value) {
            $stmt->bindValue(":$column", $value, PDO::PARAM_BOOL);
        }
        return $stmt->execute();
    }

    public function getUserAccessByList($list_id): ?array
    {
        $req = "SELECT * FROM lists_access WHERE list_id = :list_id ";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result ?: null;
    }


    public function updateUserToList($list_id, $user_id, $access_level)
    {
        $permissions = AccessLevelHelper::mapLevelToPermissions($access_level);

        $setParts = [];
        foreach ($permissions as $column => $value) {
            $setParts[] = "$column = :$column";
        }

        $sql = "UPDATE lists_access SET " . implode(", ", $setParts) . "
            WHERE list_id = :list_id AND user_id = :user_id";

        $stmt = $this->setDB()->prepare($sql);
        $stmt->bindValue(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        foreach ($permissions as $column => $value) {
            $stmt->bindValue(":$column", $value, PDO::PARAM_BOOL);
        }

        return $stmt->execute();
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
}
