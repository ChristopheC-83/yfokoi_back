<?php 

if(empty($path[1])) {
    $path[1] = "api_items";
}


$apiController->handleOptionsRequest();
$apiController->setCorsHeaders();

switch ($path[1]) {

    case "getAllMyItems":
        $apiItemsController->getAllMyItems();
        break;
    // case "getItemsByListId":
    //     $apiItemsController->getItemsByListId();
    //     break;
    // case "addItem":
    //     $apiItemsController->addItem();
    //     break;
    // case "updateItem":
    //     $apiItemsController->updateItem();
    //     break;
    // case "deleteItem":
    //     $apiItemsController->deleteItem();
    //     break;
    // case "deleteAllDone":
    //     $apiItemsController->deleteAllDone();
    //     break;
    default:
        $apiController->sendJson(["message" => "Page non trouvée."], 404);
        break;
}