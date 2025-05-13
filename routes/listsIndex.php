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
    case "deleteList":
        $listsController->deleteList();
        break;
   

    default:
        throw new Exception("La page List demandée n'existe pas.");
}