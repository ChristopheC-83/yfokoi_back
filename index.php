<?php

require_once __DIR__ . '/vendor/autoload.php';


use Src\Controller\Dev\MainController;
use Src\Controller\Dev\Users\UsersController;
use Src\Controller\Dev\Lists\ListsController;

session_start();

define('BASE_DIR', __DIR__);
define('VIEWS_DIR', BASE_DIR . '/src/Views');
define('PUBLIC_DIR', BASE_DIR);
define('IMAGES_DIR', PUBLIC_DIR . '/images');
define('AVATARS_DIR', IMAGES_DIR . '/avatars');

require_once BASE_DIR . '/autoloader.php';
require_once BASE_DIR . '/helpers.php';

define("ROOT", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https"  : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER["PHP_SELF"]));

$mainController = new MainController();
$usersController = new UsersController();
$listsController = new ListsController();

try {
    if (empty($_GET['page'])) {
        $path[0] = "accueil";
    } else {
        $path = explode("/", filter_var($_GET["page"], FILTER_SANITIZE_URL));
    }


    // dump($path);

    switch ($path[0]) {
        case "accueil":
            // $_SESSION=[];
            $mainController->homePage();
            break;

        case "account":
            require_once BASE_DIR . "/routes/usersIndex.php";
            break;
        case "lists":
            require_once BASE_DIR . "/routes/listsIndex.php";
            break;

        default:
            throw new Exception("La page demandée n'existe pas !!!");
    }
} catch (Exception $e) {
    flashMessage ("Erreur : " . $e->getMessage(), "alert-danger");
}
