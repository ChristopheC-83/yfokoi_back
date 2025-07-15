<?php

if (empty($path[1])) {
    $path[1] = "api_links";
}


$apiController->handleOptionsRequest();
$apiController->setCorsHeaders();

switch ($path[1]) {

    case "getMyFriends":
        $apiLinksController->getMyFriends();
        break;
    case "sentRequest":
        $apiLinksController->sentRequest();
        break;
    case "receivedRequest":
        $apiLinksController->receivedRequest();
        break;

    default:
        $apiController->sendJson(["message" => "Page non trouvée."], 404);
        break;
}
