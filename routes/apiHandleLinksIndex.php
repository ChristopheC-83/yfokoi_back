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
 case "breakLink":
        $apiHandleLinksController->breakLink();
        break;
 case "acceptRequest":
        $apiHandleLinksController->acceptRequest();
        break;
 case "declineRequest":
        $apiHandleLinksController->declineRequest();
        break;

    default:
        $apiController->sendJson(["message" => "Page non trouvée."], 404);
        break;
}
