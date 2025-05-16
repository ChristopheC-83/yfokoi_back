<?php


if (empty($path[1])) {
    $path[1] = "items";
}
switch ($path[1]) {


     case "addItem":
        $itemsController->addItem($_POST);
        break;
    case "itemIsDone":
        $itemsController->itemIsDone($_POST);
        break;
    case "editMode":
        $itemsController->editMode($_POST);
        break;
    case "updateItem":
        $itemsController->updateItem($_POST);
        break;
    
    case "deleteItem":
        $itemsController->deleteItem($_POST);
        break;
    case "deleteAllDone":
        $itemsController->deleteAllDone($_POST);
        break;


    case "exitEditMode":
        $itemsController->exitEditMode();
        break;


    default:
        throw new Exception("La page List demandée n'existe pas.");
}
