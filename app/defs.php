<?php
// Include the file defining paths
require_once __DIR__.'/defs.paths.php';

// Database configuration
$database_configs = [
    'databases' => [
        [
            'host' => 'localhost',
            'name' => 'PY_CHINEDUM_CUICUI_APP',
            'user' => 'root',
            'password' => ''
        ],
    ]
];

// Define global variables
$GLOBALS['json_paths'] = __DIR__.'/json/normalized_paths.json';
$GLOBALS['json_dir_paths'] = __DIR__.'/json/directory_structure.json';
$GLOBALS['json_files'] = __DIR__.'/json/php_files.json';
$GLOBALS['img_upload'] = '/users/media/';

// Retrieve normalized paths from JSON file
$normalized_paths_json = file_get_contents($GLOBALS['json_paths']);
$normalized_paths = json_decode($normalized_paths_json, true);

// Check JSON decoding
if ($normalized_paths === null) {
    die('<br> Error decoding JSON.');
}

// Store normalized paths in an array
$appdir = [];
foreach ($normalized_paths as $pathName => $pathValue) {
    $appdir[$pathName] = $pathValue;
}

// Define global path variables
$GLOBALS['_up'] = '.' . $appdir['UP'];
$GLOBALS['_up2'] = '.' . $appdir['2UP'];
$GLOBALS['_up3'] = '.' . $appdir['3UP'];

// Load the content of the JSON file containing PHP file paths
$php_files_json = file_get_contents($GLOBALS['json_files']);
$php_files = json_decode($php_files_json, true);

// Check JSON decoding
if ($php_files === null) {
    die('Error decoding JSON.');
}

// Store PHP files in an array
$phpfile = [];
foreach ($php_files as $fileName => $filePath) {
    $phpfile[$fileName] = $filePath;
}

// Absolute path to the website root directory
$root_path = $appdir['PATH_CUICUI_PROJECT'];

// Generate .htaccess content for all server errors
$htaccess_content = "";
$http_error_codes = [400, 401, 403, 404, 500, 503]; // Add more error codes if necessary

foreach ($http_error_codes as $error_code) {
    $error_path = $root_path . '/error' . $error_code . '.php';
    $htaccess_content .= "ErrorDocument $error_code $error_path\n";
}

// Write content to .htaccess file
file_put_contents(__DIR__ . '/../.htaccess', $htaccess_content);

if(isset($_SESSION["UID"])) {
    header('Location:' . $appdir['PATH_CUICUI_APP']);
}