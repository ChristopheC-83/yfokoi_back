<?php



if (empty($path[1])) {
    $path[1] = "account";
}

switch ($path[1]) {

    case "register":
        $usersController->registerPage();
        break;
    case "validateAndCreateAccount":
        $usersController->validateAndCreateAccount($_POST);
        break;
    case "connection":
        $usersController->loginPage();
        break;

    case "connectionAccount":
        $usersController->login($_POST);
        break;

    

    case "logout":
        $usersController->logout();
        break;

    case "profile":
        $usersController->profilePage();
        break;
    case "deleteAccount":
        $usersController->deleteAccount();
        break;

    default:
        throw new Exception("La page Account demandée n'existe pas.");
}
