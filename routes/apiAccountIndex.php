<?php

if (empty($path[1])) {
    $path[1] = "api_account";
}
// Gérer les requêtes OPTIONS
$apiController->handleOptionsRequest(); // Appel de la méthode de gestion des OPTIONS
$apiController->setCorsHeaders();


switch ($path[1]) {
    case "register":
        $apiUsersController->registerReact();
        break;
    case "login":
        $apiUsersController->loginReact();
        break;
    case "userContext":
        $apiUserContextController->userContext();
        break;
    // case "profil":
    //     $usersApiController->profilePage();
    //     break;
    // case "modifier-profil":
    //     $usersApiController->updateProfilePage();
    //     break;
    case "delete":
        $apiUsersController->deleteAccount();
        break;
    // default:
    // header("HTTP/1.1 404 Not Found");
    // echo json_encode(["message" => "Page non trouvée."]);

    default:
        $usersApiController->sendJson(["message" => "Page non trouvée."], 404);
}
