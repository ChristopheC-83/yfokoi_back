<?php

if (empty($path[1])) {
    $path[1] = "api_account";
}
// Gérer les requêtes OPTIONS
$apiController->handleOptionsRequest(); // Appel de la méthode de gestion des OPTIONS


switch ($path[1]) {
    // case "inscription":
    //     $usersApiController->registerReact();
    //     break;
    case "login":
        $apiUsersController->loginReact();
        break;
    // case "deconnexion":
    //     $usersApiController->logoutReact();
    //     break;
    // case "profil":
    //     $usersApiController->profilePage();
    //     break;
    // case "modifier-profil":
    //     $usersApiController->updateProfilePage();
    //     break;
    // case "supprimer-profil":
    //     $usersApiController->deleteProfilePage();
    //     break;
    // default:
    // header("HTTP/1.1 404 Not Found");
    // echo json_encode(["message" => "Page non trouvée."]);

    default:
        $usersApiController->sendJson(["message" => "Page non trouvée."], 404);
}
