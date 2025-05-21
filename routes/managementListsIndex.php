<?php


if (empty($path[1])) {
    $path[1] = "managementList";
}
switch ($path[1]) {


    case "myLists":
        if (empty($path[2])) {
            $path[2] = -1;
        }
        $managementListsController->managementListsPage($path[2]);
        break;
    case "modifyListAccess":
        $managementListsController->modifyListAccess($_POST);
        break;
    case "deleteListAccess":
        $managementListsController->deleteListAccess($_POST);
        break;






    default:
        throw new Exception("La page ManagementLists demandée n'existe pas.");
}
