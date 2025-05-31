<?php


if (empty($path[1])) {
    $path[1] = "api_lists";
}

$apiController->handleOptionsRequest();

switch ($path[1]) {

    case "getAllLists":
        $apiListsController->getAllLists();
        break;
}
