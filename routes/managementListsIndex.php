<?php


if (empty($path[1])) {
    $path[1] = "managementList";
}
switch ($path[1]) {


     case "myLists":
        $managementListsController->managementListsPage($path[2]);
        break;
     case "modifyListAccess":
        dump($_POST);
        break;
    


   


    default:
        throw new Exception("La page ManagementLists demandée n'existe pas.");
}
