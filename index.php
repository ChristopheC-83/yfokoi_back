<?php

require_once __DIR__ . '/vendor/autoload.php';

use Src\Controller\Api\ApiController;
use Src\Controller\Api\ApiListsController;
use Src\Controller\Api\ApiUserContextController;
use Src\Controller\Api\ApiUsersController;
use Src\Controller\Dev\Items\ItemsController;
use Src\Controller\Dev\MainController;
use Src\Controller\Dev\Users\UsersController;
use Src\Controller\Dev\Lists\ListsController;
use Src\Controller\Dev\Lists\ManagementListsController;
use Src\Controller\Dev\Users\UsersContextController;
use Src\Controller\Dev\Users\UsersLinksController;

session_start();

define('BASE_DIR', __DIR__);
define('VIEWS_DIR', BASE_DIR . '/src/Views');
define('PUBLIC_DIR', BASE_DIR);
define('IMAGES_DIR', PUBLIC_DIR . '/images');
define('AVATARS_DIR', IMAGES_DIR . '/avatars');

require_once BASE_DIR . '/autoloader.php';
require_once BASE_DIR . '/helpers.php';

define("ROOT", str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https"  : "http") . "://" . $_SERVER['HTTP_HOST'] .
    $_SERVER["PHP_SELF"]));





$mainController = new MainController();
$usersController = new UsersController();
$listsController = new ListsController();
$itemsController = new ItemsController();
$usersLinksController = new UsersLinksController();
$usersContextController = new UsersContextController();
$managementListsController = new ManagementListsController();
$apiController = new ApiController();
$apiListsController = new ApiListsController();
$apiUsersController = new ApiUsersController();
$apiUserContextController = new ApiUserContextController();


try {
    if (empty($_GET['page'])) {
        $path[0] = "accueil";
    } else {
        $path = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
      
    }


    // dump($path);

    switch ($path[0]) {
        case "accueil":
            $mainController->homePage();
            break;
        case "account":
            require_once BASE_DIR . "/routes/usersIndex.php";
            break;
        case "lists":
            require_once BASE_DIR . "/routes/listsIndex.php";
            break;
        case "items":
            require_once BASE_DIR . "/routes/itemsIndex.php";
            break;
        case "usersLinks":
            require_once BASE_DIR . "/routes/usersLinksIndex.php";
            break;
        case "userContext":
            require_once BASE_DIR . "/routes/userContextIndex.php";
            break;
        case "managementLists":
            require_once BASE_DIR . "/routes/managementListsIndex.php";
            break;

        case "api":
            require_once BASE_DIR . "/routes/apiUsersIndex.php";
            break;
        case "api_lists":
            require_once BASE_DIR . "/routes/apiListsIndex.php";
            break;
        case "api_account":
            require_once BASE_DIR . "/routes/apiAccountIndex.php";
            break;

        default:
            throw new Exception("La page demandée n'existe pas !!!");
    }
} catch (Exception $e) {
    flashMessage("Erreur : " . $e->getMessage(), "alert-danger");
}
