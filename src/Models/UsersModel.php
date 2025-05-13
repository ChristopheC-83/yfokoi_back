<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class UsersModel extends DataBase {

     public function getAllUsers(): array
    {
        $req = "SELECT * FROM user";
        $stmt = $this->setDB()->prepare($req);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }

    public function createUser($name, $email, $hashed_password): void{

        $req = "INSERT INTO user (name, email, hashed_password, role) VALUES (:name, :email, :hashed_password, :user)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':hashed_password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindValue(':user', "user", PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    }

   

    public function getUserByEmail(string $email): ?array
    {
        $req = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result ?: null;
    }

    public function getUserByName(string $name): ?array
    {
        $req = "SELECT * FROM user WHERE name = :name";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result ?: null;
    }

    public function deleteUser(int $id): bool
    {
       $this->setDB()->beginTransaction();
        $req = "DELETE FROM user WHERE id = :id";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        $this->setDB()->commit();
        return $result;
    }

}