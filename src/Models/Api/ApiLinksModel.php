<?php

declare(strict_types=1);

namespace Src\Models\Api;

use BcMath\Number;
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
        $req = "        SELECT u.id, u.name, u.email
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

    public function nameSearchedInDb(string $nameSearched, int $id): array
    {
         // Requête pour rechercher les utilisateurs qui ne sont pas déjà liés
        $req = "
        SELECT u.id, u.name, u.avatar
        FROM user u 
        WHERE u.name LIKE :name 
        AND u.id != :id
        AND NOT EXISTS (
            SELECT 1 FROM user_links ul 
            WHERE (ul.user1_id = :id AND ul.user2_id = u.id) 
            OR (ul.user2_id = :id AND ul.user1_id = u.id)
        )";

        // Préparation de la requête
        $stmt = $this->setDB()->prepare($req);

        
        // Liaison des paramètres
        $stmt->bindValue(':name', '%' . $nameSearched . '%');
        $stmt->bindValue(':id', $id); // ID de l'utilisateur connecté
        $stmt->execute();

        // Récupérer les résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $results;
    }
}
