<?php
require_once __DIR__.'/defs.php';

function determineLanguage() {
    // Vérifier si la variable globale LANG est définie
    if (!isset($GLOBALS['LANG'])) {
        // Déterminer la langue à partir de l'en-tête HTTP_ACCEPT_LANGUAGE
        $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        // Définir la variable globale LANG
        $GLOBALS['LANG'] = $language;
    }
    // Retourner la langue
    return $GLOBALS['LANG'];
}

function resolveBack($count) {
    // Récupérer le chemin du répertoire actuel
    $currentDir = realpath(__DIR__);

    // Remonter dans l'arborescence du répertoire selon le nombre spécifié
    for ($i = 0; $i < $count; $i++) {
        $currentDir = dirname($currentDir);
    }

    // Retourner le chemin résolu
    return $currentDir;
}

function includeIfDefined(...$segments) {
    // Vérifier si aucun segment n'a été passé
    if (empty($segments)) {
        echo "Erreur: Aucun segment fourni.<br>";
        return;
    }

    // Vérifier si chaque segment est défini
    /*foreach ($segments as $segment) {
        if (is_string($segment)) {
            // Si c'est une chaîne de caractères, on vérifie si la constante est définie
            if (!defined($segment)) {
                echo "Erreur: Constante '$segment' non définie.<br>";
                return;
            }
        } else {
            // Si ce n'est pas une chaîne de caractères, on vérifie si c'est une constante définie
            if (!defined($segment[0])) {
                echo "Erreur: Constante '$segment[0]' non définie.<br>";
                return;
            }
        }
    }*/

    // Construire le chemin complet en utilisant les constantes et les chaînes de caractères
    $filePath = '';
    foreach ($segments as $segment) {
        if (is_string($segment)) {
            if (substr($segment, 0, 5) === 'back(' && substr($segment, -1) === ')') {
                // Extraire le nombre spécifié dans back()
                $count = intval(substr($segment, 5, -1));
                // Résoudre le chemin relatif
                $resolvedPath = resolveBack($count);
                // Ajouter le chemin résolu
                $filePath .= $resolvedPath;
            } else {
                $filePath .= $segment;
            }
        } else {
            $filePath .= constant($segment[0]) . $segment[1];
        }
    }

    // Vérifier si le fichier existe avant de l'inclure
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        echo "Erreur: Fichier non trouvé à l'emplacement '$filePath'.<br>";
    }
}

function baseDir($path) {
    // Supprimer les éventuels slash à la fin du chemin
    $path = rtrim($path, '/');
    // Extraire le nom du dernier dossier
    $lastFolder = '/' . basename($path);
    return $lastFolder;
}

// ------------
determineLanguage();