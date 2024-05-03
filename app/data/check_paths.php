<?php

$GLOBALS['__var_sql_path__'] = '/../../../sql';
$GLOBALS['__json_path__'] = '/../json/';

function scanDirectory($dir) {
    $files = [];
    $subdirectories = [];

    // Vérifier si le répertoire est lisible
    if (!is_readable($dir)) {
        // Afficher un message d'erreur si le répertoire n'est pas lisible
        echo "Erreur : Permission refusée pour accéder au répertoire $dir";
        return ['files' => $files, 'subdirectories' => $subdirectories];
    }

    // Ouvrir le répertoire
    $handle = opendir($dir);

    // Parcourir les éléments du répertoire
    while (($file = readdir($handle)) !== false) {
        if ($file != '.' && $file != '..') {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                // Si c'est un répertoire, ajouter à la liste des sous-répertoires et parcourir récursivement
                $subdirectories[$file] = scanDirectory($path);
            } else {
                // Si c'est un fichier, l'ajouter à la liste des fichiers
                $files[] = $file;
            }
        }
    }

    closedir($handle);

    // Retourner les fichiers et les sous-répertoires
    return ['files' => $files, 'subdirectories' => $subdirectories];
}


// Chemin racine du projet
$rootDirectory = dirname(__DIR__);

// Scanner le répertoire racine
$directoryStructure = scanDirectory($rootDirectory);

// Enregistrer la structure du répertoire dans un fichier JSON
file_put_contents(__DIR__.$GLOBALS['__json_path__'].'directory_structure.json', json_encode($directoryStructure, JSON_PRETTY_PRINT));

// echo 'Structure du répertoire enregistrée dans directory_structure.json <br>';


// --------------------------------------------
function createPhpVariables($directoryStructure, $currentPath = '') {
    $phpFiles = [];

    // Parcourir les fichiers du répertoire actuel
    foreach ($directoryStructure['files'] as $file) {
        // Vérifier si le fichier est un fichier .php
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            // Créer une variable pour le fichier .php
            $variableName = pathinfo($file, PATHINFO_FILENAME);
            $filePath = $currentPath . '/' . $file;
            // Enlever le nom du premier dossier du chemin
            $adjustedPath = preg_replace('/\/[^\/]+\//', '/', $filePath, 1);
            ${$variableName} = $adjustedPath;
            $phpFiles[$variableName] = $adjustedPath;
        }
    }

    // Parcourir les sous-répertoires
    foreach ($directoryStructure['subdirectories'] as $subdirectoryName => $subdirectory) {
        // Construire le chemin complet du sous-répertoire
        $subdirectoryPath = $currentPath . '/' . $subdirectoryName;
        // Appeler récursivement la fonction pour chaque sous-répertoire
        $phpFiles = array_merge($phpFiles, createPhpVariables($subdirectory, $subdirectoryPath));
    }

    return $phpFiles;
}

// Charger le contenu de directory_structure.json
$directoryStructureJson = file_get_contents(__DIR__.$GLOBALS['__json_path__'].'directory_structure.json');
$directoryStructure = json_decode($directoryStructureJson, true);

// Créer les variables pour les fichiers .php
$phpFiles = createPhpVariables($directoryStructure);

// Enregistrer les variables dans un fichier JSON
file_put_contents(__DIR__.$GLOBALS['__json_path__'].'php_files.json', json_encode($phpFiles, JSON_PRETTY_PRINT));




// echo 'Variables pour les fichiers PHP créées et enregistrées dans php_files.json <br>';



