<?php

// // AutoLoader
// spl_autoload_register(function ($class) {
//     // echo "coucou autoloader!";
//     // echo "<br>";
//     // echo $class;
//     // echo "<br>";
//      // Convertir les backslashes en slashes
//     $class = str_replace('\\', '/', $class);

//        // Obtenir le répertoire racine du projet
//     $baseDir = __DIR__ ;  // Cela suppose que ton index.php est dans le répertoire racine de ton projet

//     // Générer le chemin complet du fichier
//     $file = $baseDir . '/' . $class . '.php';


//     // echo "Fichier attendu : " . $file . "<br>";

//     // Vérifier si le fichier existe
//     if (file_exists($file)) {
//         require_once $file;
//     } else {
//         throw new \Exception("Fichier de classe introuvable : " . $file);
//     }
// });

// AutoLoader
spl_autoload_register(function ($class) {
    // Convertir les backslashes en slashes
    $classPath = str_replace('\\', '/', $class);

    $file = __DIR__ . '/' . $classPath . '.php';

    // Vérifier si le fichier existe
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new \Exception("Fichier de classe introuvable : " . $file);
    }
});
