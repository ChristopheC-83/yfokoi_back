<?php


if (empty($path[1])) {
    $path[1] = "api_lists";
}

$apiController->handleOptionsRequest();
$apiController->setCorsHeaders();

switch ($path[1]) {

    case "getAllLists":
        $apiListsController->getAllLists();
        break;
    case "getOwnedLists":
        $apiListsController->getOwnedLists();
        break;
    case "getAccessLists":
        $apiListsController->getAccessLists();
        break;
    case "createNewList":
        $apiListsController->createNewList();
        break;
    case "modifyNameList":
        $apiListsController->modifyNameList();
        break;
    case "deleteList":
        $apiListsController->deleteList();
        break;
    default:
        $apiController->sendJson(["message" => "Page non trouvée."], 404);
        break;
}
