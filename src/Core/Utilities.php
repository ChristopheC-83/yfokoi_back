<?php

declare(strict_types=1);

namespace Src\Core;

class Utilities
{
    public static function renderPage(array $datas_page): void
    {
        // Constante de base pour les vues
        $viewsPath = VIEWS_DIR ?? BASE_DIR . '/src/Views';

        // Extraire les données fournies (comme $title, $view, $layout, etc.)
        extract($datas_page);

        // Vérifier la présence et l'existence du fichier de vue
        if (!isset($view) || !file_exists($viewsPath . '/' . $view)) {
            throw new \Exception("Vue introuvable : " . ($view ?? 'non définie'));
        }

        // Vérifier la présence et l'existence du layout
        if (!isset($layout) || !file_exists($viewsPath . '/' . $layout)) {
            throw new \Exception("Layout introuvable : " . ($layout ?? 'non défini'));
        }

        // Inclure les helpers uniquement pour les vues
        $helpersPath = BASE_DIR . '/helpers.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        // Bufferisation de la vue
        ob_start();
        require $viewsPath . '/' . $view;
        $content = ob_get_clean();

        // Chargement du layout
        require_once $viewsPath . '/' . $layout;
    }

    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    public static function dump($array): void
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public static function flashMessage(string $message, string $type = "success"): void
    {
        $_SESSION['alert'] = [
            'message' => $message,
            'type' => $type,
        ];
    }
}
