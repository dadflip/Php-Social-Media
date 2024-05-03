<?php
// Inclusion du fichier de définition des chemins
require_once __DIR__.'/defs.paths.php';

// Configuration de la base de données
$database_configs = array(
    'databases' => array(
        // -- jeu de données 0
        array(
            'host' => 'localhost',
            'name' => 'cuicui_db',
            'user' => 'root',
            'password' => ''
        ),
        // -- jeu de données 1
        /*array(
            'host' => 'localhost',
            'name' => '-- configurer --',
            'user' => '-- configurer --',
            'password' => '-- configurer --',
        ),
        // -- jeu de données 2
        array(
            'host' => 'localhost',
            'name' => '-- configurer --',
            'user' => '-- configurer --',
            'password' => '-- configurer --',
        )*/
    )
);

// Définition des variables globales
global $database_configs;
$GLOBALS['json_paths'] = __DIR__.'/json/normalized_paths.json';
$GLOBALS['json_dir_paths'] = __DIR__.'/json/directory_structure.json';
$GLOBALS['json_files'] = __DIR__.'/json/php_files.json';
$GLOBALS['img_upload'] = '/users/media/';

// Récupération des chemins normalisés depuis le fichier JSON
$normalized_paths_json = file_get_contents($GLOBALS['json_paths']);
$normalized_paths = json_decode($normalized_paths_json, true);

// Vérification du décodage JSON
if ($normalized_paths === null) {
    die('<br> Erreur lors du décodage du JSON.');
}

// Stockage des chemins normalisés dans un tableau
$appdir = [];
foreach ($normalized_paths as $pathName => $pathValue) {
    $appdir[$pathName] = $pathValue;
}

// Définition des variables globales de chemin
$GLOBALS['_up'] = '.' . $appdir['UP'];
$GLOBALS['_up2'] = '.' . $appdir['2UP'];
$GLOBALS['_up3'] = '.' . $appdir['3UP'];

//-----------------------

// Chargement du contenu du fichier JSON contenant les chemins des fichiers PHP
$php_files_json = file_get_contents($GLOBALS['json_files']);
$php_files = json_decode($php_files_json, true);

// Vérification du décodage JSON
if ($php_files === null) {
    die('Erreur lors du décodage du JSON.');
}

// Stockage des fichiers PHP dans un tableau
$phpfile = [];
foreach ($php_files as $fileName => $filePath) {
    $phpfile[$fileName] = $filePath;
}

// Affichage des variables
// echo $appdir['PATH_EN'];

// -----------------------

// Chemin absolu vers le répertoire racine du site web
$root_path = $appdir['PATH_CUICUI_PROJECT'];

// Générer le contenu du fichier .htaccess pour toutes les erreurs du serveur
$htaccess_content = "";
$http_error_codes = [400, 401, 403, 404, 500, 503]; // Ajoutez d'autres codes d'erreur si nécessaire

foreach ($http_error_codes as $error_code) {
    // Chemin vers le fichier error404.php pour le code d'erreur actuel
    $error_path = $root_path . '/error' . $error_code . '.php';
    // Ajouter la directive ErrorDocument au contenu du fichier .htaccess
    $htaccess_content .= "ErrorDocument $error_code $error_path\n";
}

// Écrire le contenu dans le fichier .htaccess
file_put_contents(__DIR__ . '/../.htaccess', $htaccess_content);

if(isset($_SESSION["UID"])) {
    header('Location:' . $appdir['PATH_CUICUI_APP']);
}

// echo "Le fichier .htaccess a été généré avec succès.";

