<?php

declare(strict_types=1);

namespace Src\Services;

use Src\Models\UsersModel;


class ValidationSercice
{

    public $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }
    public function validateRegisterData($data)
    {
        $errors = [];

        // Validation du nom
        if (empty($data['name'])) {
            $errors['name'] = 'Le nom est requis';
        }

        // Validation de l'email
        if (empty($data['email'])) {
            $errors['email'] = 'L\'email est requis';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Le format de l\'email est invalide';
        }

        // Validation du mot de passe
        if (empty($data['password'])) {
            $errors['password'] = 'Le mot de passe est requis';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 6 caractères';
        }

        // Validation de la confirmation du mot de passe
        if (empty($data['confirmPassword'])) {
            $errors['confirmPassword'] = 'La confirmation du mot de passe est requise';
        } elseif ($data['confirmPassword'] !== $data['password']) {
            $errors['confirmPassword'] = 'Les mots de passe ne correspondent pas';
        }

        return $errors;
    }

    public function avaibleNameAndEmail($users_datas)
    {
        $existingUser = $this->usersModel->getUserByEmail($users_datas['email']);
        if ($existingUser) {
            flashMessage('Cet email est déjà utilisé.', 'alert-danger');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        //  si le nom déjà utilisé
        $existingUser = $this->usersModel->getUserByName($users_datas['name']);
        if ($existingUser) {
            flashMessage('Ce nom est déjà utilisé.', 'alert-danger');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
