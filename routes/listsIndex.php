<?php



if (empty($path[1])) {
    $path[1] = "lists";
}
switch ($path[1]) {

    case "newList":
        $listsController->createList($_POST);
        break;
    case "selectList":
        $listsController->selectList($_POST);
        break;
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
    case "exitEditMode":
        $listsController->exitEditMode();
        break;
    case "deleteItem":
        $listsController->deleteItem($_POST);
        break;
    case "deleteAllDone":
        $listsController->deleteAllDone($_POST);
        break;

    default:
        throw new Exception("La page List demandée n'existe pas.");
}