<?php

declare(strict_types=1);

namespace Src\Models\Api;

use PDO;
use Src\Core\DataBase;

class UsersReactModel extends DataBase
{

    public function createAccountDB($name, $email, $password, $role, $created_at)
    {

        $req = "INSERT INTO user (name,email, hashed_password, role, created_at) VALUES (:name,:email, :hashed_password, :role, :created_at)";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":hashed_password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $created_at, PDO::PARAM_STR);
        $stmt->execute();
        $isCreate = ($stmt->rowCount() > 0);
        $stmt->closeCursor();
        return $isCreate;
    }
    public function findByEmailOrName($email, $name)
    {
        $req = "SELECT * FROM user WHERE email = :email OR name = :name";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $user;
    }

    public function getUserByName($name)
    {
        $req = "SELECT * FROM user WHERE name = :name";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $success =$stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row;
    }

    public function getUserInfo($name)
    {

        $req = "SELECT * FROM user WHERE name = :name";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
    public function getAllUsers()
    {

        $req = "SELECT * FROM user";
        $stmt = $this->setDB()->prepare($req);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        // On supprime la colonne 'password' pour chaque utilisateur
        // pour que l'effacement ne se fasse pas de manière temporaire
        // nous passons une variable par référence plutôt que par valeur.
        // nous modifions ce qu'est le tableau, pas seulement ce qu'il affiche
        // d'où un terme en plus
        foreach ($result as &$user) {
            unset($user['password']);
        }
        return $result;
    }

    public function isAccountValid($name, $password)
    {

        $req = "SELECT hashed_password FROM user WHERE name = :name";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $passwordDB = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return password_verify($password, $passwordDB['hashed_password']);
        // return false;
    }

    public function deleteAccountDB($name)
    {
        $req = "DELETE FROM user WHERE name = :name";
        $stmt = $this->setDB()->prepare($req);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
        return true;
    }
}
