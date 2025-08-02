<?php

if (empty($path[1])) {
    $path[1] = "api_shares";
}
// Gérer les requêtes OPTIONS
$apiController->handleOptionsRequest(); // Appel de la méthode de gestion des OPTIONS
$apiController->setCorsHeaders();


switch ($path[1]) {
    case "getAllShares":
        $apiSharesController->getAllShares();
        break;

    default:
        $sharesApiController->sendJson(["message" => "Page non trouvée."], 404);
}
