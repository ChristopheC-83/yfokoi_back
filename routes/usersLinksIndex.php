<?php



if (empty($path[1])) {
  $path[1] = "usersLinks";
}

switch ($path[1]) {

  case "searchContact":
    $usersLinksController->searchContact($_POST);
    break;
  case "addContact":
    $usersLinksController->addContact($_POST);
    break;
  case "validateAskFriendRequest":
    $usersLinksController->validateAskFriendRequest($_POST);
    break;

  default:
    throw new Exception("La page de Lien demandée n'existe pas.");
}
