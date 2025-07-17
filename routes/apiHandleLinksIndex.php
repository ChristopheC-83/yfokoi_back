<?php

if (empty($path[1])) {
    $path[1] = "api_handle_links";
}


$apiController->handleOptionsRequest();
$apiController->setCorsHeaders();

switch ($path[1]) {

 case "sendFriendRequest":
        $apiHandleLinksController->sendFriendRequest();
        break;
 case "cancelRequest":
        $apiHandleLinksController->cancelRequest();
        break;

    default:
        $apiController->sendJson(["message" => "Page non trouvée."], 404);
        break;
}
