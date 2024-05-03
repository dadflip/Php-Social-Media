<?php

// Déclaration de la variable globale pour la langue choisie
global $LANGUAGE_CHOSEN;

// Définition des constantes
define('path_BASE_PATH', __DIR__);
define('path_ROOT', '/');
define('path_FLIP_APP', rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', path_BASE_PATH), '/') . '/');
define('path_FLIP_MAIN', path_FLIP_APP . '@app/');

// Chemins prédéfinis pour les différentes langues
define('path_FR', 'fr/');
define('path_EN', 'en/');
// Ajoutez d'autres langues si nécessaire..

// Répertoires et chemins
define('path_ASSETS', path_FLIP_APP . '_assets');
define('path_CSS_DIR', path_ASSETS . '/css');
define('path_IMG_DIR', path_ASSETS . '/img');
define('path_JS_DIR', path_ASSETS . '/js');
define('path_PHP_DIR', path_ASSETS . '/php');
define('path_AUTH', path_FLIP_MAIN . $LANGUAGE_CHOSEN . 'authentication/');
define('path_USR', path_FLIP_APP . '_usr');
define('path_USR_PHP_DIR', path_FLIP_APP . '/.php');
define('path_USR_MEDIA', path_FLIP_APP . 'media');


// Déclaration des autres variables globales
global $userId;
global $userEmail;

?>
