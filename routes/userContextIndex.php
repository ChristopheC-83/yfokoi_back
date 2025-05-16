<?php


if (empty($path[1])) {
    $path[1] = "userContext";
}
switch ($path[1]) {

    case "favoriteList":
        $usersContextController->favoriteList($_POST);
        break;
    case "unsetFavoriteList":
        $usersContextController->unsetFavoriteList();
        break;
}
