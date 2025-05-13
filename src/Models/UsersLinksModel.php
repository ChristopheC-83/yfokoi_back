<?php

declare(strict_types=1);

namespace Src\Models;

use Src\Core\DataBase;
use PDO;

class UsersLinksModel extends DataBase
{


    public function searchContact(string $nameSearched): array
    {
        // Requête pour rechercher les utilisateurs qui ne sont pas déjà liés
        $req = "
        SELECT u.* 
        FROM user u 
        WHERE u.name LIKE :name 
        AND NOT EXISTS (
            SELECT 1 FROM user_links ul 
            WHERE (ul.user1_id = :currentUserId AND ul.user2_id = u.id) 
            OR (ul.user2_id = :currentUserId AND ul.user1_id = u.id)
        )";

        // Préparation de la requête
        $stmt = $this->setDB()->prepare($req);

        // Vérification que $_SESSION['user_id'] est défini
        if (!isset($_SESSION['user_id'])) {
            // Si l'ID de l'utilisateur connecté n'est pas défini dans la session, on lève une erreur ou on gère ça
            die("Utilisateur non connecté.");
        }

        // Liaison des paramètres
        $stmt->bindValue(':name', '%' . $nameSearched . '%');
        $stmt->bindValue(':currentUserId', $_SESSION['user_id']); // ID de l'utilisateur connecté
        $stmt->execute();

        // Récupérer les résultats
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $result;
    }

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
