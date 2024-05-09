<?php
include '../../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

session_start();

if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);
}else{
    header('Location:' . $appdir['PATH_CUICUI_APP']);
}

function generateServerReport() {
    // Récupérer les informations sur le serveur
    $serverInfo = array(
        "Server Name" => $_SERVER['SERVER_NAME'],
        "Server Address" => $_SERVER['SERVER_ADDR'],
        "Server Port" => $_SERVER['SERVER_PORT'],
        "Server Software" => $_SERVER['SERVER_SOFTWARE'],
        "Document Root" => $_SERVER['DOCUMENT_ROOT'],
        "PHP Version" => phpversion(),
        "Current Time" => date('Y-m-d H:i:s')
    );

    // Générer le rapport au format HTML
    $html = "<h2>Rapport sur l'utilisation du serveur</h2>";
    $html .= "<table border='1'>";
    foreach ($serverInfo as $key => $value) {
        $html .= "<tr><td>{$key}</td><td>{$value}</td></tr>";
    }
    $html .= "</table>";

    return $html;
}

echo generateServerReport();
