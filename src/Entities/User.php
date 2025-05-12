<?php

declare(strict_types=1);

namespace Src\Entities;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $hashed_password;
    private string $created_at;
    private ?string $avatar;

    // Constructeur pour initialiser les propriétés de l'utilisateur
    public function __construct(
        string $name, 
        string $email, 
        string $hashed_password, 
        string $created_at, 
        ?string $avatar = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->created_at = $created_at;
        $this->avatar = $avatar;
    }

    // Getters et setters pour chaque propriété

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    public function setHashedPassword(string $hashed_password): void
    {
        $this->hashed_password = $hashed_password;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    // Méthode pour hacher le mot de passe
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Méthode pour vérifier le mot de passe (vérifie le mot de passe contre le haché stocké)
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->hashed_password);
    }
}
