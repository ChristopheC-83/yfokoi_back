<?php

declare(strict_types=1);

namespace Src\Core;

use PDO;
use PDOException;

abstract class DataBase
{
    protected static ?PDO $pdo = null;

    protected function setDB(): PDO
    {
        if (self::$pdo === null) {
            if (!getenv('DB_HOST')) {
                $this->loadEnv();
            }

            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                getenv('DB_HOST'),
                getenv('DB_NAME')
            );

            try {
                self::$pdo = new PDO(
                    $dsn,
                    getenv('DB_USER'),
                    getenv('DB_PASS'),
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                throw new \Exception("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    private function loadEnv(): void
    {
        $envFile = dirname(__DIR__) . '/../.env';

        // Vérifier si le fichier .env existe
        if (!file_exists($envFile)) {
            throw new \Exception(".env manquant à la racine du projet.");
        }

        // Charger le fichier .env et le transformer en tableau
        $envVariables = parse_ini_file($envFile);

        // Vérifier si le fichier a bien été chargé
        if ($envVariables === false) {
            throw new \Exception("Erreur lors de la lecture du fichier .env.");
        }

        // Boucle pour ajouter chaque variable d'environnement
        foreach ($envVariables as $key => $value) {
            $key = trim($key);
            $value = trim($value);

            // Ne pas ajouter une variable d'environnement si elle existe déjà
            if (!getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}
