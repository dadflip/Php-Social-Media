<?php
// Initialiser les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure les fichiers de configuration et de gestion des sessions
include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['UID'])) {
    header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG']. $phpfile['mainpage']);
    exit();
}

// Récupérer l'identifiant de la notification à supprimer depuis la requête POST
if(isset($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];
    
    // Créer une instance de CuicuiManager et CuicuiSession
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);
    
    // Définir la requête de suppression
    $deleteQuery = "DELETE FROM notifications WHERE notification_id = ? AND users_uid = ?";
    
    // Préparer la requête
    $deleteStmt = $cuicui_manager->prepare($deleteQuery);
    
    // Exécuter la requête en liant les paramètres
    $deleteStmt->bind_param("ii", $notificationId, $_SESSION['UID']);
    if ($deleteStmt->execute()) {
        // Envoyer une réponse 200 (OK) si la suppression réussit
        http_response_code(200);
        echo "La notification a été supprimée avec succès.";
    } else {
        // Envoyer une réponse 500 (Internal Server Error) en cas d'erreur lors de la suppression
        http_response_code(500);
        echo "Erreur lors de la suppression de la notification.";
    }
} else {
    // Envoyer une réponse 400 (Bad Request) si l'identifiant de la notification n'est pas fourni
    http_response_code(400);
    echo "L'identifiant de la notification n'est pas spécifié.";
}
