<?php 

if(empty($path[1])) {
    $path[1] = "api_links";
}


$apiController->handleOptionsRequest();
$apiController->setCorsHeaders();

switch ($path[1]) {

    case "getFriends":
        $apiLinksController->getFriends();
        break;

    default:
        $apiController->sendJson(["message" => "Page non trouvée."], 404);
        break;
}