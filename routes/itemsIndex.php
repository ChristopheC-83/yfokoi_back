<?php


if (empty($path[1])) {
    $path[1] = "items";
}
switch ($path[1]) {


     case "addItem":
        $listsController->addItem($_POST);
        break;
    case "itemIsDone":
        $listsController->itemIsDone($_POST);
        break;
    case "editMode":
        $listsController->editMode($_POST);
        break;
    case "updateItem":
        $listsController->updateItem($_POST);
        break;
    
    case "deleteItem":
        $listsController->deleteItem($_POST);
        break;
    case "deleteAllDone":
        $listsController->deleteAllDone($_POST);
        break;


    case "exitEditMode":
        $listsController->exitEditMode();
        break;


    default:
        throw new Exception("La page List demandée n'existe pas.");
}
